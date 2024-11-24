@extends('layouts.admin_layout')

@section('content')
<section class="unique-news-filler-div">

</section>

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
                <h2 id="unique-news-display-headline">{{ $news->headline }}</h2>
                <p id="unique-news-display-org">{{ $news->org }}</p>
                <p id="unique-news-display-content">{{ $news->content }}</p>
                @if(is_array($photos) && count($photos) > 0)
                    @foreach($photos as $photo)
                        <img src="{{ asset('storage/' . $photo) }}" alt="Product Photo" class="unique-news-full-photo-preview">
                    @endforeach
                @else
                    <p>No images available</p>
                @endif

                <!-- Edit Button -->
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

<script>
    function enableEdit() {
        document.getElementById('unique-news-view-mode').style.display = 'none'; // Hide the non-editable view
        document.getElementById('unique-news-edit-form').style.display = 'block'; // Show the editable form
    }

    function goBack() {
        window.history.back();
    }

    function cancelEdit() {
        document.getElementById('unique-news-edit-form').style.display = 'none'; // Hide the editable form
        document.getElementById('unique-news-view-mode').style.display = 'block'; // Show the non-editable view
    }
</script>
@endsection
