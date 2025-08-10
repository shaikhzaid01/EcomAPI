@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">✏ Edit Product</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            ⬅ Back to Products
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Product Name --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           value="{{ old('name', $product->name) }}"
                           class="form-control @error('name') is-invalid @enderror"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Price --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Price (₹) <span class="text-danger">*</span></label>
                    <input type="number" name="price" step="0.01"
                           value="{{ old('price', $product->price) }}"
                           class="form-control @error('price') is-invalid @enderror"
                           required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea id="summernote" name="description"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Upload More Images --}}
                <div class="mb-3">
                    <label class="form-label fw-bold" for="multipleImageInput">Upload More Images</label>
                    <input type="file" name="images[]" multiple
                           class="form-control @error('images') is-invalid @enderror"
                           id="multipleImageInput">
                    <small class="text-muted">You can select multiple images (JPG, PNG only).</small>
                    @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Existing Images with Delete Option --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Existing Images</label>
                    <div class="d-flex flex-wrap gap-3">
                        @forelse($product->images as $img)
                            <div class="text-center" style="width:90px;">
                                <img src="{{ asset('storage/'.$img->image_path) }}"
                                     alt="Product Image"
                                     class="img-thumbnail mb-1" width="80">
                                <div>
                                    <label class="small text-danger">
                                        <input type="checkbox" name="delete_images[]" value="{{ $img->id }}">
                                        Delete
                                    </label>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No images uploaded yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">💾 Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
