<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\News;
use Illuminate\Support\Facades\Storage;
// use App\User;

class UrsacHubController extends Controller
{
    public function admin() {

        // $user = User::all();
        $products = Products::all();
        $news = News::all();

        return view('admin_account', [
            'org_name' => request('org_name'),
            'org_name_full' => request('org_name_full'),
            'products' => $products,
            'news' => $news
        ]);
    }


    public function products_page() {

        $products = Products::all();

        return view('products_page', [
            'products' => $products
        ]);
    }

    public function news_page() {

        $news = News::all();

        return view('news_page', [
            'news' => $news
        ]);
    }
    
    public function show_eachprodpage($id)
    {
        $product = Products::findOrFail($id);
        return view('show_eachprod', compact('product'));
    }

    public function show_eachprodpage_admin($id)
    {
        $product = Products::findOrFail($id);
        return view('show_eachprod_admin', compact('product'));
    }

    public function show_eachnewspage($id)
    {
        $news = News::findOrFail($id);
        return view('show_eachnews', compact('news'));
    }

    public function show_eachnewspage_admin($id)
    {
        $news = News::findOrFail($id);
        return view('show_eachnews_admin', compact('news'));
    }

    public function addprod(Request $request)
    {
       $product = new Products();

        $product->org = $request->input('org');
        $product->name = $request->input('name');
        $product->small = $request->input('small');
        $product->medium = $request->input('medium');
        $product->large = $request->input('large');
        $product->extralarge = $request->input('extralarge');
        $product->double_extralarge = $request->input('double_extralarge');
        $product->price = $request->input('price');

        $photoPaths = [];

        // Handle multiple file uploads, max 5 images
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            foreach ($photos as $index => $photo) {
                if ($index >= 5) break; // Limit to 5 images
    
                if ($photo->isValid()) {
                    $photoPaths[] = $photo->store('product_photos', 'public'); // Add each path to array
                }
            }
        }
    
        // Store JSON-encoded photo paths in the photos column
        $product->photos = json_encode($photoPaths);
        $product->save();
    
        return redirect('/');
    }

    public function delete_prod($id)
    {
        // Find the product by ID
        $product = Products::findOrFail($id);

        // Optionally, delete associated photos from storage
        if ($product->photos) {
            $photos = json_decode($product->photos);
            foreach ($photos as $photoPath) {
                if (Storage::exists($photoPath)) {
                    Storage::delete($photoPath);
                }
            }
        }

        // Delete the product from the database
        $product->delete();

        // Redirect with a success message
        return redirect('/');
    }

    public function editStock(Request $request, $id, $size)
    {
        $product = Products::findOrFail($id);
        
        // Validate the input
        $request->validate([
            $size => 'required|integer|min:0',
        ]);
        
        // Update the corresponding size stock
        $product->$size = $request->input($size);
        $product->save();

        return redirect()->back()->with('success', 'Stock updated successfully.');
    }
    

    public function addprodpage()
    {
        return view('admin_addprod'); 
    }

    public function addnews(Request $request)
    {
        $news = new News();
    
        // Set other fields
        $news->org = $request->input('org');
        $news->headline = $request->input('headline');
        $news->content = $request->input('content');
    
        // Array to store each photo path
        $photoPaths = [];
    
        // Handle multiple file uploads, max 5 images
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            foreach ($photos as $index => $photo) {
                if ($index >= 5) break; // Limit to 5 images
    
                if ($photo->isValid()) {
                    $photoPaths[] = $photo->store('news_photos', 'public'); // Add each path to array
                }
            }
        }
    
        // Store JSON-encoded photo paths in the photos column
        $news->photos = json_encode($photoPaths);
        $news->save();
    
        return redirect('/');
    }

    public function addnewspage()
    {
        return view('admin_addnews'); 
    }
}
