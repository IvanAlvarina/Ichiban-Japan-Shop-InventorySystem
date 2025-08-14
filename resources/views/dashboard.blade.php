@extends('layouts.vuexy')

@section('title', 'Dashboard')

@section('content')
 <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row">

        <!-- Statistics -->
        <div class="col-xl-12 mb-4 col-lg-7 col-12">
          <div class="card h-100">
            <div class="card-header">
              <div class="d-flex justify-content-between mb-3">
                <h5 class="card-title mb-0">Statistics</h5>
                <small class="text-muted">Updated 1 month ago</small>
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
                      <h5 class="mb-0">230k</h5>
                      <small>Sales</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-info me-3 p-2">
                      <i class="ti ti-users ti-sm"></i>
                    </div>
                    <div class="card-info">
                      <h5 class="mb-0">8.549k</h5>
                      <small>Customers</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-danger me-3 p-2">
                      <i class="ti ti-shopping-cart ti-sm"></i>
                    </div>
                    <div class="card-info">
                      <h5 class="mb-0">1.423k</h5>
                      <small>Products</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-success me-3 p-2">
                      <i class="ti ti-currency-dollar ti-sm"></i>
                    </div>
                    <div class="card-info">
                      <h5 class="mb-0">$9745</h5>
                      <small>Revenue</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--/ Statistics -->

        <!-- Popular Product -->
        <div class="col-md-6 col-xl-4 mb-4">
          <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
              <div class="card-title m-0 me-2">
                <h5 class="m-0 me-2">Popular Products</h5>
                <small class="text-muted">Total 10.4k Visitors</small>
              </div>
              <div class="dropdown">
                <button
                  class="btn p-0"
                  type="button"
                  id="popularProduct"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false">
                  <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="popularProduct">
                  <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                  <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                  <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <ul class="p-0 m-0">
                <li class="d-flex mb-4 pb-1">
                  <div class="me-3">
                    <img src="../../assets/img/products/iphone.png" alt="User" class="rounded" width="46" />
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Apple iPhone 13</h6>
                      <small class="text-muted d-block">Item: #FXZ-4567</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                      <p class="mb-0 fw-medium">$999.29</p>
                    </div>
                  </div>
                </li>
                <li class="d-flex mb-4 pb-1">
                  <div class="me-3">
                    <img
                      src="../../assets/img/products/nike-air-jordan.png"
                      alt="User"
                      class="rounded"
                      width="46" />
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Nike Air Jordan</h6>
                      <small class="text-muted d-block">Item: #FXZ-3456</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                      <p class="mb-0 fw-medium">$72.40</p>
                    </div>
                  </div>
                </li>
                <li class="d-flex mb-4 pb-1">
                  <div class="me-3">
                    <img src="../../assets/img/products/headphones.png" alt="User" class="rounded" width="46" />
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Beats Studio 2</h6>
                      <small class="text-muted d-block">Item: #FXZ-9485</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                      <p class="mb-0 fw-medium">$99</p>
                    </div>
                  </div>
                </li>
                <li class="d-flex mb-4 pb-1">
                  <div class="me-3">
                    <img
                      src="../../assets/img/products/apple-watch.png"
                      alt="User"
                      class="rounded"
                      width="46" />
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Apple Watch Series 7</h6>
                      <small class="text-muted d-block">Item: #FXZ-2345</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                      <p class="mb-0 fw-medium">$249.99</p>
                    </div>
                  </div>
                </li>
                <li class="d-flex mb-4 pb-1">
                  <div class="me-3">
                    <img
                      src="../../assets/img/products/amazon-echo.png"
                      alt="User"
                      class="rounded"
                      width="46" />
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Amazon Echo Dot</h6>
                      <small class="text-muted d-block">Item: #FXZ-8959</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                      <p class="mb-0 fw-medium">$79.40</p>
                    </div>
                  </div>
                </li>
                <li class="d-flex">
                  <div class="me-3">
                    <img
                      src="../../assets/img/products/play-station.png"
                      alt="User"
                      class="rounded"
                      width="46" />
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Play Station Console</h6>
                      <small class="text-muted d-block">Item: #FXZ-7892</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                      <p class="mb-0 fw-medium">$129.48</p>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!--/ Popular Product -->

      </div>
 </div>
@endsection

