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
                <span>Note: The first image will be the thumbnail.</span>
                <input 
                    type="file" 
                    name="photos[]" 
                    id="newsPhotos" 
                    class="publish-news-input" 
                    multiple 
                    accept="image/*" 
                    onchange="handleNewsImageUpload(event)"
                >
                <div id="newsImagePreview" class="publish-news-preview"></div>
            </div>


            <button type="submit" class="publish-news-button">Publish News</button>
        </form>
    </div>

    <script>
    let newsSelectedFiles = []; // To store uploaded files

    function handleNewsImageUpload(event) {
        const files = Array.from(event.target.files); // Convert FileList to an array
        const maxFiles = 5;

        // Add files while ensuring no duplicates and max limit is maintained
        files.forEach(file => {
            if (newsSelectedFiles.length < maxFiles && !newsSelectedFiles.some(f => f.name === file.name)) {
                newsSelectedFiles.push(file);
            }
        });

        renderNewsPreview(); // Update preview
        updateNewsFileInput(); // Sync the file input
    }

    function renderNewsPreview() {
        const preview = document.getElementById('newsImagePreview');
        preview.innerHTML = ''; // Clear existing previews

        newsSelectedFiles.forEach((file, index) => {
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

                // Add a "Thumbnail" label to the first image
                if (index === 0) {
                    const thumbnailLabel = document.createElement('span');
                    thumbnailLabel.textContent = 'Thumbnail';
                    thumbnailLabel.classList.add('thumbnail-label');
                    imageContainer.appendChild(thumbnailLabel);
                }

                imageContainer.appendChild(removeButton);
                preview.appendChild(imageContainer);
            };

            reader.readAsDataURL(file); // Read the file to generate preview
        });
    }

    function removeNewsImage(index) {

        newsSelectedFiles.splice(index, 1); // Remove the selected image
        renderNewsPreview(); // Update the preview
        updateNewsFileInput(); // Sync the file input
    }

    function updateNewsFileInput() {
        const fileInput = document.getElementById('newsPhotos');
        const dataTransfer = new DataTransfer();

        newsSelectedFiles.forEach(file => {
            dataTransfer.items.add(file); // Add each file to the DataTransfer
        });

        fileInput.files = dataTransfer.files; // Update the input's files property
    }
</script>

@endsection