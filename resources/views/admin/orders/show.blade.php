@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Order #{{ $order->id }} Details</h1>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>User:</strong> {{ $order->user->name ?? 'Guest' }}</p>
            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Total:</strong> ₹{{ number_format($order->total, 2) }}</p>
            <p><strong>Placed At:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>

    <h4>Items</h4>
    <table class="table table-bordered">
        <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->price,2) }}</td>
                    <td>₹{{ number_format($item->price * $item->quantity,2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">Back to Orders</a>
</div>
@endsection
