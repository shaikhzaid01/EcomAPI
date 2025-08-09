@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between mb-3">
  <h4>Products</h4>
  <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
</div>

<table class="table table-bordered">
  <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Images</th><th>Actions</th></tr></thead>
  <tbody>
    @forelse($products as $p)
      <tr>
        <td>{{ $p->id }}</td>
        <td>{{ $p->name }}</td>
        <td>{{ $p->price }}</td>
        <td>
          @foreach($p->images as $img)
            <img src="{{ asset('storage/'.$img->image_path) }}" width="60" class="me-1 mb-1">
          @endforeach
        </td>
        <td>
          <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-secondary">Edit</a>
          <form action="{{ route('admin.products.destroy', $p) }}" method="POST" style="display:inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5">No products yet.</td></tr>
    @endforelse
  </tbody>
</table>

{{ $products->links() }}
@endsection
