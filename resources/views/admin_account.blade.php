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
            <a href="{{ $admin->fb_link }}" target="_blank">{{ $admin->org }}</a> 
        </div>

        <div class="edit-buttons">

            <form  class="upload-logo-form" action="{{ route('upload.logo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="profile_photo">Upload Logo:</label>
                <input type="file" name="profile_photo" id="profile_photo" required>
                <button type="submit">Upload</button>
                <p class="upload-note">
                    Note: The logo should be 1080 x 1080 pixels and has no background.
                </p>
            </form>

            <div class="modal-buttons">
                <button class="open-modal">Edit Account</button>
            </div>

        </div>




        {{-- Button to Open Modal --}}

        <div class="modal" id="editModal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h2>Edit Profile</h2>

                {{-- Tabs for Switching Between Forms --}}
                <div class="tab-buttons">
                    <button class="tab-link active btn" data-tab="editNameTab">Edit Name</button>
                    <button class="tab-link btn" data-tab="editFBLinkTab">Edit FB Link</button>
                    <button class="tab-link btn" data-tab="editPasswordTab">Edit Password</button>
                    <button class="tab-link btn" data-tab="editGCashTab">Edit GCash</button>
                </div>

                {{-- Tab Content --}}
                <div class="tab-content active" id="editNameTab">
                    <form action="{{ route('admin.update_name') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" value="{{ $admin->name }}" required>
                        <button class="btn" type="submit">Update Name</button>
                    </form>
                </div>

                <div class="tab-content" id="editFBLinkTab">
                    <form action="{{ route('admin.update_fb_link') }}" method="POST">
                        @csrf
                        @method('POST')
                        <label for="name">FB Link:</label>
                        <input type="text" name="fb_link" id="fb_link" value="{{ $admin->fb_link }}" required>
                        <button class="btn" type="submit">Update FB Link</button>
                    </form>
                </div>
                
                <div class="tab-content" id="editPasswordTab">
                    <form action="{{ route('admin.update_password') }}" method="POST">
                        @csrf
                        @method('POST')  <!-- Use POST method since we’re updating the resource -->
                        
                        <!-- Current Password -->
                        <label for="current_password">Current Password:</label>
                        <input type="password" name="current_password" id="current_password" required>
                        
                        <!-- New Password -->
                        <label for="password">New Password:</label>
                        <input type="password" name="password" id="password" required>
                        
                        <!-- Confirm New Password -->
                        <label for="password_confirmation">Confirm New Password:</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>

                        <a href="#" onclick=showPasswordwCN() class="show-password">Show Password</a>

                        <button class="btn" type="submit">Update Password</button>
                    </form>
                </div>

                <div class="tab-content" id="editGCashTab">
                    <form action="{{ route('admin.update_gcash') }}" method="POST">
                        @csrf
                        @method('POST')  <!-- Use POST method since we’re updating the resource -->
                        
                        <!-- Current Password -->
                        <label for="gcash_name">GCash Name: </label>
                        <input type="text" name="gcash_name" id="gcash_name" value="{{ $admin->gcash_name }}" required>
                        
                        <!-- New Password -->
                        <label for="gcash_number">GCash Number: </label>
                        <input type="text" name="gcash_number" id="gcash_number" value="{{ $admin->gcash_number }}" required>
                        
                        <button class="btn" type="submit">Update GCash Details</button>
                    </form>
                </div>

            </div>
        </div>

        <div class="account-addbtn-ctn">
            <a href="{{ route('admin.orders') }}"> <div class="main-button"><i class='bx bx-list-ul'></i>Track Orders</div></a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('editModal');
            const openModalButton = document.querySelector('.open-modal');
            const closeModalButton = modal.querySelector('.close-modal');
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            // Open modal
            openModalButton.addEventListener('click', () => {
                modal.style.display = 'block';
            });

            // Close modal
            closeModalButton.addEventListener('click', () => {
                modal.style.display = 'none';
            });

            // Tab switching functionality
            tabLinks.forEach(link => {
                link.addEventListener('click', () => {
                    tabLinks.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    link.classList.add('active');
                    document.getElementById(link.getAttribute('data-tab')).classList.add('active');
                });
            });
        });
    </script>
</body>


@endsection
