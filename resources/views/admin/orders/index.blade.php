@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">📦 Orders</h1>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#ID</th>
                        <th>User</th>
                        <th class="text-end">Total</th>
                        <th>Status</th>
                        <th>Placed At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                <strong>{{ $order->user->name ?? 'Guest' }}</strong>
                                @if($order->user)
                                    <div class="small text-muted">{{ $order->user->email }}</div>
                                @endif
                            </td>
                            <td class="text-end">₹{{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status == 'pending' ? 'secondary' : ($order->status == 'processing' ? 'warning' : ($order->status == 'completed' ? 'success' : 'danger')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="btn btn-sm btn-info me-1">
                                    <i class="bi bi-eye"></i> View
                                </a>

                                {{-- Status update buttons --}}
                                <form action="{{ route('admin.orders.update', $order) }}"
                                      method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="processing">
                                    <button class="btn btn-sm btn-warning mb-1"
                                            {{ $order->status == 'processing' ? 'disabled' : '' }}>
                                        Processing
                                    </button>
                                </form>

                                <form action="{{ route('admin.orders.update', $order) }}"
                                      method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="completed">
                                    <button class="btn btn-sm btn-success mb-1"
                                            {{ $order->status == 'completed' ? 'disabled' : '' }}>
                                        Completed
                                    </button>
                                </form>

                                <form action="{{ route('admin.orders.update', $order) }}"
                                      method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button class="btn btn-sm btn-danger mb-1"
                                            {{ $order->status == 'cancelled' ? 'disabled' : '' }}>
                                        Cancel
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
