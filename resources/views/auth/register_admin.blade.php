@extends('layouts.login_reg_layout')

@section('content')
<div class="registration-container">
    <form method="POST" action="{{ route('admin.register') }}" class="registration-form">
        @csrf

        <h2>Admin Registration</h2 class="text-1-admin">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" style="text-transform:uppercase" required>
        </div>
        <div class="form-group">
            <label for="org">Organization</label>
            <select class="form-control" id="org" name="org" required>
                <option value="" disabled selected>Select an organization</option>
                @foreach ($organizations as $organization)
                    <option value="{{ $organization }}">{{ $organization }}</option>
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
        <a href="#" onclick="showPasswordwC()" class="show-password">Show Password</a>
        <button type="submit" class="btn btn-primary">Register</button>
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
