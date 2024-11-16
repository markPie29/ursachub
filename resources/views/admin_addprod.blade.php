@extends('layouts.admin_layout')

@section('content')
    <div class="add-product-container">
        <h1 class="add-product-title">Add New Product</h1>

        {{-- Display validation errors --}}
        @if($errors->any())
            <div class="add-product-alert">
                <ul class="add-product-errors">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Product Form --}}
        <form action="/admin/addprod" method="POST" enctype="multipart/form-data" class="add-product-form" id="addProductForm">
            @csrf
            <div class="add-product-field">
                <label for="name" class="add-product-label">Product Name:</label>
                <input type="text" name="name" id="name" class="add-product-input" value="{{ old('name') }}" required>
            </div>

            <div class="add-product-field"> 
                <label for="org" class="add-product-label">Organization:</label>
                <span class="add-product-organization">{{ $org }}</span>
            </div>

            {{-- Course Selection using Checkboxes --}}
            <label for="courses" class="add-product-label">Available for Courses:</label>
            <div id="courses" class="add-product-courses">
                @foreach($courses as $course)
                    <div class="add-product-course-item">
                        <input type="checkbox" name="course_ids[]" value="{{ $course->id }}" id="course_{{ $course->id }}">
                        <label for="course_{{ $course->id }}">{{ $course->name }}</label>
                    </div>
                @endforeach
            </div>

            <div class="add-product-field">
                <h3 class="add-product-subtitle">Enter Stocks for Sizes:</h3>
                <div class="add-product-size">
                    <label for="small" class="add-product-label">Small:</label>
                    <input type="number" name="small" id="small" class="add-product-input" value="{{ old('small') }}" min="0" required placeholder="Enter stocks for Small">
                </div>
                <div class="add-product-size">
                    <label for="medium" class="add-product-label">Medium:</label>
                    <input type="number" name="medium" id="medium" class="add-product-input" value="{{ old('medium') }}" min="0" required placeholder="Enter stocks for Medium">
                </div>
                <div class="add-product-size">
                    <label for="large" class="add-product-label">Large:</label>
                    <input type="number" name="large" id="large" class="add-product-input" value="{{ old('large') }}" min="0" required placeholder="Enter stocks for Large">
                </div>
                <div class="add-product-size">
                    <label for="extralarge" class="add-product-label">Extra Large:</label>
                    <input type="number" name="extralarge" id="extralarge" class="add-product-input" value="{{ old('extralarge') }}" min="0" required placeholder="Enter stocks for Extra Large">
                </div>
                <div class="add-product-size">
                    <label for="double_extralarge" class="add-product-label">Double Extra Large:</label>
                    <input type="number" name="double_extralarge" id="double_extralarge" class="add-product-input" value="{{ old('double_extralarge') }}" min="0" required placeholder="Enter stocks for Double Extra Large">
                </div>
            </div>

            <div class="add-product-field">
                <label for="price" class="add-product-label">Price:</label>
                <input type="number" name="price" id="price" class="add-product-input" step="0.01" value="{{ old('price') }}" required>
            </div>

            <div class="add-product-field">
                <label for="photos" class="add-product-label">Photos (max 5):</label>
                <input type="file" name="photos[]" id="photos" class="add-product-input" multiple>
                <p id="photoSizeError" style="color: red; display: none;">The total size of uploaded photos must not exceed 20MB.</p>
            </div>
            
            <button type="submit" class="add-product-button">Add Product</button>
        </form>
    </div>

    {{-- JavaScript for Photo Size Validation --}}
    <script>
        document.getElementById('addProductForm').addEventListener('submit', function (e) {
            const files = document.getElementById('photos').files;
            let totalSize = 0;

            // Calculate total size of uploaded files
            for (let i = 0; i < files.length; i++) {
                totalSize += files[i].size; // Size in bytes
            }

            // Convert bytes to megabytes and check if it exceeds 20MB
            if (totalSize > 20 * 1024 * 1024) {
                e.preventDefault(); // Prevent form submission
                const errorElement = document.getElementById('photoSizeError');
                errorElement.style.display = 'block'; // Show error message
            }
        });
    </script>
@endsection
