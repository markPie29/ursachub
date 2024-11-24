@extends('layouts.admin_layout')

@section('content')
<div class="admin-main">
    <div class="unique-news-container">
        <div class="unique-news-row">
            <div class="unique-news-col-md-6">
                <!-- Editable form -->
                <form id="unique-news-edit-form" action="{{ route('editNews', $news->id) }}" method="POST" enctype="multipart/form-data" style="display:none;">
                    @csrf
                    @method('PUT')
                    <div class="unique-news-form-group">
                        <label for="headline">Headline:</label>
                        <input type="text" id="headline" name="headline" value="{{ $news->headline }}" class="unique-news-form-control">
                    </div>
                    <div class="unique-news-form-group">
                        <label for="org">Organization:</label>
                        <input type="text" id="org" name="org" value="{{ $news->org }}" class="unique-news-form-control">
                    </div>
                    <div class="unique-news-form-group">
                        <label for="content">Content:</label>
                        <textarea id="content" name="content" class="unique-news-form-control">{{ $news->content }}</textarea>
                    </div>
                    
                    <!-- Current Photos with Delete Options -->
                    <div class="unique-news-current-photos">
                        <label>Current Photos:</label><br>
                        @php
                            $photos = json_decode($news->photos, true);
                        @endphp
                        @if(is_array($photos) && count($photos) > 0)
                            @foreach($photos as $photo)
                                <div class="unique-news-photo-item">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Current Photo" class="unique-news-photo-preview">
                                    <label>
                                        <input type="checkbox" name="remove_photos[]" value="{{ $photo }}"> Remove
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <p>No images available</p>
                        @endif
                    </div>

                    <div class="unique-news-form-group">
                        <label for="photos">Upload New Photos:</label>
                        <input type="file" name="photos[]" multiple>
                    </div>
                    <button type="submit" class="unique-news-btn unique-news-btn-success">Save</button>
                    <button type="button" class="unique-news-btn unique-news-btn-secondary" onclick="cancelEdit()">Back</button>
                </form>

                <!-- Non-editable view -->
                <div id="unique-news-view-mode">
                    <div class="admin-news-info">
                        <h2 id="unique-news-display-headline">{{ $news->headline }}</h2>
                        <h3 id="unique-news-display-org">{{ $news->org }}</h3>
                        <h4 id="unique-news-display-content">{{ $news->updated_at }}</h4>
                        <p id="unique-news-display-content">{{ $news->content }}</p>
                   
                    </div>

                    <!-- New Carousel Structure -->
                    <div class="admin-news-photos">
                        <div class="product-col-md-6 image-carousel">
                            @php
                                $photos = json_decode($news->photos, true);
                            @endphp

                            @if(is_array($photos) && count($photos) > 0)
                                <div class="carousel">
                                    @foreach($photos as $index => $photo)
                                        <img src="{{ asset('storage/' . $photo) }}" alt="Product Photo" class="product-image" id="image-{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                                    @endforeach
                                </div>
                                <button class="carousel-button left" onclick="showPreviousImage()">&#10094;</button>
                                <button class="carousel-button right" onclick="showNextImage()">&#10095;</button>
                            @else
                                <p class="product-no-image">No images available</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div id="admin-news-buttons">
                    <button class="unique-news-btn unique-news-btn-primary" id="unique-news-edit-button" onclick="enableEdit()">Edit</button>
                    <button type="button" class="unique-news-btn unique-news-btn-secondary" onclick="goBack()">Back</button>

                    <form action="{{ route('delete_news', $news->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="unique-news-btn unique-news-btn-danger" onclick="return confirm('Are you sure you want to delete this news?')">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function enableEdit() {
        document.getElementById('unique-news-view-mode').style.display = 'none'; // Hide the non-editable view
        document.getElementById('admin-news-buttons').style.display = 'none'; // Hide the non-editable view
        document.getElementById('unique-news-edit-form').style.display = 'block'; // Show the editable form
    }

    function goBack() {
        window.history.back();
    }

    function cancelEdit() {
        document.getElementById('admin-news-buttons').style.display = 'block';
        document.getElementById('unique-news-edit-form').style.display = 'none'; // Hide the editable form
        document.getElementById('unique-news-view-mode').style.display = 'grid'; // Show the non-editable view
    }

    let currentIndex = 0;
    const images = document.querySelectorAll('.product-image');

    function showImage(index) {
        images.forEach((img, idx) => {
            img.style.display = idx === index ? 'block' : 'none';
        });
    }

    function showNextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage(currentIndex);
    }

    function showPreviousImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showImage(currentIndex);
    }

    // Customizable Error Message Function
    function showError(message) {
        alert(message);
    }
</script>

@endsection
