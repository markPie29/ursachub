<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\News;
use App\Models\Courses;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// use App\User;

class UrsacHubController extends Controller
{

    public function home() {
        return view ('home');
    }
    public function admin()
    {
        // Check if the user is authenticated as an admin
        if (Auth::guard('admin')->check()) {
            // Get the authenticated admin user
            $admin = Auth::guard('admin')->user();
            $org = $admin->org; // Assuming 'org' is a field in the admin table
            
            // Fetch data based on the authenticated admin's organization
            $products = Products::where('org', $org)->get(); 
            $news = News::where('org', $org)->get();
    
            // Pass the organization name, products, and news to the view
            return view('admin_account', [
                'org_name' => $org,
                'org_name_full' => $admin->org_name_full ?? null, // Adjust if the full name field is different
                'products' => $products,
                'news' => $news
            ]);
        }
    
        // Redirect to the admin login page if not authenticated
        return redirect()->route('admin.login')->with('error', 'You must be logged in to access this page.');
    }
    


    public function products_page() {

        $products = Products::paginate(6);

        return view('products_page', [
            'products' => $products
        ]);
    }

    public function news_page() {

        $news = News::paginate(8);

        return view('news_page', [
            'news' => $news
        ]);
    }
    
    public function show_eachprodpage($id)
    {
        $student = Auth::guard('student')->user();
        $product = Products::findOrFail($id);
    
        // Check if the student's course is in the list of courses that the product is related to
        $canAddToCart = $product->courses->contains($student->course_id);
    
        return view('show_eachprod', compact('product', 'canAddToCart'));
    }
    


    public function show_eachprodpage_admin($id)
    {
        $product = Products::findOrFail($id);
        $courses = Courses::all();
        return view('show_eachprod_admin', compact('product', 'courses'));
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
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480', // 20MB per file
        ]);
    
        // Check total size of uploaded photos
        if ($request->hasFile('photos')) {
            $totalSize = 0;
            foreach ($request->file('photos') as $photo) {
                $totalSize += $photo->getSize(); // Get size in bytes
            }
            
            // Convert bytes to MB and check if it exceeds 20MB
            if ($totalSize > 20 * 1024 * 1024) {
                return back()->withErrors([
                    'photos' => 'The total size of all uploaded photos must not exceed 20MB.',
                ])->withInput();
            }
        }
    
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
    
        // Handle multiple file uploads, max 5 images
        $photoPaths = [];
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
    
        // Save the product first to generate product_id
        $product->save();
    
        // Attach the selected courses to the product (only after the product is saved)
        if ($request->has('course_ids')) {
            $product->courses()->attach($request->course_ids); // Attach selected courses
        }
    
        // Redirect to the admin account route
        return redirect()->route('admin.account')->with('success', 'Product added successfully.');
    }
    
    


    public function addprodpage()
    {
        // Fetch all courses
        $courses = Courses::all(); 

        // Fetch the currently authenticated admin and their organization
        $admin = Auth::guard('admin')->user();
        $org = $admin->org;

        // Return the view with both courses and org data
        return view('admin_addprod', compact('courses', 'org'));
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
        return redirect()->route('admin.account')->with('success', 'Product deleted successfully.');
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

    public function updateRestrictions(Request $request, $id)
    {
        $product = Products::findOrFail($id);
    
        // Sync allowed courses
        $allowedCourses = $request->input('allowed_courses', []);
        $product->courses()->sync($allowedCourses);
    
        // Disable edit mode after saving changes
        session()->forget('edit_mode');
        
        return redirect()->back()->with('success', 'Course restrictions updated successfully!');
    }
    

    public function toggleEditMode(Request $request, $id)
    {
        session(['edit_mode' => !session('edit_mode', false)]); // Toggle edit mode
        return redirect()->back();
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

        $admin = Auth::guard('admin')->user();
        $org = $admin->org;
    
        // Set other fields
        $news->org = $org;
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
        // Fetch the currently authenticated admin and their organization
        $admin = Auth::guard('admin')->user();
        $org = $admin->org;

        // Return the view with both courses and org data
        return view('admin_addnews', compact('org'));
    }

    public function student_account()
    {
        $student = Auth::guard('student')->user();
    
        // Eager load the related course
        $course = $student->course;
    
        return view('student_account', [
            'firstname' => $student->first_name,
            'lastname' => $student->last_name,
            'middlename' => $student->middle_name,
            'student_id' => $student->student_id,
            'course' => $course // Pass the course object to the view
        ]);
    }
    

    public function student_cart()
    {
        $student = Auth::guard('student')->user();
        $course = $student->course;
        $cartItems = Cart::where('student_id', $student->id)->get();
        $totalPrice = $cartItems->sum('price'); // Calculate total price
    
        return view('student_cart', [
            'firstname' => $student->first_name,
            'lastname' => $student->last_name,
            'middlename' => $student->middle_name,
            'student_id' => $student->student_id,
            'course' => $course, // Pass course data
            'cartItems' => $cartItems, // Pass cart items
            'totalPrice' => $totalPrice, // Pass total price
        ]);
    }
    
    

    public function addToCart(Request $request)
    {
        \Log::info($request->all()); 
    
        $request->validate([
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'product_id' => 'required|exists:products,id'  // Validate that product_id exists
        ]);
    
        $product = Products::findOrFail($request->product_id);  // Retrieve the product by ID
        $student = Auth::guard('student')->user();
    
        // Prepare cart data with required fields
        $cartData = [
            'name' => $product->name,
            'org' => $product->org,
            'size' => $request->size,
            'quantity' => $request->quantity,
            'price' => $product->price * $request->quantity,
            'photos' => $product->photos,
            'student_id' => $student->id,
        ];
    
        // Insert into cart
        Cart::create($cartData);
    
        // Redirect to the student cart page with a success message
        return response()->json(['success' => true, 'message' => 'Product added to cart successfully']);
    }
    
    
    
    

    public function updateCartQuantity(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        $cartItem = Cart::findOrFail($id);
        $cartItem->quantity = $request->quantity;
        $cartItem->price = $cartItem->quantity * $cartItem->price / $cartItem->getOriginal('quantity'); // Adjust price
        $cartItem->save();

        return response()->json(['success' => true]);
    }

    // Remove item
    public function removeItem($itemId)
    {
        // Find the cart item by its ID
        $cartItem = Cart::find($itemId);
    
        if (!$cartItem) {
            // Redirect back with error message if item is not found
            return redirect()->route('student.cart')->with('error', 'Item not found.');
        }
    
        // Delete the cart item
        $cartItem->delete();
    
        // Redirect back to the cart page with a success message
        return redirect()->route('student.cart')->with('success');
    }
    
    public function checkout(Request $request)
    {
        try {
            // Ensure the student is authenticated
            $student = Auth::guard('student')->user();
            if (!$student) {
                return redirect()->route('student.login')->with('error', 'You must be logged in to proceed with the checkout.');
            }
    
            // Retrieve course information
            $course = $student->course;
            if (!$course) {
                return redirect()->route('home')->with('error', 'Course information not available.');
            }
    
            // Retrieve and validate the selected items from the request
            $selectedItems = json_decode($request->input('selected_items'), true);
            if (empty($selectedItems)) {
                return redirect()->route('student.cart')->with('error', 'No items selected for checkout.');
            }
    
            // Fetch the cart items from the database based on selected item IDs
            $cartItems = Cart::whereIn('id', $selectedItems)->get();
    
            // Check if the selected items exist in the cart
            if ($cartItems->count() !== count($selectedItems)) {
                return redirect()->route('student.cart')->with('error', 'Some of the selected items are no longer available in your cart.');
            }
    
            // Pass data to the checkout view
            return view('checkout', [
                'cartItems' => $cartItems,
                'firstname' => $student->first_name,
                'lastname' => $student->last_name,
                'middlename' => $student->middle_name,
                'student_id' => $student->student_id,
                'course' => $course, // Pass course data
            ]);
    
        } catch (\Exception $e) {
            // Log the error and return a generic error message to the user
            \Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'An error occurred while processing your checkout. Please try again later.');
        }
    }
    
    

    public function placeOrder(Request $request)
    {
        // Validate inputs
        $request->validate([
            'selected_items' => 'required',
            'payment_method' => 'required',
            'gcash_reference' => 'required_if:payment_method,gcash',
        ]);
    
        // Process the order
        $selectedItems = json_decode($request->input('selected_items'), true);
        foreach ($selectedItems as $itemId) {
            // Update order table (create new order)
            Order::create([
                'student_id' => auth()->id(),
                'cart_item_id' => $itemId,
                'payment_method' => $request->input('payment_method'),
                'gcash_reference' => $request->input('gcash_reference'),
            ]);
    
            // Optionally: Remove item from cart after checkout
            Cart::destroy($itemId);
        }
    
        return redirect()->route('cart.index')->with('success', 'Order placed successfully!');
    }
    




}
