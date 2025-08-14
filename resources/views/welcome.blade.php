@extends('layouts.vuexy')

@section('title', 'Dashboard')

@section('content')
  <h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Home /</span> Dashboard
  </h4>

  {{-- Example card from Vuexy HTML (paste your own content below) --}}
  <div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Example</h5>
        </div>
        <div class="card-body">
          Hello, this is an example card in your dashboard. You can replace this with your own content.
        </div>
      </div>
    </div>
  </div>
@endsection

