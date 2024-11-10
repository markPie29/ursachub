<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// use App\User;

class UrsacHubController extends Controller
{

    public function home() {
        return view ('home');
    }
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

        $products = Products::paginate(6);

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
    // Validate input fields
    $request->validate([
        'name' => 'required|string|max:255',
        'small' => 'required|integer|min:0',
        'medium' => 'required|integer|min:0',
        'large' => 'required|integer|min:0',
        'extralarge' => 'required|integer|min:0',
        'double_extralarge' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'photos' => 'nullable|array|max:5',
        'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Retrieve the organization from the authenticated admin
    $admin = Auth::guard('admin')->user();
    $org = $admin->org;

    // Create a new product instance
    $product = new Products();
    $product->org = $org; // Set the organization based on the admin's org
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

    // Redirect to the admin account route
    return redirect()->route('admin.account')->with('success', 'Product added successfully.');
}

public function addprodpage()
{
    // Retrieve the authenticated admin's organization
    $admin = Auth::guard('admin')->user();
    $org = $admin->org;
    return view('admin_addprod', compact('org'));
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

    public function delete_news($id)
    {
        // Find the product by ID
        $news = News::findOrFail($id);

        // Optionally, delete associated photos from storage
        if ($news->photos) {
            $photos = json_decode($news->photos);
            foreach ($photos as $photoPath) {
                if (Storage::exists($photoPath)) {
                    Storage::delete($photoPath);
                }
            }
        }

        // Delete the product from the database
        $news->delete();

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

    public function editNews(Request $request, $id)
    {
        $news = News::findOrFail($id);

        // Update text fields
        $news->headline = $request->input('headline');
        $news->org = $request->input('org');
        $news->content = $request->input('content');

        // Get existing photos
        $existingPhotos = json_decode($news->photos, true) ?? [];

        // Debug: Log existing photos
        \Log::info('Existing Photos: ', $existingPhotos);

        // Handle removing selected photos
        if ($request->has('remove_photos')) {
            foreach ($request->input('remove_photos') as $photoToRemove) {
                // Check if the photo exists before trying to delete it
                if (in_array($photoToRemove, $existingPhotos)) {
                    // Remove the photo from storage
                    \Storage::disk('public')->delete($photoToRemove);
                    // Remove the photo from the existing array
                    if (($key = array_search($photoToRemove, $existingPhotos)) !== false) {
                        unset($existingPhotos[$key]);
                    }
                } else {
                    // Debug: Log if the photo to remove was not found
                    \Log::warning('Photo not found for removal: ', [$photoToRemove]);
                }
            }
        }

        // Handle new photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                if ($photo->isValid()) {
                    $existingPhotos[] = $photo->store('news_photos', 'public');
                }
            }
        }

        // Limit the number of photos to 5
        $existingPhotos = array_slice($existingPhotos, 0, 5);
        $news->photos = json_encode($existingPhotos);

        // Save the updated news
        $news->save();
        return redirect('/');
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

    public function student_account()
    {
        $student = Auth::guard('student')->user();
        $firstname = $student->first_name;
        $lastname = $student->last_name;
        $middlename = $student->middle_name;
        $student_id = $student->student_id;

        return view('student_account', [
            'firstname' => $firstname, 
            'lastname' => $lastname, 
            'middlename' => $middlename, 
            'student_id'=> $student_id
        ]);
    }
}
