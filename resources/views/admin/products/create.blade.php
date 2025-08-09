@extends('layouts.admin')

@section('content')
<h4>Add Product</h4>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="mb-3">
    <label>Name</label>
    <input name="name" class="form-control" value="{{ old('name') }}" required>
  </div>

  <div class="mb-3">
    <label>Price</label>
    <input name="price" class="form-control" value="{{ old('price') }}" required>
  </div>

  <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
  </div>

  <div class="mb-3">
    <label>Images (multiple)</label>
    <input type="file" name="images[]" multiple class="form-control">
  </div>

  <button class="btn btn-success">Save</button>
</form>
@endsection
