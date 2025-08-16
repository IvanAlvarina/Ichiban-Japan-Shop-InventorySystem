<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\OrderItems;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index()
    {
        return view('Order.Orderview');
    }

    public function getOrders(Request $request)
    {
        $query = Order::with('customer')
              ->withCount(['orderItems as totalOrders']);

        $totalData = $query->count();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%");
        }

        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $columns = ['id', 'customer_id', 'total', 'status', 'order_date'];
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $query->orderBy($columns[$orderColIndex], $orderDir);
        }

        // Pagination
        $orders = $query
            ->offset($request->input('start'))
            ->limit($request->input('length'))
            ->get();

        $data = $orders->map(function ($order) {
            return [
                'id'       => $order->id,
                'customer' => $order->customer->customer_name ?? '-',
                'total'    => $order->total,
                'totalOrders' => $order->totalOrders,
                'order_date' => $order->created_at->format('Y-m-d'),
                'action'   => '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="'. route('orders.edit', $order->id) .'">
                                <i class="ti ti-pencil me-1"></i> Edit
                            </a>
                            <a class="dropdown-item text-danger" href="javascript:void(0);" 
                            onclick="deleteOrder(\'' . route('orders.destroy', $order->id) . '\')">
                                <i class="ti ti-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>'
            ];
        });

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ]);
    }

    public function create()
    {
        // Load customers and products for dropdowns
        $customers = Customer::all();
        $products  = Product::where('status', 'In Stock')->get();

        return view('Order.CreateOrderView', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        try {
            // Validate main order data
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'order_date' => 'required|date',
                'total' => 'required|numeric',
                'balance' => 'nullable|numeric',
                'product_id.*' => 'required|exists:products,id',
                'quantity.*' => 'required|numeric|min:1',
                'price.*' => 'required|numeric|min:0',
                'subtotal.*' => 'required|numeric|min:0',
                'downpayment.*' => 'nullable|numeric|min:0',
                'status.*' => 'required|in:pending,completed,cancelled',
            ]);

             // Check if customer already has an order
            $existingOrder = Order::where('customer_id', $request->customer_id)->first();
            if ($existingOrder) {
                return back()
                    ->with('error', 'This customer already has an order. If you want to add another one, please edit the existing order.')
                    ->withInput();
            }

            // Start database transaction
            DB::beginTransaction();

            // Calculate totals from line items
            $totalAmount = 0;
            $totalDownpayment = 0;
            
            foreach ($request->product_id as $index => $productId) {
                $totalAmount += $request->subtotal[$index];
                $totalDownpayment += $request->downpayment[$index] ?? 0;
            }

            // Create the order
            $order = new Order();
            $order->customer_id = $request->customer_id;
            $order->order_date = $request->order_date;
            $order->total = $totalAmount;
            $order->downpayment = $totalDownpayment;
            $order->balance = $totalAmount - $totalDownpayment;
            $order->save();

            // Save order items and deduct stock
            foreach ($request->product_id as $index => $productId) {
                $quantity = $request->quantity[$index];

                // Check and deduct stock
                $product = Product::find($productId);
                if (!$product) {
                    throw new \Exception("Product not found");
                }
                
                if ($product->stock < $quantity) {
                    throw new \Exception("Not enough stock for product {$product->product_name}. Available: {$product->stock}, Required: {$quantity}");
                }
                
                $product->stock -= $quantity;
                $product->save();

                // Create order item
                OrderItems::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $request->price[$index],
                    'subtotal' => $request->subtotal[$index],
                    'downpayment' => $request->downpayment[$index] ?? 0,
                    'status' => $request->status[$index],
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order created and stock updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating order: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        $customers = Customer::all();
        $products = Product::where('status', 'In Stock')->get();

        return view('Order.CreateOrderView', compact('order', 'customers', 'products'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'order_date' => 'required|date',
                'total' => 'nullable|numeric',
                'balance' => 'nullable|numeric',
                // Only validate items if present
                'product_id.*' => 'nullable|exists:products,id',
                'quantity.*' => 'nullable|numeric|min:1',
                'price.*' => 'nullable|numeric|min:0',
                'subtotal.*' => 'nullable|numeric|min:0',
                'downpayment.*' => 'nullable|numeric|min:0',
                'status.*' => 'nullable|in:pending,completed,cancelled',
            ]);

            DB::beginTransaction();

            $order = Order::findOrFail($id);

            // --- Restore stock & delete all old items first ---
            foreach ($order->orderItems as $item) {
                $item->product->stock += $item->quantity;
                $item->product->save();
                $item->delete();
            }

            // ✅ If no items submitted → delete whole order
            if (!$request->has('product_id') || count($request->product_id) === 0) {
                $order->delete();
                DB::commit();
                return redirect()->route('orders.index')
                    ->with('success', 'Order deleted because it had no items.');
            }

            // --- Otherwise re-save items ---
            $totalAmount = 0;
            $totalDownpayment = 0;

            foreach ($request->product_id as $index => $productId) {
                if (!$productId) continue; // skip empty rows

                $quantity = $request->quantity[$index];
                $product = Product::findOrFail($productId);

                if ($product->stock < $quantity) {
                    throw new \Exception("Not enough stock for product {$product->product_name}");
                }

                $product->stock -= $quantity;
                $product->save();

                $subtotal = $request->subtotal[$index] ?? ($quantity * $request->price[$index]);
                $downpayment = $request->downpayment[$index] ?? 0;

                OrderItems::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $request->price[$index],
                    'subtotal' => $subtotal,
                    'downpayment' => $downpayment,
                    'status' => $request->status[$index] ?? 'pending',
                ]);

                $totalAmount += $subtotal;
                $totalDownpayment += $downpayment;
            }

            // Update order totals
            $order->customer_id = $request->customer_id;
            $order->order_date = $request->order_date;
            $order->total = $totalAmount;
            $order->downpayment = $totalDownpayment;
            $order->balance = $totalAmount - $totalDownpayment;
            $order->save();

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating order: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);

            // Restore stock
            foreach ($order->orderItems as $item) {
                $item->product->stock += $item->quantity;
                $item->product->save();
                $item->delete();
            }

            // Delete order
            $order->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting order: '.$e->getMessage()
            ], 500);
        }
    }



}