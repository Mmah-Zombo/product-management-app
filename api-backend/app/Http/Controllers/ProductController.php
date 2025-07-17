<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $products = Product::where('user_id', '=', $user_id)->get();

        return response()->json([
            'status' => true,
            'products' => $products
        ]);

    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $validatedData['user_id'] = auth()->user()->id;

        if($request->hasFile('banner_image')) {
            $validatedData['banner_image'] = $request->file('banner_iamge')->store('products', 'public');
        }

        Product::create($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully'
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return response()->json([
            'status' => true,
            'message' => 'Product data found',
            'products' => $product
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('banner_image')) {
            if ($product->banner_image) {
                Storage::disk('public')->delete($product->banner_image);
            }

            $validatedData['banner_image'] = $request->file('banner_iamge')->store('products', 'public');
        }

        $product->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Product data updated successfully'
        ]);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}
