@extends('layouts.admin_layout')

@section('content')
    <div class="add-product-container">
        <h1 class="add-product-title">Add New Product</h1>

        @if($errors->any())
            <div class="add-product-alert">
                <ul class="add-product-errors">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.addprod') }}" method="POST" enctype="multipart/form-data" class="add-product-form" id="addProductForm">
            @csrf
            <div class="add-product-row">
                <!-- Left Side: Product Details -->
                <div class="add-product-left">
                    <div class="add-product-field">
                        <label for="name" class="add-product-label">Product Name:</label>
                        <input type="text" name="name" id="name" class="add-product-input" value="{{ old('name') }}" required>
                    </div>

                    <div class="add-product-field"> 
                        <label for="org" class="add-product-label">Organization:</label>
                        <strong class="add-product-organization">- {{ $org }} -</strong>
                    </div>

                    <label for="courses" class="add-product-label">Available for Courses:</label>
                    <div id="courses" class="add-product-courses">
                        @foreach($courses as $course)
                            <div class="add-product-course-item">
                                <input class="add-product-checkbox" type="checkbox" name="course_ids[]" value="{{ $course->id }}" id="course_{{ $course->id }}">
                                <label for="course_{{ $course->id }}">{{ $course->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="add-product-field">
                        <label for="price" class="add-product-label">Price:</label>
                        <input type="number" name="price" id="price" class="add-product-input" step="0.01" value="{{ old('price') }}" required>
                    </div>
                </div>

                <!-- Right Side: Stock Sizes -->
                <div class="add-product-right">
                    <h3 class="add-product-subtitle">Enter Stocks for Sizes:</h3>
                    @foreach(['small', 'medium', 'large', 'extralarge', 'double_extralarge'] as $size)
                        <div class="add-product-size">
                            <label for="{{ $size }}" class="add-product-label">{{ ucfirst($size) }}:</label>
                            <input type="number" name="{{ $size }}" id="{{ $size }}" class="add-product-input" value="{{ old($size) }}" min="0" required placeholder="Enter stocks for {{ ucfirst($size) }}">
                        </div>
                    @endforeach

                    <div class="add-product-field">
                        <label for="photos" class="add-product-label">Photos (max 5):</label>
                        <input type="file" name="photos[]" id="photos" class="add-product-input" multiple accept="image/*" onchange="handleProductImageUpload(event)">
                        <div id="imagePreview" class="add-product-preview"></div>
                    </div>
                </div>
            </div>

            <button type="submit" class="add-product-button">Add Product</button>
        </form>
    </div>

    <script>
        let selectedFiles = [];

        function handleProductImageUpload(event) {
            const files = Array.from(event.target.files);
            const preview = document.getElementById('imagePreview');
            const maxFiles = 5;

            preview.innerHTML = ''; // Clear previous previews
            selectedFiles = [];

            files.forEach((file, index) => {
                if (index < maxFiles && file.type.startsWith('image/')) {
                    selectedFiles.push(file);

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const imageContainer = document.createElement('div');
                        imageContainer.classList.add('image-container');

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = file.name;
                        img.style.maxWidth = '150px';
                        img.style.maxHeight = '150px';

                        const removeButton = document.createElement('button');
                        removeButton.textContent = 'Remove';
                        removeButton.classList.add('remove-button');
                        removeButton.onclick = function () {
                            removeProductImage(index);
                        };

                        imageContainer.appendChild(img);
                        imageContainer.appendChild(removeButton);
                        preview.appendChild(imageContainer);
                    };
                    reader.readAsDataURL(file);
                }
            });

            updateFileInput();
        }

        function removeProductImage(index) {
            selectedFiles.splice(index, 1);
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            selectedFiles.forEach((file, i) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imageContainer = document.createElement('div');
                    imageContainer.classList.add('image-container');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = file.name;
                    img.style.maxWidth = '150px';
                    img.style.maxHeight = '150px';

                    const removeButton = document.createElement('button');
                    removeButton.textContent = 'Remove';
                    removeButton.classList.add('remove-button');
                    removeButton.onclick = function () {
                        removeProductImage(i);
                    };

                    imageContainer.appendChild(img);
                    imageContainer.appendChild(removeButton);
                    preview.appendChild(imageContainer);
                };
                reader.readAsDataURL(file);
            });

            updateFileInput();
        }

        function updateFileInput() {
            const fileInput = document.getElementById('photos');
            if (selectedFiles.length > 0) {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });
                fileInput.files = dataTransfer.files;
            } else {
                fileInput.value = '';
            }
        }
    </script>
@endsection