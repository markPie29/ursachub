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
                    autocomplete="username"
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
                    autocomplete="current-password"
                    required>
            </div>

            <a href="#" onclick="showPassword()" class="show-password">Show Password</a>
            

            <div class="inputBox-3" style="display: flex; flex-direction: column; gap: 10px; margin-top: 15px;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
                <button type="button" class="btn btn-secondary" onclick="fillDemoStudent()" style="background-color: #6c757d; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; width: 100%; font-weight: 500;">⚡ Auto-fill Student Credentials (Demo)</button>
            </div>

            <!-- Registration prompt inside the container -->
            <div class="register-prompt" style="margin-top: 15px;">
                <p>Don't have an account? <a href="{{ route('student.register') }}">Register here.</a></p>
                <p style="margin-top: 5px;">Are you an administrator? <a href="{{ route('admin.login') }}" style="color: #007bff; font-weight: bold;">Log in as Admin here.</a></p>
            </div>
        </div>
        <!-- Login div ends here -->
    </form>

    <script>
        function fillDemoStudent() {
            document.getElementById('student_id').value = 'AC2023-00521';
            document.getElementById('password').value = '12345678';
        }
    </script>
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
