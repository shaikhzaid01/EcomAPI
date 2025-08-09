@extends('layouts.admin')

@section('content')
<h4>Edit Product</h4>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
  @csrf @method('PUT')
  <div class="mb-3">
    <label>Name</label>
    <input name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
  </div>

  <div class="mb-3">
    <label>Price</label>
    <input name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
  </div>

  <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
  </div>

  <div class="mb-3">
    <label>Upload more Images</label>
    <input type="file" name="images[]" multiple class="form-control">
  </div>

  <div class="mb-3">
    <label>Existing Images</label><br>
    @foreach($product->images as $img)
    <div style="display: inline-block; margin: 10px; text-align: center;">
      <img src="{{ asset('storage/'.$img->image_path) }}" width="80" class="me-1 mb-1">
        <!-- Checkbox to delete image -->
            <label>
                <input type="checkbox" name="delete_images[]" value="{{ $img->id }}">
                Delete
            </label>
       </div>
    @endforeach


  </div>

  <button class="btn btn-primary">Update</button>
</form>
@endsection
