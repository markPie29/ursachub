@extends('layouts.layout')

@section('content')

<section class="filler-div">

</section>

<body>
    <div class="freedomwall-ctn">
        <h1>Freedom Wall</h1>
            <div class="addPosts">
                <form action="{{ route('posts.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Content Input -->
                    <div>
                        <textarea maxlength="200" id="content" name="content" resize="none" rows="4" required placeholder="What's on your mind?"></textarea>
                    </div>

                    <div class="anonymous-div">
                        <label for="anonymous">
                            <input type="checkbox" id="anonymous" name="anonymous">
                            Post Anonymously
                        </label>
                    </div>

                    <!-- Multiple Image Input -->
                    <div>
                        <!-- Hidden File Input -->
                        <input type="file" id="photos" name="photos[]" accept="image/*" multiple style="display: none;" onchange="displaySelectedImages(this)">
                        
                        <!-- Icon Button -->
                        <!-- <label class="add-image-icon"for="photos" style="cursor: pointer;">
                            <span><i class="bx bx-image"></i> Add an image</span>
                            <div>
                            <button type="submit" class="btn">Create Post</button>
                            </div>
                        </label> -->

                        <div>
                            <button type="submit" class="btn">Create Post</button>
                        </div>
                        
                        <div id="photo-preview-container" style="margin-top: 10px;"></div>
                    </div>
                </form>
            </div>

        <div class="account-posts">
            @if ($posts->isEmpty())
                <p>You haven't posted anything yet.</p>
            @else

                @foreach ($posts as $post)
                    @if ($post->anon != 'anon') 
                        <div class="account-post">
                            <div class="posts-info">
                                <h3>{{ $post->user->first_name }} {{ $post->user->last_name }}</h3>
                                <p>Posted on: {{ $post->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                                    

                            <div class="post">
                                <p>{{ $post->content }}</p>
                                
                                @php
                                    $photos = json_decode($post->image, true);  // Decode the JSON-encoded image array
                                @endphp

                                @if(is_array($photos) && count($photos) > 0)
                                    <div class="post-images">
                                        @foreach($photos as $photo)
                                        <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $photo) }}" alt="Post Photo">
                                        </a>
                                        
                                        @endforeach
                                    </div>
                                @else
                                    
                                @endif
                        
                            </div>
                        </div>
                    @else 
                        <div class="account-post-anon">
                            <div class="posts-info-anon">
                                <h3>Anonymous</h3>
                                <p>Posted on: {{ $post->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                                    

                            <div class="post">
                                <p>{{ $post->content }}</p>
                                
                                @php
                                    $photos = json_decode($post->image, true);  // Decode the JSON-encoded image array
                                @endphp

                                @if(is_array($photos) && count($photos) > 0)
                                    <div class="post-images">
                                        @foreach($photos as $photo)
                                        <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $photo) }}" alt="Post Photo">
                                        </a>
                                        
                                        @endforeach
                                    </div>
                                @else
                                    
                                @endif
                        
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
        
</body>

<script>

</script>
@endsection
