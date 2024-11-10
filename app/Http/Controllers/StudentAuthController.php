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
        $courses = ['Bachelor of Science in Computer Engineering', 'Bachelor of Science in Civil Engineering', 'Bachelor of Science in Hospitality Management ', 'Bachelor of Science in Business Administration'];
        return view('auth.register_student', compact('courses'));
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

    public function logout(Request $request)
    {
        Auth::guard('student')->logout(); // or 'student' as needed
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}
