<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register_student');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'student_id' => 'required|string|max:255|unique:students',
            'course' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Student::create($request->all());

        return redirect()->route('student.login')->with('success', 'Registration successful. Please log in.');
    }

    public function showLoginForm()
    {
        return view('auth.login_student');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'student_id' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('student')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('student.home'); // Redirect to /home view
        }

        return back()->withErrors([
            'student_id' => 'The provided credentials do not match our records.',
        ])->onlyInput('student_id');
    }
}
