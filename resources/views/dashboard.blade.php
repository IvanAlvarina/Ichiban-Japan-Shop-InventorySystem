@extends('layouts.vuexy')

@section('title', 'Dashboard')

@section('content')

@push('page-styles')

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />

@endpush

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">

    <!-- Stats -->
    <div class="col-xl-12 mb-4 col-lg-7 col-12">
      <div class="card h-100">
        <div class="card-header">
          <div class="d-flex justify-content-between mb-3">
            <h5 class="card-title mb-0">Statistics</h5>
            <small class="text-muted">Updated {{ \Carbon\Carbon::now()->diffForHumans() }}</small>
          </div>
        </div>
        <div class="card-body">
          <div class="row gy-3">
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-primary me-3 p-2">
                  <i class="ti ti-chart-pie-2 ti-sm"></i>
                </div>
                <div class="card-info">
                  <h5 class="mb-0">₱{{ number_format($totalSales, 2) }}</h5>
                  <small>Total Sales</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-info me-3 p-2">
                  <i class="ti ti-users ti-sm"></i>
                </div>
                <div class="card-info">
                  <h5 class="mb-0">{{ $totalCustomers }}</h5>
                  <small>{{ $totalCustomers == 1 ? 'Customer' : 'Customers' }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-danger me-3 p-2">
                  <i class="ti ti-shopping-cart ti-sm"></i>
                </div>
                <div class="card-info">
                  <h5 class="mb-0">{{ $totalProducts }}</h5>
                  <small>{{ $totalProducts == 1 ? 'Product' : 'Products' }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-success me-3 p-2">
                  <i class="ti ti-wallet"></i>
                </div>
                <div class="card-info">
                  <h5 class="mb-0">₱{{ number_format($totalRevenue, 2) }}</h5>
                  <small>Revenue</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection


