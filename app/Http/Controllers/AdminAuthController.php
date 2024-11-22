<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Products;
use App\Models\News;


class AdminAuthController extends Controller
{
    public function showRegisterForm()
    {
    $organizations = ['Association of Civil Engineering Students', 'Association of Concerned Computer Engineering Students', 'College of Engineering - Student Council', 'Association of Junior Administrator', 'Association of Stenographers Aiming for Progress', 'Association of Stenographers Aiming for Progress', "Bartender's Society",
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

        return redirect()->route('admin.login')->with('success', 'Registration successful. Please log in.');
    }

    public function showLoginForm()
    {
    $organizations = ['Association of Civil Engineering Students', 'Association of Concerned Computer Engineering Students', 'College of Engineering - Student Council', 'Association of Junior Administrator', 'Association of Stenographers Aiming for Progress', 'Association of Stenographers Aiming for Progress', "Bartender's Society",
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
            $request->session()->regenerate();

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

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout(); // or 'student' as needed
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
