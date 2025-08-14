<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('Product/ProductView');
    }

    public function create()
    {
        return view('Product/CreateProductView');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'productName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'productPrice' => 'required|numeric|min:0',
            'imagePath' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'nullable|string|max:255',
            'stockQuantity' => 'required|integer|min:0',
            'status' => 'nullable|in:Published,Inactive',
        ]);

        // Handle file upload if image is provided
        $imagePath = null;
        if ($request->hasFile('imagePath')) {
            $imagePath = $request->file('imagePath')->store('products', 'public');
        }

        // Create a new product by assigning each field individually
        $product = new Product();
        $product->product_name = $validatedData['productName'];
        $product->description = $validatedData['description'] ?? null;
        $product->price = $validatedData['productPrice'];
        $product->img_path = $imagePath;
        $product->category_id = $validatedData['category'] ?? null;
        $product->stock = $validatedData['stockQuantity'];
        $product->status = $validatedData['status'] ?? 'Published';
        $product->save();

        // Redirect to product list with success message
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }


}
