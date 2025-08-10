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
    <textarea id="summernote" name="description" class="form-control">{{ old('description') }}</textarea>
  </div>

  {{-- <div class="mb-3">
    <label>Images (multiple)</label>
    <input type="file" name="images[]" multiple class="form-control">
  </div> --}}
  <div class="form-group">
                    <label for="multipleImageInput">Images (multiple)</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file"  name="images[]"  multiple class="custom-file-input" id="multipleImageInput">
                        <label class="custom-file-label" for="multipleImageInput">Choose file</label>
                      </div>
                      {{-- <div class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div> --}}
                    </div>
                  </div>

  <button class="btn btn-success">Save</button>
</form>
@endsection
