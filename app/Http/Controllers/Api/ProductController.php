<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('images')->get();
            return response()->json([
                'status' => true,
                'message' => 'Products fetched successfully',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Somthing went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'description' => 'nullable|string',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Product create
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description
            ]);

            // Multiple images upload
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path
                    ]);
                }
            }


            return response()->json([
                'status' => true,
                'message' => 'Product created successfully',
                'data' => $product->load('images')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

public function show($id)
{
    try {
        $product = Product::with('images')->findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Product fetched successfully',
            'data' => $product
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Product not found',
            'error' => $e->getMessage()
        ], 404);
    }
}
public function update(Request $request, $id)
{
    try {
        $product = Product::findOrFail($id);

        // Validate input (you can extend validation as needed)
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer'
        ]);

        // Update product fields if present
        if ($request->has('name')) $product->name = $request->name;
        if ($request->has('price')) $product->price = $request->price;
        if ($request->has('description')) $product->description = $request->description;

        $product->save();

        // Delete checked images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = $product->images()->where('id', $imageId)->first();
                if ($image) {
                    // Delete image file from storage

                    Storage::delete('public/' . $image->image_path);
                    // Delete image record
                    $image->delete();
                }
            }
        }

        // Add new images if any
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('products', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product->load('images')
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Update failed',
            'error' => $e->getMessage()
        ], 500);
    }
}
public function destroy($id)
{
    try {
        $product = Product::findOrFail($id);

        // Delete all product images from storage and DB
        foreach ($product->images as $image) {
            Storage::delete('public/' . $image->image_path);
            $image->delete();
        }

        // Delete product itself
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to delete product',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
