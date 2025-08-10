@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">🛒 User Carts</h1>

    @forelse($carts as $userId => $items)
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>User:</strong> {{ $items->first()->user->name }}
                        <small class="d-block text-light">{{ $items->first()->user->email }}</small>
                    </div>
                    <span class="badge bg-light text-dark">
                        {{ $items->count() }} Item(s)
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Price</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $cart)
                            <tr>
                                <td>{{ $cart->product->name }}</td>
                                <td class="text-end">₹{{ number_format($cart->product->price, 2) }}</td>
                                <td class="text-center">{{ $cart->quantity }}</td>
                                <td class="text-end">₹{{ number_format($cart->product->price * $cart->quantity, 2) }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.cart.destroy', $cart) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this item?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            No carts found.
        </div>
    @endforelse
</div>
@endsection
