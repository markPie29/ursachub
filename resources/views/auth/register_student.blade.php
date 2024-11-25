@extends('layouts.login_reg_layout')

@section('content')
<div class="registration-container">
    
    <form method="POST" action="{{ route('student.register') }}" class="registration-form">
        @csrf
        <div class="form-group">
            <h2>Student Registration</h2>
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" style="text-transform:uppercase" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" style="text-transform:uppercase" required>
        </div>
        <div class="form-group">
            <label for="middle_name">Middle Name</label>
            <input type="text" class="form-control" id="middle_name" name="middle_name" style="text-transform:uppercase">
        </div>
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" class="form-control" id="student_id" name="student_id" required>
        </div>
        <div class="form-group">
            <label for="course">Program</label>

            <select class="form-control" id="course" name="course_id" required>
                <option value="" disabled selected>Select your program</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>


        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <a href="#" onclick="showPassword()" class="show-password">Show Password</a>
        
        <button type="submit" class="btn btn-reg">Register</button>
        
    </form>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
