<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // <-- This is the correct import for Hash

class StudentAuthController extends Controller
{
    public function showRegisterForm()
    {
        $courses = Courses::all();
        return view('auth.register_student', compact('courses'));
    }

    public function register(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'student_id' => [
                'required',
                'regex:/^AC\d{4}-\d{5}$/',
                'unique:students',
            ],
            'course_id' => 'required|exists:courses,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the new student record
        Student::create([
            'first_name' => strtoupper($request->first_name),
            'last_name' => strtoupper($request->last_name),
            'middle_name' => $request->middle_name ? strtoupper($request->middle_name) : null,
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'password' => $request->password, // Hash password here
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

            return redirect()->route('student.home'); // Redirect to the student's home page
        }

        return back()->withErrors([
            'student_id' => 'The provided credentials do not match our records.',
        ])->onlyInput('student_id');
    }

    
    public function student_update_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        $student = Auth::guard('student')->user();
    
        // Check if the current password matches the stored one
        if (!Hash::check($request->current_password, $student->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
    
        // Simply set the new password, as the model will hash it
        $student->password = $request->password;
        $student->save();
    
        return redirect()->route('student.account')->with('success', 'Password updated successfully.');
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
