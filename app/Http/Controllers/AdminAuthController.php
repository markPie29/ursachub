<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Products;
use App\Models\News;
use Illuminate\Support\Facades\Hash; // <-- This is the correct import for Hash


class AdminAuthController extends Controller
{
    public function showRegisterForm()
    {
    $organizations = ['Association of Civil Engineering Students', 'Association of Concerned Computer Engineering Students', 'College of Engineering - Student Council', 'Association of Junior Administrator', 'Association of Stenographers Aiming for Progress',"Bachelor of Elementary Education Society", "Bartender's Society",
    'Christian Brotherhood International', 'College of Business Administration - Student Council', 'College of Education - Student Council', 'College of Hospitality Industry - Student Council', 'CORO URSAC', 'Elevate University of Rizal System Antipolo Chapter', 'Environmental Army Society', 'Hiyas ng Rizal Dance Troup', 
    'Hospitality Management Society', 'Kapulungang Filipino', 'Litera Organization', 'Radicals Organization', 'Red Cross Youth Council', 'Tipolo Student Publication', 'Tourism Society Organization', 'University Supreme Student Government', 'URSAC - Fierce Group Facilitator', 'URSAC - Social Studies Organization for UNESCO', 'URSAC Extensionist'];
        return view('auth.register_admin',compact('organizations'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'org' => 'required|string|max:255|unique:admins,org',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create($request->all());

        return redirect()->route('admin.register')->with('success', 'Registration successful. Please log in.');
    }

    public function showLoginForm()
    {
    $organizations = ['Association of Civil Engineering Students', 'Association of Concerned Computer Engineering Students', 'College of Engineering - Student Council', 'Association of Junior Administrator', 'Association of Stenographers Aiming for Progress',"Bachelor of Elementary Education Society", "Bartender's Society",
    'Christian Brotherhood International', 'College of Business Administration - Student Council', 'College of Education - Student Council', 'College of Hospitality Industry - Student Council', 'CORO URSAC', 'Elevate University of Rizal System Antipolo Chapter', 'Environmental Army Society', 'Hiyas ng Rizal Dance Troup', 
    'Hospitality Management Society', 'Kapulungang Filipino', 'Litera Organization', 'Radicals Organization', 'Red Cross Youth Council', 'Tipolo Student Publication', 'Tourism Society Organization', 'University Supreme Student Government', 'URSAC - Fierce Group Facilitator', 'URSAC - Social Studies Organization for UNESCO', 'URSAC Extensionist'];
        return view('auth.login_admin',compact('organizations'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required|string',
            'org' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {

            // Get the authenticated admin's organization
            $admin = Auth::guard('admin')->user();
            
            // Fetch data based on the authenticated admin's organization
            $products = Products::where('org', $admin->org)->get(); 
            $news = News::where('org', $admin->org)->get();
    
            // Pass the organization name, products, and news to the view
            return view('admin_account', compact('admin','news','products'));
        }

        return back()->withErrors([
            'name' => 'The provided credentials do not match our records.',
        ])->onlyInput('name');
    }

    public function admin_update_name(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $admin = auth('admin')->user(); // Assuming the admin is authenticated
        $admin->name = $request->name;
        $admin->save(); // Save the updated name
    
        return redirect()->route('admin.account')->with('success', 'Name edited successfully');
    }
    
    public function admin_update_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        $admin = Auth::guard('admin')->user();
    
        // Check if the current password matches the stored one
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
    
        // Simply set the new password, as the model will hash it
        $admin->password = $request->password;
        $admin->save();
    
        return redirect()->route('admin.account')->with('success', 'Password updated successfully.');
    }

    public function admin_update_gcash(Request $request)
    {
        $request->validate([
            'gcash_name' => 'required|string',
            'gcash_number' => 'required|string',
        ]);
    
        // Get the authenticated admin
        $admin = Auth::guard('admin')->user();
    
        // Update the gcash_name and gcash_number
        $admin->gcash_name = $request->gcash_name;
        $admin->gcash_number = $request->gcash_number;
    
        // Save the changes
        $admin->save();
    
        // Redirect back with a success message
        return redirect()->route('admin.account')->with('success', 'GCash details updated successfully.');
    }

    public function admin_update_fb_link(Request $request)
    {
        $request->validate([
            'fb_link' => 'required|url',
        ]);
    
        // Get the authenticated admin
        $admin = Auth::guard('admin')->user();


        $admin->fb_link = $request->fb_link;
    
        $admin->save();
    
        return redirect()->route('admin.account')->with('success', 'Link updated successfully.');
    }
    


    public function logout(Request $request)
    {
        Auth::guard('admin')->logout(); // or 'student' as needed
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
