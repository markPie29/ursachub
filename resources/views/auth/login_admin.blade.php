@extends('layouts.login_reg_layout')

@section('content')
<div class="admin-container">
    
    <form method="POST" action="{{ route('admin.login') }}" class="admin-login">
        @csrf

        <div class="welcome">
            <h1>Welcome Admin!</h1>
        </div>

        <!-- Name Field -->
        <div class="form-group inputBx">
            <label for="name" class="label-registration">Name</label>
            <input 
                type="text" 
                class="form-control" 
                id="name" 
                name="name" 
                placeholder="Name" 
                style="text-transform:uppercase"
                autocomplete="username"
                required>
        </div>

        <!-- Organization Field -->
        <div class="form-group inputBx">
            <label for="org" class="label-registration">Organization</label>
            <select class="form-control" id="org" name="org" required>
                <option value="" disabled selected>Select an organization</option>
                @foreach ($organizations as $organization)
                    <option value="{{ $organization }}">{{ $organization }}</option>
                @endforeach
            </select>
        </div>

        <!-- Password Field -->
        <div class="form-group inputBx">
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
            <a href="#" onclick=showPassword() class="show-password">Show Password</a>
        </div>

        <!-- Login Button & Demo Actions -->
        <div class="inputBx" style="display: flex; flex-direction: column; gap: 10px; margin-top: 15px;">
            <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
            <button type="button" class="btn btn-secondary" onclick="fillDemoAdmin()" style="background-color: #6c757d; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; width: 100%; font-weight: 500;">⚡ Auto-fill Admin Credentials (Demo)</button>
        </div>

        <div class="register-prompt" style="margin-top: 15px; text-align: center;">
            <p>Looking for student login? <a href="{{ route('student.login') }}" style="color: #007bff; font-weight: bold;">Log in as Student here.</a></p>
        </div>

    </form>

    <script>
        function fillDemoAdmin() {
            document.getElementById('name').value = 'COENGSC';
            document.getElementById('org').value = 'College of Engineering - Student Council';
            document.getElementById('password').value = '12345678';
        }
    </script>

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

