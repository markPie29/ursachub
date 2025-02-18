<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\News;
use App\Models\Courses;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\Admin;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Student;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
// use App\User;

class UrsacHubController extends Controller
{

    public function home() {
        // Fetch the latest 2 news
        $news = News::orderBy('updated_at', 'desc')->take(2)->get();
    
        // Fetch the latest 3 products
        $products = Products::orderBy('updated_at', 'desc')->take(3)->get();
    
        // Pass the data to the view
        return view('home', compact('news', 'products'));
    }
    public function admin()
    {
        // Check if the user is authenticated as an admin
        if (Auth::guard('admin')->check()) {
            // Get the authenticated admin user
            $admin = Auth::guard('admin')->user();
            
            // Fetch data based on the authenticated admin's organization
            $products = Products::where('org', $admin->org)->get(); 
            $news = News::where('org', $admin->org)->get();
    
            // Pass the organization name, products, and news to the view
            return view('admin_account', compact('admin','news','products'));
        }
    
        // Redirect to the admin login page if not authenticated
        return redirect()->route('admin.login')->with('error', 'You must be logged in to access this page.');
    }

    public function adminProducts()
    {
        // Check if the user is authenticated as an admin
        if (Auth::guard('admin')->check()) {
            // Get the authenticated admin user
            $admin = Auth::guard('admin')->user();
            
            // Fetch data based on the authenticated admin's organization
            $products = Products::where('org', $admin->org)->paginate(9); 
    
            // Pass the organization name, products, and news to the view
            return view('admin_products', compact('admin','products'));
        }
    
        // Redirect to the admin login page if not authenticated4
        return redirect()->route('admin.login')->with('error', 'You must be logged in to access this page.');
    }

    public function adminNews()
    {
        // Check if the user is authenticated as an admin
        if (Auth::guard('admin')->check()) {
            // Get the authenticated admin user
            $admin = Auth::guard('admin')->user();
            
            // Fetch data based on the authenticated admin's organization
            $news = News::where('org', $admin->org)->paginate(8); 
            $events = Event::where('org', $admin->org)->get();

            // Pass the organization name, products, and news to the view
            return view('admin_news', compact('admin','news','events'));
        }
    
        // Redirect to the admin login page if not authenticated
        return redirect()->route('admin.login')->with('error', 'You must be logged in to access this page.');
    }


    public function uploadLogo(Request $request)
    {
        // Validate the request
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480', // Ensure it's an image
        ]);

        // Handle the file upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('logos', $fileName, 'public'); // Save in the 'public/logos' directory

            // Update the profile_photo column for the admin
            $admin = Admin::find(auth('admin')->id()); // Assumes the admin is logged in
            $admin->logo = $filePath;
            $admin->save();

            return redirect()->route('admin.account')->with('success', 'Logo uploaded successfully!');
        }

        return redirect()->route('admin.account')->with('error', 'Error in Uploading the Logo');
    }
    


    public function products_page() {
        // Get the products with their orgs
        $products = Products::orderBy('updated_at', 'desc')->paginate(12);
    
        // Add the logo to each product by matching the org
        $products->each(function($product) {
            // Fetch the admin logo by matching org name
            $admin = Admin::where('org', $product->org)->first();
            $product->logo = $admin ? $admin->logo : null; // Assign the logo, or null if not found
        });
    
        return view('products_page', [
            'products' => $products
        ]);
    }

    public function news_page() {
        $news = News::orderBy('updated_at', 'desc')->paginate(8);
        

        $news->each(function($newsx) {
            // Fetch the admin logo by matching org name
            $admin = Admin::where('org', $newsx->org)->first();
            $newsx->logo = $admin ? $admin->logo : null; // Assign the logo, or null if not found
        });

        $events = Event::orderBy('updated_at', 'desc')->get();

        $events->each(function($event) {
            // Fetch the admin logo by matching org name
            $admin = Admin::where('org', $event->org)->first();
            $event->logo = $admin ? $admin->logo : null; // Assign the logo, or null if not found
        });
    
        return view('news_page', [
            'news' => $news,
            'events' => $events
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

        $product->logo = $admin->logo;
    
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
        return redirect()->route('admin.account')->with('success', 'News deleted successfully.');
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

    public function updateStocks(Request $request, $id)
    {
        $product = Products::findOrFail($id);
    
        // Validate inputs
        $request->validate([
            'small' => 'required|integer|min:0',
            'medium' => 'required|integer|min:0',
            'large' => 'required|integer|min:0',
            'extralarge' => 'required|integer|min:0',
            'double_extralarge' => 'required|integer|min:0',
        ]);
    
        // Update stock values
        $product->small = $request->input('small');
        $product->medium = $request->input('medium');
        $product->large = $request->input('large');
        $product->extralarge = $request->input('extralarge');
        $product->double_extralarge = $request->input('double_extralarge');
        $product->save();
    
        return redirect()->back()->with('success', 'Stocks updated successfully.');
    }

    public function updateRestrictions(Request $request, $id)
    {
        $product = Products::findOrFail($id);
    
        // Sync allowed courses
        $allowedCourses = $request->input('courses', []);
        $product->courses()->sync($allowedCourses);
    
        return redirect()->back()->with('success', 'Course restrictions updated successfully!');
    }

    public function updatePrice(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $product = Products::findOrFail($id);
        $product->price = $request->price;
        $product->save();

        return redirect()->back()->with('success', 'Price updated successfully.');
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
        return redirect()->back()->with('success', 'News updated successfully!');
    }


    public function addnews(Request $request)
    {
        $news = new News();

        $admin = Auth::guard('admin')->user();
        $org = $admin->org;
    
        // Set other fields
        $news->org = $org;
        $news->headline = $request->input('headline');
        $news->content = nl2br($request->input('content'));
    
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

        $news->logo = $admin->logo;

        $news->save();
    
        return redirect()->route('admin.account')->with('success', 'News added successfully.');
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
        // Get the authenticated student
        $student = Auth::guard('student')->user();

        // Retrieve posts and eager load the related student (user) for efficiency
        $posts = Post::where('user_id', $student->id)
                    ->latest()
                    ->with('user') // Assuming the Post model has a 'user' relationship defined
                    ->get();

        // Eager load the student's course
        $course = $student->course;

        // Return the view with necessary data
        return view('student_account', [
            'firstname' => $student->first_name,
            'lastname' => $student->last_name,
            'middlename' => $student->middle_name,
            'student_id' => $student->student_id,
            'course' => $course,
            'posts' => $posts
        ]);
    }


    public function student_cart()
    {
        $student = Auth::guard('student')->user();
        $course = $student->course;
        $cartItems = Cart::where('student_id', $student->student_id)->get();
        $totalPrice = $cartItems->sum('price'); // Calculate total price
    
        // Fetch admin details for the organizations
        $admins = Admin::all(); // Get all admins (with their GCash details)
    
        return view('student_cart', [
            'firstname' => $student->first_name,
            'lastname' => $student->last_name,
            'middlename' => $student->middle_name,
            'student_id' => $student->student_id,
            'course' => $course, // Pass course data
            'cartItems' => $cartItems, // Pass cart items
            'totalPrice' => $totalPrice, // Pass total price
            'admins' => $admins, // Pass all admins to the view
        ]);
    }
    
    
    

    public function addToCart(Request $request)
    {
        \Log::info($request->all());
    
        $request->validate([
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'product_id' => 'required|exists:products,id', // Validate that product_id exists
        ]);
    
        $product = Products::findOrFail($request->product_id); // Retrieve the product by ID
        $student = Auth::guard('student')->user();
    
        // Check if the same product (name, size, org) already exists in the cart
        $existingCartItem = Cart::where([
            ['student_id', '=', $student->student_id],
            ['name', '=', $product->name],
            ['size', '=', $request->size],
            ['org', '=', $product->org],
        ])->first();
    
        if ($existingCartItem) {
            // If item exists, update the quantity and price
            $existingCartItem->quantity += $request->quantity;
            $existingCartItem->price = $product->price * $existingCartItem->quantity; // Update total price
            $existingCartItem->save();
        } else {
            // If item does not exist, add a new entry
            $cartData = [
                'name' => $product->name,
                'org' => $product->org,
                'size' => $request->size,
                'quantity' => $request->quantity,
                'price' => $product->price * $request->quantity, // Total price for the quantity
                'photos' => $product->photos,
                'student_id' => $student->student_id,
            ];
    
            Cart::create($cartData);
        }
    
        // Return a success response
        return response()->json(['success' => true, 'message' => 'Product added to cart successfully']);
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        
        // Search products by name or other fields
        $products = Products::where('name', 'like', '%' . $query . '%')
            ->orWhere('org', 'like', '%' . $query . '%')
            ->paginate(12);

        return view('products_page', compact('products')); // Replace 'your-blade-template' with the actual template name
    }
    

    
    
    public function searchNews(Request $request)
    {
        $query = $request->input('query');
        
        // Search products by name or other fields
        $news = News::where('headline', 'like', '%' . $query . '%')
            ->orWhere('org', 'like', '%' . $query . '%')
            ->paginate(8);

        $news->each(function($newsx) {
            // Fetch the admin logo by matching org name
            $admin = Admin::where('org', $newsx->org)->first();
            $newsx->logo = $admin ? $admin->logo : null; // Assign the logo, or null if not found
        });

        return view('news_page', compact('news')); // Replace 'your-blade-template' with the actual template name
    }

    public function searchOrgs(Request $request)
    {
        $query = $request->input('query');
        
        $orgs = Admin::where('org', 'like', '%' . $query . '%')
        ->orderBy('org', 'asc') // Sort by 'org' in ascending order (A-Z)
        ->get();

        return view('orgs_page', compact('orgs')); 
    }

    public function orgs_page()
    {
        $orgs = Admin::orderBy('org', 'asc')->get();
    
        return view('orgs_page', [
            'orgs' => $orgs
        ]);
    }

    public function show_eachorgs ($id) 
    {
        $student = Auth::guard('student')->user();
        $org = Admin::FindOrFail($id);
        
        $products = Products::where('org', $org->org)->paginate(); 
        $news = News::where('org', $org->org)->paginate();

        $events = Event::where('org', $org->org)
        ->orderBy('updated_at', 'desc')->get();

        $events->each(function($event) {
            // Fetch the admin logo by matching org name
            $admin = Admin::where('org', $event->org)->first();
            $event->logo = $admin ? $admin->logo : null; // Assign the logo, or null if not found
        });

        // Pass the organization name, products, and news to the view
        return view('show_eachorg', [
            'org' => $org,
            'products' => $products,
            'news' => $news,
            'events' => $events
        ]);

    }

    public function view_events () 
    {
        $events = Event::orderBy('date', 'desc')->get();

        $events->each(function($event) {
            // Fetch the admin logo by matching org name
            $admin = Admin::where('org', $event->org)->first();
            $event->logo = $admin ? $admin->logo : null; // Assign the logo, or null if not found
        });

        return view('view_events', [
            'events' => $events
        ]);

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
        $selectedItemIds = $request->input('selected_items');
        $paymentMethod = $request->input('payment_method');
        $gcashRef = $request->input('gcash_ref', null);
        $studentId = auth()->user()->id;

        // Retrieve the selected items from the cart
        $selectedItems = Cart::whereIn('id', $selectedItemIds)->get();

        if ($selectedItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        // Process the order...
        // For simplicity, assuming the order is created successfully

        $order = Order::create([
            'student_id' => $studentId,
            'total_price' => $selectedItems->sum('price'),
            'payment_method' => $paymentMethod,
            'gcash_ref' => $gcashRef,
        ]);

        foreach ($selectedItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        return response()->json(['success' => true, 'redirect_url' => route('order.success', $order->id)]);
    }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|json',
            'student_id' => 'required|string',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'nullable|string',
            'course' => 'required|string',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'gcash_photo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Begin a database transaction to ensure atomicity
        DB::beginTransaction();

        try {
            $orderNumber = Str::upper(Str::random(10));
            $items = json_decode($validated['items'], true);

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('gcash_photo')) {
                $photoPath = $request->file('gcash_photo')->store('gcash_proofs', 'public');
            }

            // Loop through each item in the order
            foreach ($items as $item) {
                // Check if the product exists
                $product = DB::table('products')
                    ->where('name', $item['name'])
                    ->where('org', $item['org'])
                    ->first();

                // If product not found
                if (!$product) {
                    DB::rollBack(); // Rollback the transaction if the product is not found
                    return response()->json(['success' => false, 'message' => "Product {$item['name']} not found."], 400);
                }

                // Check stock availability based on size
                $availableStock = $product->{$item['size']};

                // If there's insufficient stock for the requested size
                if ($availableStock < $item['quantity']) {
                    DB::rollBack(); // Rollback the transaction if there's insufficient stock
                    return response()->json(['success' => false, 'message' => "Insufficient stock for {$item['name']} ({$item['size']}). Available stock: {$availableStock}."], 400);
                }

                // Insert the order into the orders table
                DB::table('orders')->insert([
                    'name' => $item['name'],
                    'size' => $item['size'],
                    'price' => $item['price'],
                    'org' => $item['org'],
                    'quantity' => $item['quantity'],
                    'student_id' => $validated['student_id'],
                    'firstname' => $validated['firstname'],
                    'lastname' => $validated['lastname'],
                    'middlename' => $validated['middlename'],
                    'course' => $validated['course'],
                    'payment_method' => $validated['payment_method'],
                    'reference_number' => $validated['reference_number'],
                    'gcash_proof' => $photoPath,
                    'order_number' => $orderNumber,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the product stock after order is placed
                DB::table('products')
                    ->where('id', $product->id)
                    ->decrement($item['size'], $item['quantity']);
                
                // Remove the item from the cart after placing the order
                DB::table('carts')
                    ->where('student_id', $validated['student_id'])
                    ->where('name', $item['name'])
                    ->where('size', $item['size'])
                    ->where('org', $item['org'])
                    ->delete();
            }

            // Commit the transaction if all operations succeed
            DB::commit();

            // Return success response
            return response()->json(['success' => true, 'order_number' => $orderNumber]);
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }



    public function studentOrders()
    {
        // Get the logged-in student's ID
        $student = Auth::guard('student')->user();
        $studentId = $student->student_id;

        // Fetch grouped orders with their details directly from the Order model
        $orders = Orders::where('student_id', $studentId)
            ->select('order_number', 'name', 'size', 'quantity', 'price', 'org', 'status', 'created_at')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('order_number');

        return view('student_orders', compact('orders'));
    }
    public function adminOrders()
    {
        $org_name = auth('admin')->user()->org; 

        $orders = Orders::where('org', $org_name)
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('order_number');

        return view('admin_vieworders', compact('orders', 'org_name'));
    }

    public function updateOrderStatus(Request $request, $order_number)
    {
        $orders = Orders::where('order_number', $order_number)->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'Order not found.');
        }

        $currentStatus = $orders->first()->status;
        $newStatus = $request->input('status');

        // Prevent status change if the order is already claimed
        if ($currentStatus === 'claimed') {
            return back()->with('error', 'Cannot change status of a claimed order.');
        }

        if ($newStatus === 'claimed') {
            $request->validate([
                'claimed_by' => 'required|string|max:255',

            ]);

            foreach ($orders as $order) {
                $order->status = $newStatus;
                $order->claimed_by = $request->input('claimed_by');
                $order->claimed_at = now();
                $order->save();
            }
            return back()->with('success', 'Order status updated to claimed successfully.');
        }

        foreach ($orders as $order) {
            $order->status = $newStatus;
            $order->save();
        }
        return back()->with('success', "Order status updated to {$newStatus} successfully.");
    }

    public function searchOrders(Request $request)
    {
        $search = $request->input('search');
    
        // Get the organization name from the authenticated admin
        $org_name = auth('admin')->user()->org;
    
        // Fetch orders that match the product name or order number,
        // belong to the admin's organization, and exclude 'claimed' status
        $orders = Orders::where('org', $org_name)
            ->where('status', '!=', 'claimed')
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                      ->orWhere('order_number', 'LIKE', "%$search%");
            })
            ->orderBy('created_at', 'desc') // Sort by creation date
            ->get()
            ->groupBy('order_number');
    
        return view('admin_vieworders', compact('orders', 'org_name'));
    }

    public function trackOrders(Request $request)
    {
        // Get the organization name from the authenticated admin user
        $org_name = auth('admin')->user()->org;
    
        // Base query: Filter orders by the organization name
        $query = Orders::where('org', $org_name);
    
        // Apply search filter if 'search' input exists
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('order_number', 'like', '%' . $search . '%');
            });
        }
    
        // Fetch and group orders by order number, ordered by creation date
        $orders = $query->orderBy('created_at', 'desc')->get()->groupBy('order_number');
    
        // Return the view with the filtered and grouped orders
        return view('admin_vieworders', compact('orders', 'org_name'));
    }

    public function finishedOrders(Request $request)
    {
        $org_name = auth('admin')->user()->org; 

        $orders = Orders::where('status', 'claimed')
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->groupBy('order_number');

        return view('admin_finishedorders', compact('orders', 'org_name'));
    }

      
    public function createPost(Request $request)
    {
        // Validate input fields
        $request->validate([
            'content' => 'required|string|max:200', // Content validation
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:20480', // Validate each photo
        ]);
    
        // Check if the post should be anonymous
        $isAnonymous = $request->has('anonymous') ? 'anon' : null;
    
        // Process photos if any are uploaded
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                \Log::info('File uploaded: ' . $photo->getClientOriginalName());
            }
            
            $photos = $request->file('photos');
            foreach ($photos as $index => $photo) {
                if ($index >= 5) break; // Limit to 5 images
                if ($photo->isValid()) {
                    $photoPaths[] = $photo->store('post_photos', 'public'); // Store image paths in storage
                }
            }
        }
    
        // Get current student's ID
        $studentId = Auth::guard('student')->id();
    
        // Store the post in the database
        Post::create([
            'user_id' => $studentId,
            'content' => $request->input('content'),
            'image' => json_encode($photoPaths), // Save image paths as JSON
            'anon' => $isAnonymous, // Set anon column to 'anon' or null based on the checkbox
        ]);
    
        return redirect()->route('student.account')->with('success', 'Post created successfully.');
    }


    public function add_event(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        // Validate incoming data
        $validatedData = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_venue' => 'required|string|max:255',
            'event_date_time' => 'required|date'
        ]);
    
        // Save the event to the database
        $event = new Event(); // Assuming you have an Event model
        $event->name = $validatedData['event_name'];
        $event->venue = $validatedData['event_venue'];
        $event->date = $validatedData['event_date_time'];
        $event->org = $admin->org;
        $event->save();
    
        // Redirect or return a response
        return redirect()->back()->with('success', 'Event added successfully!');
    }

    public function edit_event(Request $request, $id) {

        $admin = Auth::guard('admin')->user();
        // Validate incoming data
        $validatedData = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_venue' => 'required|string|max:255',
            'event_date_time' => 'required|date'
        ]);
    
        // Save the event to the database
        $event = Event::findOrFail($id); // Assuming you have an Event model
        $event->name = $validatedData['event_name'];
        $event->venue = $validatedData['event_venue'];
        $event->date = $validatedData['event_date_time'];
        $event->org = $admin->org;
        $event->save();
    
        // Redirect or return a response
        return redirect()->back()->with('success', 'Event edited successfully!');
    }

    public function delete_event($id) {
        try {
            $event = Event::findOrFail($id);
            $event->delete();
            return redirect()->back()->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the event.');
        }
    }
    

    
    
    

    
    

    public function addComment(Request $request, Post $post)
    {
        $request->validate(['content' => 'required|string']);
        $post->comments()->create(['user_id' => auth()->id(), 'content' => $request->content]);
        return back()->with('success', 'Comment added!');
    }

    public function likePost(Post $post)
    {
        if (!$post->likes()->where('user_id', auth()->id())->exists()) {
            $post->likes()->create(['user_id' => auth()->id()]);
        }
        return back()->with('success', 'Post liked!');
    }

    public function repost(Post $post)
    {
        Repost::create(['user_id' => auth()->id(), 'post_id' => $post->id]);
        return back()->with('success', 'Post reposted!');
    }

    public function freedomwall() {
        // Get the authenticated student
        $student = Auth::guard('student')->user();

        // Retrieve posts and eager load the related student (user) for efficiency
        $posts = Post::latest()->get();

        // Eager load the student's course
        $course = $student->course;

        // Return the view with necessary data
        return view('freedomwall', [
            'firstname' => $student->first_name,
            'lastname' => $student->last_name,
            'middlename' => $student->middle_name,
            'student_id' => $student->student_id,
            'course' => $course,
            'posts' => $posts
        ]);
    }
    
}
