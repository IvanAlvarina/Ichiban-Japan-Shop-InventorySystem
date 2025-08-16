@extends('Layouts.vuexy')

@section('title', 'Add Customer')

@section('content')

@push('page-styles')

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}" />

@endpush


<div class="row">
    <!-- FormValidation -->
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">{{ !empty($customer->id) ? 'Update Customer' : 'Add Customer' }}</h5>
                <div class="card-body">
                    <form id="formValidationExamples" action="{{  !empty($customer->id) ? route('customers.update', $customer->id) : route('customers.store') }}" method="POST" class="row g-3">
                        <!-- Account Details -->
                        
                        @csrf
                        @if(isset($customer->id))
                            @method('PUT')
                        @endif
                        <div class="col-12">
                            <h6>1. Account Details</h6>
                            <hr class="mt-0" />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="fullname">Name</label>
                            <input
                            type="text"
                            id="fullname"
                            class="form-control"
                            placeholder="John Doe"
                            name="fullname"
                            value="{{ $customer->customer_name ?? '' }}" />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="email">Email</label>
                            <input
                            class="form-control"
                            type="email"
                            id="email"
                            name="email"
                            placeholder="john.doe"
                            value="{{ $customer->email ?? '' }}" />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input
                            type="text"
                            id="phone"
                            class="form-control"
                            placeholder="+1234567890"
                            name="phone"
                            value="{{ $customer->phone ?? '' }}" />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="address">Address</label>
                            <input
                            type="text"
                            id="address"
                            class="form-control"
                            placeholder="123 Main St, City, Country"
                            name="address" 
                            value="{{ $customer->address ?? '' }}"/>
                        </div>


                        <div class="col-12">
                            <button type="submit" name="submitButton" class="btn btn-primary">{{ !empty($customer->id) ? 'Update' : 'Submit' }}</button>
                            <a href="{{ route('customers.index') }}" class="btn btn-label-secondary waves-effect">Discard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- /FormValidation -->
</div>




@endsection

@push('page-scripts')

 <script src="{{ asset('assets/js/form-validation.js') }}"></script>

@endpush