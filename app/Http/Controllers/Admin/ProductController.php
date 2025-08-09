<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{



    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $products = Product::with('images')->orderBy('created_at','desc')->paginate(12);
        return view('admin.products.index', compact("products"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=> 'required| string|max:255',
            'price'=> 'required|numeric',
            'description'=> 'nullable|string',
            'images.*' => 'image| max:5120'
        ]);
        $product = Product::create($data);
        if ($request->hasFile('images')){
            foreach($request->file('images') as $img){
                $path = $img->store('products','public');
                ProductImage::create([
                    'product_id'=> $product->id,
                    'image_path' => $path
                ]);
            }
        }
        return redirect()->route('admin.products.index')->with('success','Product Created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Product $product)
    {
 $product->load('images');
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
           $data = $request->validate([
            'name'=> 'required| string|max:255',
            'price'=> 'required|numeric',
            'description'=> 'nullable|string',
            'images.*' => 'image| max:5120',
                   'delete_images' => 'array',
        'delete_images.*' => 'integer|exists:product_images,id',
        ]);
        $product->update($data);
            // Delete selected images
    if ($request->filled('delete_images')) {
        $imagesToDelete = ProductImage::whereIn('id', $request->delete_images)
            ->where('product_id', $product->id)
            ->get();

        foreach ($imagesToDelete as $img) {

            Storage::disk('public')->delete($img->image_path); // delete file
            $img->delete(); // delete DB record
        }
    }

         if ($request->hasFile('images')){
            foreach($request->file('images') as $img){
                $path = $img->store('products','public');
                ProductImage::create([
                    'product_id'=> $product->id,
                    'image_path' => $path
                ]);
            }
        }
        return redirect()->route('admin.products.index')->with('success','Product Updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
          return redirect()->route('admin.products.index')->with('success','Product Deleted.');
    }
}
