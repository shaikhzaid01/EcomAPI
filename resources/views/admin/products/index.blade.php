@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">📦 Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            ➕ Add Product
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th class="text-end">Price</th>
                        <th>Images</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->name }}</td>
                            <td class="text-end">₹{{ number_format($p->price, 2) }}</td>
                            <td>
                                @if($p->images->count() > 0)
                                    @foreach($p->images as $img)
                                        <img src="{{ asset('storage/'.$img->image_path) }}"
                                             alt="Product Image"
                                             class="rounded border me-1 mb-1"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @endforeach
                                @else
                                    <span class="text-muted">No images</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.products.edit', $p) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    ✏ Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $p) }}"
                                      method="POST"
                                      style="display:inline"
                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        🗑 Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
