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
        <form action="/admin/addnews" method="POST" enctype="multipart/form-data" class="publish-news-form">
            @csrf
            
            <div class="publish-news-field">
                <label for="org" class="publish-news-label">Organization:</label>
                <input type="text" name="org" id="org" class="publish-news-input" required>
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
                <label for="photos" class="publish-news-label">Photos (min 1, max 5):</label>
                <input type="file" name="photos[]" id="photos" class="publish-news-input" required multiple accept="image/*">
            </div>
            
            <button type="submit" class="publish-news-button">Publish News</button>
        </form>

    </div>
@endsection
