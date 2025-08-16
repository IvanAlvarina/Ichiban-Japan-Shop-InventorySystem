@extends('Layouts.vuexy')

@section('title', 'Add Product')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="app-ecommerce">

        <form action="{{ isset($product->id) ? route('products.update', $product->id) : route('products.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @if(isset($product->id))
                @method('PUT')
            @endif

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                <div>
                    <h4 class="mb-1 mt-3">{{ isset($product->id) ? 'Edit Product' : 'Add a new Product' }}</h4>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-label-secondary">Discard</a>
                    <button type="submit" class="btn btn-primary">
                        {{ isset($product->id) ? 'Update Product' : 'Publish Product' }}
                    </button>
                </div>
            </div>

            <div class="row">
                <!-- First column -->
                <div class="col-12 col-lg-8">
                    <!-- Product Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Product Information</h5>
                        </div>
                        <div class="card-body">
                            <!-- Name -->
                            <div class="mb-3">
                                <label for="ecommerce-product-name" class="form-label">Name</label>
                                <input type="text"
                                       name="productName"
                                       id="ecommerce-product-name"
                                       class="form-control @error('productName') is-invalid @enderror"
                                       placeholder="Product title"
                                       value="{{ old('productName', $product->product_name ?? '') }}">
                                @error('productName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">Description (Optional)</label>
                                <textarea name="description"
                                          rows="4"
                                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Media -->
                            <div class="mb-3">
                                <label for="imagePath" class="form-label">Image (Optional)</label>
                                @if(isset($product->img_path))
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $product->img_path) }}" alt="Product Image" width="150">
                                        <p class="mt-1"><strong>Current file:</strong> {{ basename($product->img_path) }}</p>
                                    </div>
                                @endif

                                <input type="file"
                                       name="imagePath"
                                       class="form-control @error('imagePath') is-invalid @enderror">
                                @error('imagePath')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Inventory -->
                            <div class="mb-3">
                                <label for="ecommerce-product-stock" class="form-label">Stock Quantity</label>
                                <input type="number"
                                       name="stockQuantity"
                                       id="ecommerce-product-stock"
                                       class="form-control @error('stockQuantity') is-invalid @enderror"
                                       placeholder="Quantity"
                                       value="{{ old('stockQuantity', $product->stock ?? '') }}">
                                @error('stockQuantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second column -->
                <div class="col-12 col-lg-4">
                    <!-- Pricing -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Pricing</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="ecommerce-product-price" class="form-label">Base Price</label>
                                <input type="number"
                                       name="productPrice"
                                       id="ecommerce-product-price"
                                       class="form-control @error('productPrice') is-invalid @enderror"
                                       placeholder="Price"
                                       value="{{ old('productPrice', $product->price ?? '') }}">
                                @error('productPrice')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Organize -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Organize</h5>
                        </div>
                        <div class="card-body">
                            <!-- Category -->
                            <div class="mb-3">
                                <label for="category-org" class="form-label">Category</label>
                                <select name="category"
                                        id="category-org"
                                        class="form-select @error('category') is-invalid @enderror">
                                    <option value="">Select Category</option>
                                    <option value="Toys & Collectibles" {{ old('category', $product->category_id ?? '') == 'Toys & Collectibles' ? 'selected' : '' }}>Toys & Collectibles</option>
                                    <option value="Action Figures" {{ old('category', $product->category_id ?? '') == 'Action Figures' ? 'selected' : '' }}>Action Figures</option>
                                    <option value="Hobby & Collectibles" {{ old('category', $product->category_id ?? '') == 'Hobby & Collectibles' ? 'selected' : '' }}>Hobby & Collectibles</option>
                                    <option value="Entertainment Merchandise" {{ old('category', $product->category_id ?? '') == 'Entertainment Merchandise' ? 'selected' : '' }}>Entertainment Merchandise</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status-org" class="form-label">Status</label>
                                <select name="status"
                                        id="status-org"
                                        class="form-select @error('status') is-invalid @enderror">
                                    <option value="In Stock" {{ old('status', $product->status ?? '') == 'In Stock' ? 'selected' : '' }}>In Stock</option>
                                    <option value="Out of Stock" {{ old('status', $product->status ?? '') == 'Out of Stock' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection
