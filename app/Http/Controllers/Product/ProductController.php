<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    
    public function index()
    {
        return view('Product.ProductView');
    }

    public function getProducts(Request $request){
        
        $query = Product::where('status', 'In Stock');

        $totalData = $query->count();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where('product_name', 'like', "%{$search}%");
        }

        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['product_name', 'description', 'price', 'category_id', 'stock', 'status'];
            $query->orderBy($columns[$orderColIndex], $orderDir);
        }

        // Pagination
        $products = $query
                ->offset($request->input('start'))
                ->limit($request->input('length'))
                ->get();

        $data = $products->map(function ($product) {
            return [
                'product_name' => $product->product_name,
                'description'  => $product->description,
                'price'        => $product->price,
                'category'     => $product->category_id ?? 'N/A',
                'stock'        => $product->stock,
                'status'       => '<span class="badge bg-label-success">'
                                    . e($product->status) .
                                '</span>',
                'action'       => '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . route('products.edit', $product->id) . '">
                                <i class="ti ti-pencil me-1"></i> Edit
                            </a>
                            <a class="dropdown-item text-danger" href="javascript:void(0);" 
                            onclick="deleteProduct(\'' . route('products.destroy', $product->id) . '\')">
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
        return view('Product/CreateProductView', ['product' => new Product()]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'productName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'productPrice' => 'required|numeric|min:0',
            'imagePath' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'category' => 'nullable|string|max:255',
            'stockQuantity' => 'required|integer|min:0',
            'status' => 'nullable|in:In Stock,Out of Stock',
        ]);

        // Prepare image path
        $imagePath = null;
        if ($request->hasFile('imagePath')) {
            $file = $request->file('imagePath');
            $originalName = $file->getClientOriginalName();
            $file->storeAs('products', $originalName, 'public');
            $imagePath = 'products/' . $originalName;
        }

        // Create and save product
        $product = new Product();
        $product->product_name = $validatedData['productName'];
        $product->description = $validatedData['description'] ?? null;
        $product->price = $validatedData['productPrice'];
        $product->img_path = $imagePath;
        $product->category_id = $validatedData['category'] ?? null;
        $product->stock = $validatedData['stockQuantity'];
        $product->status = $validatedData['status'] ?? 'Published';
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }


    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('Product.CreateProductView', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'productName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'productPrice' => 'required|numeric|min:0',
            'imagePath' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'category' => 'nullable|string|max:255',
            'stockQuantity' => 'required|integer|min:0',
            'status' => 'nullable|in:In Stock,Out of Stock',
        ]);

        $product = Product::findOrFail($id);

        // If a new file is uploaded, delete the old one and save the new one
        if ($request->hasFile('imagePath')) {
            // Delete old file if exists
            if ($product->img_path && Storage::disk('public')->exists($product->img_path)) {
                Storage::disk('public')->delete($product->img_path);
            }

            // Store new file with original name
            $file = $request->file('imagePath');
            $originalName = $file->getClientOriginalName();
            $file->storeAs('products', $originalName, 'public');
            $product->img_path = 'products/' . $originalName;
        }

        // Update other fields
        $product->product_name = $validatedData['productName'];
        $product->description = $validatedData['description'] ?? null;
        $product->price = $validatedData['productPrice'];
        $product->category_id = $validatedData['category'] ?? null;
        $product->stock = $validatedData['stockQuantity'];
        $product->status = $validatedData['status'] ?? 'Published';
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->img_path && Storage::disk('public')->exists($product->img_path)) {
            Storage::disk('public')->delete($product->img_path);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.'], 200);
    }



}
