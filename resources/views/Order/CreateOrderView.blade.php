@extends('layouts.vuexy')

@section('title', isset($order) ? 'Edit Order' : 'Create Order')

@section('content')

    <div class="card">
        <h5 class="card-header">
            {{ isset($order) ? 'Edit Order' : 'Create New Order' }}
        </h5>
        <div class="card-body">
            <form  action="{{ isset($order) ? route('orders.update', $order->id) : route('orders.store') }}" 
                method="POST" id="orderForm">
                @csrf
                @if(isset($order))
                    @method('PUT')
                @endif

                <!-- Select Customer -->
                <div class="mb-3">
                    <label for="customer_id" class="form-label">Select Customer</label>
                    <select name="customer_id" id="customer_id" class="form-select" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" 
                                {{ isset($order) && $order->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->customer_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Order Items Table -->
                <div class="mb-3">
                    <h6>Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover align-middle" id="orderItemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 20%;">Product</th>
                                    <th style="width: 10%;">Qty</th>
                                    <th style="width: 10%;">Price</th>
                                    <th style="width: 10%;">Subtotal</th>
                                    <th style="width: 10%;">DownPayment</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 5%;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($order))
                                    @foreach($order->orderItems as $item)
                                        <tr class="order-item-row">
                                            <td>
                                                <select name="product_id[]" class="form-select form-select-sm product-select" required>
                                                    <option value="">Select Product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                                            {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                            {{ $product->product_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" name="quantity[]" class="form-control form-control-sm quantity" value="{{ $item->quantity }}"></td>
                                            <td><input type="number" name="price[]" class="form-control form-control-sm price" value="{{ $item->price }}" readonly></td>
                                            <td><input type="number" name="subtotal[]" class="form-control form-control-sm subtotal" value="{{ $item->subtotal }}" readonly></td>
                                            <td><input type="number" name="downpayment[]" class="form-control form-control-sm line-downpayment" value="{{ $item->downpayment }}"></td>
                                            <td>
                                                <select name="status[]" class="form-select form-select-sm line-status">
                                                    <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                    <option value="cancelled" {{ $item->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item remove-row" href="javascript:void(0);">
                                                            <i class="ti ti-trash me-1"></i> Remove
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="order-item-row">
                                        <td>
                                            <select name="product_id[]" class="form-select form-select-sm product-select" required>
                                                <option value="">Select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                        {{ $product->product_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="quantity[]" class="form-control form-control-sm quantity" value="1"></td>
                                        <td><input type="number" name="price[]" class="form-control form-control-sm price" readonly></td>
                                        <td><input type="number" name="subtotal[]" class="form-control form-control-sm subtotal" readonly></td>
                                        <td><input type="number" name="downpayment[]" class="form-control form-control-sm line-downpayment" value="0"></td>
                                        <td>
                                            <select name="status[]" class="form-select form-select-sm line-status">
                                                <option value="pending">Pending</option>
                                                <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item remove-row" href="javascript:void(0);">
                                                        <i class="ti ti-trash me-1"></i> Remove
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-primary" id="addRow">Add</button>
                    </div>
                </div>

                <!-- Totals -->
                <div class="row mb-3 mt-3">
                    <div class="col-md-4">
                        <label for="total" class="form-label">Total</label>
                        <input type="number" id="total" class="form-control" name="total" 
                            value="{{ isset($order) ? $order->total : '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="balance" class="form-label">Balance</label>
                        <input type="number" id="balance" class="form-control" name="balance" 
                            value="{{ isset($order) ? $order->balance : '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="order_date" class="form-label">Order Date</label>
                        <input type="date" id="order_date" class="form-control" 
                        name="order_date" required
                        value="{{ isset($order) ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') : '' }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ isset($order) ? 'Update Order' : 'Create Order' }}
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-label-secondary waves-effect">{{ isset($order) ? 'Cancel' : 'Discard' }}</a>
            </form>
        </div>
    </div>

@endsection

@push('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // --- Functions ---
        function updateSubtotal(row) {
            const qty = parseFloat(row.querySelector('.quantity').value) || 0;
            const price = parseFloat(row.querySelector('.price').value) || 0;
            row.querySelector('.subtotal').value = (qty * price).toFixed(2);
            updateTotals();
        }

        function updateTotals() {
            let total = 0;
            let totalDownpayment = 0;
            document.querySelectorAll('.order-item-row').forEach(row => {
                total += parseFloat(row.querySelector('.subtotal').value) || 0;
                totalDownpayment += parseFloat(row.querySelector('.line-downpayment').value) || 0;
            });
            document.getElementById('total').value = total.toFixed(2);
            document.getElementById('balance').value = (total - totalDownpayment).toFixed(2);
        }

        // Prevent duplicate products
        function updateProductOptions() {
            const selectedIds = Array.from(document.querySelectorAll('.product-select'))
                .map(select => select.value)
                .filter(val => val !== '');

            document.querySelectorAll('.product-select').forEach(select => {
                const currentVal = select.value;
                select.querySelectorAll('option').forEach(option => {
                    if(option.value === "" || option.value === currentVal) {
                        option.hidden = false;
                    } else {
                        option.hidden = selectedIds.includes(option.value);
                    }
                });
            });
        }

        // --- Event listeners ---
        document.querySelector('#orderItemsTable').addEventListener('input', function(e) {
            if(e.target.classList.contains('quantity') || e.target.classList.contains('line-downpayment')) {
                const row = e.target.closest('tr');
                updateSubtotal(row);
            }
        });

        document.querySelector('#orderItemsTable').addEventListener('change', function(e) {
            if(e.target.classList.contains('product-select')) {
                const row = e.target.closest('tr');
                const price = e.target.selectedOptions[0].dataset.price || 0;
                row.querySelector('.price').value = parseFloat(price).toFixed(2);
                updateSubtotal(row);
                updateProductOptions();
            }
        });

        // Remove row
        document.querySelector('#orderItemsTable').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.preventDefault();
                e.stopPropagation();
                e.target.closest('tr').remove();
                updateTotals();
                updateProductOptions();
            }
        });

        // Add row
        document.getElementById('addRow').addEventListener('click', function () {
            const table = document.querySelector('#orderItemsTable tbody');
            const firstRow = table.querySelector('tr');

            let newRow;
            if (firstRow) {
                newRow = firstRow.cloneNode(true);
                newRow.querySelectorAll('input').forEach(input => {
                    if (input.classList.contains('quantity')) {
                        input.value = 1;
                    } else if (input.classList.contains('line-downpayment')) {
                        input.value = 0;
                    } else {
                        input.value = '';
                    }
                });
                newRow.querySelector('.product-select').selectedIndex = 0;
            } else {
                // If no rows exist
                newRow = document.createElement('tr');
                newRow.classList.add('order-item-row');
                newRow.innerHTML = `
                    <td>
                        <select name="product_id[]" class="form-select form-select-sm product-select" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                    {{ $product->product_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="quantity[]" class="form-control form-control-sm quantity" value="1"></td>
                    <td><input type="number" name="price[]" class="form-control form-control-sm price" readonly></td>
                    <td><input type="number" name="subtotal[]" class="form-control form-control-sm subtotal" readonly></td>
                    <td><input type="number" name="downpayment[]" class="form-control form-control-sm line-downpayment" value="0"></td>
                    <td>
                        <select name="status[]" class="form-select form-select-sm line-status">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item remove-row" href="javascript:void(0);">
                                    <i class="ti ti-trash me-1"></i> Remove
                                </a>
                            </div>
                        </div>
                    </td>
                `;
            }

            table.appendChild(newRow);
            updateProductOptions();
            updateTotals();
        });

        // Initial calls
        updateProductOptions();
        updateTotals();
    });
</script>
@endpush
