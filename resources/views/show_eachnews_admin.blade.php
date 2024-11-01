@extends('layouts.admin_layout')

@section('content')
<section class="filler-div">

</section>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <!-- Editable form -->
            <form id="editForm" action="{{ route('editNews', $news->id) }}" method="POST" enctype="multipart/form-data" style="display:none;">
                @csrf
                @method('PUT')
                <div>
                    <label for="headline">Headline:</label>
                    <input type="text" id="headline" name="headline" value="{{ $news->headline }}" class="form-control">
                </div>
                <div>
                    <label for="org">Organization:</label>
                    <input type="text" id="org" name="org" value="{{ $news->org }}" class="form-control">
                </div>
                <div>
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" class="form-control">{{ $news->content }}</textarea>
                </div>
                
                <!-- Current Photos with Delete Options -->
                <div>
                    <label>Current Photos:</label><br>
                    @php
                        $photos = json_decode($news->photos, true);
                    @endphp
                    @if(is_array($photos) && count($photos) > 0)
                        @foreach($photos as $photo)
                            <div>
                                <img src="{{ asset('storage/' . $photo) }}" alt="Current Photo" style="max-width: 100px; height: auto;">
                                <label>
                                    <input type="checkbox" name="remove_photos[]" value="{{ $photo }}"> Remove
                                </label>
                            </div>
                        @endforeach
                    @else
                        <p>No images available</p>
                    @endif
                </div>

                <div>
                    <label for="photos">Upload New Photos:</label>
                    <input type="file" name="photos[]" multiple>
                </div>
                <button type="submit" class="btn btn-success">Save</button>
            </form>

            <!-- Non-editable view -->
            <div id="viewMode">
                <h2 id="displayHeadline">{{ $news->headline }}</h2>
                <p id="displayOrg">{{ $news->org }}</p>
                <p id="displayContent">{{ $news->content }}</p>
                @if(is_array($photos) && count($photos) > 0)
                    @foreach($photos as $photo)
                        <img src="{{ asset('storage/' . $photo) }}" alt="Product Photo" style="max-width: 100%; height: auto;">
                    @endforeach
                @else
                    <p>No images available</p>
                @endif

                <!-- Edit Button -->
                <button class="btn btn-primary" id="editButton" onclick="enableEdit()">Edit</button>

                <form action="{{ route('delete_news', $news->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this news?')">Delete</button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    function enableEdit() {
        document.getElementById('viewMode').style.display = 'none'; // Hide the non-editable view
        document.getElementById('editForm').style.display = 'block'; // Show the editable form
    }
</script>
@endsection
