@extends('layouts.vuexy')

@section('title', 'Dashboard')

@section('content')


 <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-0">
        {{-- <span class="text-muted fw-light">eCommerce /</span><span class="fw-medium"> Add Product</span> --}}
        </h4>

        <div class="app-ecommerce">

            <!-- Add Product -->
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                    <div class="d-flex flex-column justify-content-center">
                        <h4 class="mb-1 mt-3">Add a new Product</h4>
                    </div>
                    <div class="d-flex align-content-center flex-wrap gap-3">
                        <div class="d-flex gap-3">
                            <button type="reset" class="btn btn-label-secondary">Discard</button>
                        </div>
                        <button type="submit" class="btn btn-primary">Publish product</button>
                    </div>
                </div>

                <div class="row">
                    <!-- First column-->
                    <div class="col-12 col-lg-8">
                        <!-- Product Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-tile mb-0">Product information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label" for="ecommerce-product-name">Name</label>
                                    <input
                                        type="text"
                                        class="form-control @error('productName') is-invalid @enderror"
                                        id="ecommerce-product-name"
                                        placeholder="Product title"
                                        name="productName"
                                        value="{{ old('productName') }}"
                                    />
                                    @error('productName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label class="form-label">Description (Optional)</label>
                                    <textarea
                                        class="form-control @error('description') is-invalid @enderror"
                                        name="description"
                                        id="description"
                                        rows="4"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Media -->
                                <div class="mb-3">
                                    <label for="imagePath" class="form-label">Product Image</label>
                                    <input
                                        name="imagePath"
                                        type="file"
                                        class="form-control @error('imagePath') is-invalid @enderror"
                                    />
                                    @error('imagePath')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Inventory -->
                                <div class="mb-3">
                                    <label for="ecommerce-product-stock" class="form-label">Stock Quantity</label>
                                    <input
                                        type="number"
                                        class="form-control @error('stockQuantity') is-invalid @enderror"
                                        id="ecommerce-product-stock"
                                        placeholder="Quantity"
                                        name="stockQuantity"
                                        value="{{ old('stockQuantity') }}"
                                    />
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
                                    <input
                                        type="number"
                                        class="form-control @error('productPrice') is-invalid @enderror"
                                        id="ecommerce-product-price"
                                        placeholder="Price"
                                        name="productPrice"
                                        value="{{ old('productPrice') }}"
                                    />
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
                                <div class="mb-3">
                                    <label for="category-org" class="form-label">Category</label>
                                    <select
                                        id="category-org"
                                        name="category"
                                        class="form-select @error('category') is-invalid @enderror"
                                    >
                                        <option value="">Select Category</option>
                                        <option value="Household" {{ old('category') == 'Household' ? 'selected' : '' }}>Household</option>
                                        <option value="Management" {{ old('category') == 'Management' ? 'selected' : '' }}>Management</option>
                                        <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status-org" class="form-label">Status</label>
                                    <select
                                        id="status-org"
                                        name="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                    >
                                        <option value="Published" {{ old('status') == 'Published' ? 'selected' : '' }}>Published</option>
                                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
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

<!-- / Content -->





@endsection
