@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">➕ Add New Product</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            ⬅ Back to Products
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Product Name --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Price --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Price (₹) <span class="text-danger">*</span></label>
                    <input type="number" name="price" step="0.01" value="{{ old('price') }}"
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
                              class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Multiple Images --}}
                <div class="mb-3">
                    <label class="form-label fw-bold" for="multipleImageInput">Product Images</label>
                    <input type="file" name="images[]" multiple
                           class="form-control @error('images') is-invalid @enderror"
                           id="multipleImageInput">
                    <small class="text-muted">You can select multiple images (JPG, PNG only).</small>
                    @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4">💾 Save Product</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
