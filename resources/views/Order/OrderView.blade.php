@extends('Layouts.vuexy')

@section('title', 'Orders')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endpush

@section('content')
<div class="card">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total Orders</th>
                    <th>Total</th>
                    <th>Order Date</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('page-scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

<script>
$(function () {
    var dt_basic_table = $('.datatables-basic');

    if (dt_basic_table.length) {
        dt_basic_table.DataTable({
            ajax: {
                url: '{{ route("orders.json") }}',
                type: 'GET',
                error: function(xhr, error, code) {
                    console.log('AJAX Error:', xhr, error, code);
                    alert('Error loading data: ' + xhr.responseText);
                }
            },
            columns: [
                { data: 'id' },
                { data: 'customer' },
                { data: 'totalOrders', orderable: false, searchable: false },
                { data: 'total', render: $.fn.dataTable.render.number(',', '.', 2, '₱') },
                { data: 'order_date' },
                { data: 'action', orderable: false, searchable: false }
            ],
            dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end"B>>' +
                 '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>' +
                 't<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle me-2 waves-effect waves-light',
                    text: '<i class="ti ti-file-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: ['excel']
                },
                {
                    text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add Order</span>',
                    className: 'btn btn-primary waves-effect waves-light',
                    action: function () {
                        window.location.href = '{{ route("orders.create") }}';
                    }
                }
            ],
            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 10
        });

        $('div.head-label').html('<h5 class="card-title mb-0">Orders</h5>');
    }
});

// Delete order function
function deleteOrder(deleteUrl) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: false,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(deleteUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: '_method=DELETE'
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire('Deleted!', data.message, 'success');
                $('.datatables-basic').DataTable().ajax.reload(null, false);
            })
            .catch(err => {
                console.error('Delete error:', err);
                Swal.fire('Error', 'Something went wrong.', 'error');
            });
        }
    });
}
</script>
@endpush
