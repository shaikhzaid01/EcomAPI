@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">📄 Order #{{ $order->id }} Details</h1>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong>User:</strong> {{ $order->user->name ?? 'Guest' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                    <p class="mb-1">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $order->status == 'pending' ? 'secondary' : ($order->status == 'processing' ? 'warning' : ($order->status == 'completed' ? 'success' : 'danger')) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Total:</strong> ₹{{ number_format($order->total, 2) }}</p>
                    <p class="mb-1"><strong>Placed At:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                    <p class="mb-0"><strong>Last Updated:</strong> {{ $order->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3">🛒 Ordered Items</h4>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                            <td class="text-end">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">₹{{ number_format($order->total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            ← Back to Orders
        </a>
    </div>
</div>
@endsection
