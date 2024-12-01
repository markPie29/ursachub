@extends('layouts.login_reg_layout')

@section('content')
<div class="container">
    

    <!-- Login Form starts here -->
    <form method="POST" action="{{ route('student.login') }}">
        @csrf

        <!-- Login div starts here -->
        <div class="login-1">
            <h2 class="text-1">Welcome to URSAC Hub!</h2>
            <h2 class="text-2">Student Login</h2>

            <div class="form-group inputBox-1">
                <label for="student_id" class="label-registration">Student ID</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="student_id" 
                    name="student_id" 
                    placeholder="AC202X-XXXXX"
                    style="text-transform:uppercase" 
                    required>
            </div>

            <div class="form-group inputBox-2">
                <label for="password" class="label-registration">Password</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="Password" 
                    style="text-transform:uppercase"
                    required>
            </div>

            <a href="#" onclick="showPassword()" class="show-password">Show Password</a>
            

            <div class="inputBox-3">
                <button type="submit" class="btn btn-primary">Sign In</button>
            </div>


            <!-- Registration prompt inside the container -->
            <div class="register-prompt">
                <p>Don't have an account? <a href="{{ route('student.register') }}">Register here.</a></p>
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
