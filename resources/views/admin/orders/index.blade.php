@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Orders</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#ID</th>
                <th>User</th>
                <th>Total</th>
                <th>Status</th>
                <th>Placed At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                    <td>₹{{ number_format($order->total, 2) }}</td>
                    <td>
    <span class="badge bg-{{ $order->status == 'pending' ? 'secondary' : ($order->status == 'processing' ? 'warning' : ($order->status == 'completed' ? 'success' : 'danger')) }}">
        {{ ucfirst($order->status) }}
    </span>
</td>

                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">View</a>

                     {{-- Status update buttons --}}
    <form action="{{ route('admin.orders.update', $order) }}" method="POST" style="display:inline">
        @csrf @method('PUT')
        <input type="hidden" name="status" value="processing">
        <button class="btn btn-sm btn-warning mb-1" {{ $order->status == 'processing' ? 'disabled' : '' }}>Processing</button>
    </form>

    <form action="{{ route('admin.orders.update', $order) }}" method="POST" style="display:inline">
        @csrf @method('PUT')
        <input type="hidden" name="status" value="completed">
        <button class="btn btn-sm btn-success mb-1" {{ $order->status == 'completed' ? 'disabled' : '' }}>Completed</button>
    </form>

    <form action="{{ route('admin.orders.update', $order) }}" method="POST" style="display:inline">
        @csrf @method('PUT')
        <input type="hidden" name="status" value="cancelled">
        <button class="btn btn-sm btn-danger mb-1" {{ $order->status == 'cancelled' ? 'disabled' : '' }}>Cancel</button>
    </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $orders->links('pagination::bootstrap-5') }}

</div>
@endsection
