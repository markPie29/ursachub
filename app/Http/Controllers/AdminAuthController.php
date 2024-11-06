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
        return view('auth.register_admin');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'org' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create($request->all());

        return redirect()->route('admin.login')->with('success', 'Registration successful. Please log in.');
    }

    public function showLoginForm()
    {
        return view('auth.login_admin');
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
            $org = $admin->org;

            // Fetch products that belong to the authenticated admin's organization
            $products = Products::where('org', $org)->get(); // Adjust column name if necessary
            $news = News::where('org', $org)->get();

            // Redirect to the admin_account view directly with the organization and data
            return view('admin_account', [
                'org_name' => $org, // Pass the organization name
                'products' => $products, // Pass the filtered products
                'news' => $news, // Pass the news
            ]);
        }

        return back()->withErrors([
            'name' => 'The provided credentials do not match our records.',
        ])->onlyInput('name');
    }
}
