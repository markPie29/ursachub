@extends('layouts.admin_layout')

@section('content')
<body> 
    <div class ="admin-main">

        <div class="admin-main-details">
            @if ($admin->logo)
                <div class="admin-logo">
                    <img src="{{ asset('storage/' . $admin->logo) }}" alt="{{ $admin->org }} Logo" class="logo">
                </div>
            @endif
            <h1>{{ $admin->org }}</h1>   
        </div>

    <div class="account-addbtn-ctn">
        <a href="{{ route('admin.orders') }}"> <div class="main-button"><i class='bx bx-list-ul'></i> Track Orders<i class='bx bx-plus'></i></div></a>
        <form action="{{ route('upload.logo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="profile_photo">Upload Logo:</label>
            <input type="file" name="profile_photo" id="profile_photo" required>
            <button type="submit">Upload</button>
        </form>
    </div>


    </div>

    
</body>
@endsection
