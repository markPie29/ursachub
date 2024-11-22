@extends('layouts.admin_layout')

@section('content')
    <div class="publish-news-container">
        <div class="publish-news-header">
            <h3>Publish News</h3>
        </div>

        {{-- Display validation errors --}}
        @if($errors->any())
            <div class="publish-news-alert">
                <ul class="publish-news-errors">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- News Form --}}
        <form action="{{ route('admin.addnews') }}" method="POST" enctype="multipart/form-data" class="publish-news-form" id="publishNewsForm">
            @csrf

            <div class="publish-news-field">
                <label for="org" class="publish-news-label">Organization:</label>
                <span class="add-product-organization">{{ $org }}</span>
            </div>

            <div class="publish-news-field">
                <label for="headline" class="publish-news-label">News / Headline:</label>
                <input type="text" name="headline" id="headline" class="publish-news-input" required>
            </div>

            <div class="publish-news-field">
                <label for="content" class="publish-news-label">Content:</label>
                <textarea name="content" id="content" rows="5" class="publish-news-textarea" required></textarea>
            </div>

            <div class="publish-news-field">
                <label for="photos" class="publish-news-label">Photos (max 5):</label>
                <input type="file" name="photos[]" id="newsPhotos" class="publish-news-input" multiple accept="image/*" onchange="handleNewsImageUpload(event)">
                <div id="newsImagePreview" class="publish-news-preview"></div>
            </div>

            <button type="submit" class="publish-news-button">Publish News</button>
        </form>
    </div>

    <script>
        let newsSelectedFiles = [];

        function handleNewsImageUpload(event) {
            const files = Array.from(event.target.files);
            const preview = document.getElementById('newsImagePreview');
            const maxFiles = 5;

            preview.innerHTML = ''; // Clear previous preview
            newsSelectedFiles = []; // Reset selected files

            files.forEach((file, index) => {
                if (index < maxFiles && file.type.startsWith('image/')) {
                    newsSelectedFiles.push(file);

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
                            removeNewsImage(index);
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

        function removeNewsImage(index) {
            newsSelectedFiles.splice(index, 1);
            updatePreview();
            updateFileInput();
        }

        function updatePreview() {
            const preview = document.getElementById('newsImagePreview');
            preview.innerHTML = '';

            newsSelectedFiles.forEach((file, i) => {
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
                        removeNewsImage(i);
                    };

                    imageContainer.appendChild(img);
                    imageContainer.appendChild(removeButton);
                    preview.appendChild(imageContainer);
                };
                reader.readAsDataURL(file);
            });
        }

        function updateFileInput() {
            const fileInput = document.getElementById('newsPhotos');
            const dataTransfer = new DataTransfer();
            
            newsSelectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            
            fileInput.files = dataTransfer.files;
        }
    </script>
@endsection