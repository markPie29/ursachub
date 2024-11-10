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
        $courses = [
            'Bachelor of Science in Computer Engineering',
            'Bachelor of Science in Civil Engineering',
            'Bachelor of Science in Hospitality Management',
            'Bachelor of Science in Business Administration'
        ];
        return view('auth.register_student', compact('courses'));
    }

    public function register(Request $request)
    {
        // Validate incoming request data, including student ID format
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'student_id' => [
                'required',
                'regex:/^AC\d{4}-\d{5}$/', // Enforces the ACYYYY-XXXXX format
                'unique:students', // Ensures the ID is unique in the students table
            ],
            'course' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'student_id.regex' => 'Invalid Student ID',
        ]);

        // Create the new student record
        Student::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'student_id' => $request->student_id,
            'course' => $request->course,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('student.login')->with('success', 'Registration successful. Please log in.');
    }

    public function showLoginForm()
    {
        return view('auth.login_student');
    }

    public function login(Request $request)
    {
        // Validate the login credentials
        $credentials = $request->validate([
            'student_id' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt login with student guard
        if (Auth::guard('student')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('student.home'); // Redirect to the student's home page
        }

        return back()->withErrors([
            'student_id' => 'The provided credentials do not match our records.',
        ])->onlyInput('student_id');
    }

    public function logout(Request $request)
    {
        // Log out the student and invalidate the session
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}
