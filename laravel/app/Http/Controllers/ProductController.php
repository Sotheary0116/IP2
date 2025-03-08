<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //
    //get /api/product
    public function getProducts(){
        $products = Product::with('category')->get();
        return response()->json($products);
    }

    //post/api/product
    public function createProduct(Request $request){
    
        // Process images if provided
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }
    
        // Create the product
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id, // Ensure category_id is valid
            'pricing' => $request->pricing,
            'description' => $request->description,
            'images' => ($imagePaths)
        ]);
    
            return response()->json(['message' => 'Creating a new product', 'product' => $product], 201);
    }

    //get/api/categories/{ProductId}
    public function getProduct($productId)
    {
        $product = Product::with('category')->find($productId);
        return response()->json($product);
    }
    //get/api/categories/{ProductId}
    public function updateProduct(Request $request, $productId){
        $product = Product::find($productId);

        $product->update($request->all());

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->fresh()
        ]);
    }

    //delete/api/categories/{ProductId}
    public function deleteProduct($productId)
    {
        $product = Product::find($productId);

        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    
    }
}
