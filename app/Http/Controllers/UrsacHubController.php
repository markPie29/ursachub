<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
// use App\User;

class UrsacHubController extends Controller
{
    public function admin() {

        // $user = User::all();
        $products = Products::all();

        return view('admin_account', [
            'org_name' => request('org_name'),
            'org_name_full' => request('org_name_full'),
            'products' => $products
        ]);
    }


    public function products_page() {

        $products = Products::all();

        return view('products_page', [
            'products' => $products
        ]);
    }
    
    public function show_prodpage($id)
    {
        $product = Products::findOrFail($id);
        return view('show', compact('product'));
    }

    public function addproduct(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'org' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'sizes' => 'required|array|min:1', // Ensure at least one size is selected
            'sizes.*' => 'string|in:XS,S,M,L,XL,2XL,3XL', // Ensure each size is valid
            'stocks' => 'required|array', // Stocks should be an array
            'stocks.*' => 'integer|min:0', // Each stock should be a non-negative integer
            'price' => 'required|numeric|min:0', // Ensure price is non-negative
            'photos' => 'array|max:5', // Validate that a maximum of 5 photos can be uploaded
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048', // Validate each photo file
        ]);
    
        // Process each size and its corresponding stock
        foreach ($request->sizes as $size) {
            $stock = $request->stocks[$size] ?? 0; // Get the stock for the checked size or default to 0
    
            // Save the product with the respective size and stock in the database
            \App\Models\Products::create([
                'org' => $request->org,
                'name' => $request->name,
                'size' => $size,
                'stocks' => $stock,
                'price' => $request->price,
                // Handle photos as needed (store them and save their paths)
            ]);
        }
    
        // return redirect()->route('/add')->with('success', 'Products added successfully!');
    }
    

    public function create()
    {
        return view('admin_addprod'); 
    }
}
