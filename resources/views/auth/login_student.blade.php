@extends('layouts.admin_layout')

@section('content')
<div class="container">
    

    <!-- Login Form starts here -->
    <form method="POST" action="{{ route('student.login') }}">
        @csrf

        <!-- Login div starts here -->
        <div class="login-1">
            <h2 class="text-1">Student Login</h2>
            <h2 class="text-2">Login</h2>

            <div class="form-group inputBox-1">
                <label for="student_id">Student ID</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="student_id" 
                    name="student_id" 
                    placeholder="Student ID" 
                    required>
            </div>

            <div class="form-group inputBox-2">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="Password" 
                    required>
            </div>

            <div class="inputBox-3">
                <button type="submit" class="btn btn-primary">Sign in</button>
            </div>

            <!-- Links section -->
            <div class="links">
                <a href="#">Forget Password</a>
                <a href="{{ route('student.register') }}">Sign Up Now</a>
            </div>

            <!-- Registration prompt inside the container -->
            <div class="register-prompt">
                <p>Don't have an account? <a href="{{ route('student.register') }}">Register here</a></p>
            </div>
        </div>
        <!-- Login div ends here -->
    </form>
    <!-- Login Form ends here -->

    <!-- Error handling -->
    @if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
