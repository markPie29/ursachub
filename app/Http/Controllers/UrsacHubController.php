<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\News;
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
    
    public function show_prodpage($id)
    {
        $product = Products::findOrFail($id);
        return view('show', compact('product'));
    }

    public function addproduct(Request $request)
    {
       
    }
    

    public function create()
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
}
