@extends('layouts.vuexy')

@section('title', 'Dashboard')

@section('content')

@push('page-styles')

<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

@endpush

@section('title', 'Dashboard')

@section('content')

@push('page-styles')

<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

@endpush

<div class="card">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>email</th>
                    <th>Phone</th>
                    <th>Address</th>
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
                    url: '{{ route("customers.json") }}',
                    type: 'GET',
                    error: function(xhr, error, code) {
                        console.log('AJAX Error:', xhr, error, code);
                        alert('Error loading data: ' + xhr.responseText);
                    }
                },
                columns: [
                    { data: 'customer_name' },
                    { data: 'email' },
                    { data: 'phone' },
                    { data: 'address' },
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
                        text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add customer</span>',
                        className: 'btn btn-primary waves-effect waves-light',
                        action: function () {
                            window.location.href = '{{ route("customers.create") }}';
                        }
                    }
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 10
            });

            $('div.head-label').html('<h5 class="card-title mb-0">Customers</h5>');
        }
    });

    // Delete customer function
    function deleteCustomer(deleteUrl) {
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
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
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

@endsection

@push('page-scripts')

@endpush