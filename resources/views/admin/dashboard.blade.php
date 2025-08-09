@extends('layouts.admin')

@section('content')
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $productsCount }}</h3>
        <p>Products</p>
      </div>
      <div class="icon"><i class="fas fa-box"></i></div>
      <a href="{{ route('admin.products.index') }}" class="small-box-footer">Manage Products <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ $cartCount }}</h3>
        <p>Cart Items</p>
      </div>
      <div class="icon"><i class="fas fa-shopping-cart"></i></div>
      <a href="{{ route('admin.cart.index') }}" class="small-box-footer">View Carts <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $ordersCount }}</h3>
        <p>Orders</p>
      </div>
      <div class="icon"><i class="fas fa-file-invoice"></i></div>
      <a href="{{ route('admin.orders.index') }}" class="small-box-footer">View Orders <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>
@endsection
