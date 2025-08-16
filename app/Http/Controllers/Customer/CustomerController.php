<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return view('Customer.CustomerView');
    }

    public function getCustomers(Request $request)
    {
        $query = Customer::query();

        $totalData = $query->count();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where('customer_name', 'like', "%{$search}%");
        }

        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['customer_name', 'email', 'phone', 'address'];
            $query->orderBy($columns[$orderColIndex], $orderDir);
        }

        // Pagination
        $customers = $query
                ->offset($request->input('start'))
                ->limit($request->input('length'))
                ->get();

        $data = $customers->map(function ($customer) {
            return [
                'customer_name' => $customer->customer_name,
                'email'         => $customer->email,
                'phone'         => $customer->phone,
                'address'       => $customer->address ?? 'N/A',
                'action'        => '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="'. route('customers.edit', $customer->id) .'">
                                <i class="ti ti-pencil me-1"></i> Edit
                            </a>
                            <a class="dropdown-item text-danger" href="javascript:void(0);" 
                            onclick="deleteCustomer(\'' . route('customers.destroy', $customer->id) . '\')">
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
        return view('Customer.CreateCustomerView', ['customer' => new Customer()]);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        // Create a new customer
        $customer = Customer::create([
            'customer_name' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Redirect to customer list with success message
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('Customer.CreateCustomerView', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
       $validatedData =  $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        // Find the customer and update
        $customer = Customer::findOrFail($id);
  
        $customer->customer_name = $validatedData['fullname'];
        $customer->email = $validatedData['email'];
        $customer->phone = $validatedData['phone'];
        $customer->address = $validatedData['address'];
        $customer->save();

        // Redirect to customer list with success message
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $product = Customer::findOrFail($id);

        $product->delete();

        return response()->json(['message' => 'Customer deleted successfully.'], 200);
    }
}
