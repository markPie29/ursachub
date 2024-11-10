@extends('layouts.admin_layout')

@section('content')
<div class="admin-container">
    <h2>Welcome, Admin!</h2>
    
    <form method="POST" action="{{ route('admin.login') }}" class="admin-login">
        @csrf

        <!-- Login div starts here -->
        <h3>Login</h3>

        <!-- Name Field -->
        <div class="form-group inputBx">
            <label for="name">Name</label>
            <input 
                type="text" 
                class="form-control" 
                id="name" 
                name="name" 
                placeholder="Enter your name" 
                required>
        </div>

        <!-- Organization Field -->
        <div class="form-group inputBx">
            <label for="org">Organization</label>
            <input 
                type="text" 
                class="form-control" 
                id="org" 
                name="org" 
                placeholder="Enter your organization" 
                required>
        </div>

        <!-- Password Field -->
        <div class="form-group inputBx">
            <label for="password">Password</label>
            <input 
                type="password" 
                class="form-control" 
                id="password" 
                name="password" 
                placeholder="Enter your password" 
                required>
        </div>

        <!-- Login Button -->
        <div class="inputBx">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>

        <!-- Links for "Forgot Password" and Registration -->
        <div class="links">
            <a href="#">Forget Password</a>
            <a href="{{ route('admin.register') }}">Sign Up Now</a>
        </div>
        <!-- Login div ends here -->
    </form>

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
