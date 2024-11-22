@extends('layouts.admin_layout')

@section('content')
<div class="admin-container">
    
    <form method="POST" action="{{ route('admin.login') }}" class="admin-login">
        @csrf

        <!-- Login div starts here -->
        <h1>Welcome Admin!</h1>

        <!-- Name Field -->
        <div class="form-group inputBx">
            <label for="name">Name</label>
            <input 
                type="text" 
                class="form-control" 
                id="name" 
                name="name" 
                placeholder="Enter your name" 
                style="text-transform:uppercase"
                required>
        </div>

        <!-- Organization Field -->
        <div class="form-group inputBx">
            <label for="org">Organization</label>
            <select class="form-control" id="org" name="org" required>
                <option value="" disabled selected>Select an organization</option>
                @foreach ($organizations as $organization)
                    <option value="{{ $organization }}">{{ $organization }}</option>
                @endforeach
            </select>
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
                style="text-transform:uppercase"
                required>
        </div>

        <!-- Login Button -->
        <div class="inputBx">
            <button type="submit" class="btn btn-primary">Log In</button>
        </div>

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
