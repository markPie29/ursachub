@extends('layouts.layout')

@section('content')

<section class="filler-div">
</section>

<div class="profile-section">
  <div class="profile-card-section">
    <div class="profile-card hidden">
      <div class="profile-icon">
        <i class='bx bxs-user-circle'></i>
      </div>

      <div class="info">
        <p>{{ $course->name }}</p>

        <div class="filler">
          <h2>{{ $lastname }}, {{ $firstname }} {{ $middlename }}</h2>
          <h3>{{ $student_id }}</h3>
        </div>
      </div>

      <a href="{{ route('student.orders') }}" class="orders-link">View My Orders</a>

      <!-- Button to open the modal -->
      <div class="modal-buttons">
          <button class="open-modal">Edit Password</button>
      </div>

    </div>
  </div>


  <div class="account-posts-section hidden">
    <h1>My Wall</h1>
      <div class="addPosts">
      <form action="{{ route('posts.create') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Content Input -->
        <div>
            <textarea id="content" name="content" resize="none" rows="4" required placeholder="What's on your mind?"></textarea>
        </div>

        <div>
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

        <!-- Submit Button -->
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
</div>

<div class="modal" id="editModal">
      <div class="modal-content">
          <span class="close-modal">&times;</span>
          <h2>Edit Password</h2>

          {{-- Tabs for Switching Between Forms --}}

          
          <div class="tab-content active" id="editPasswordTab">
              <form action="{{ route('student.update_password') }}" method="POST">
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

document.addEventListener('DOMContentLoaded', () => {
    const photoInput = document.getElementById('photos');
    const previewContainer = document.getElementById('photo-preview-container');
    let photoFiles = [];

    // Handle file input change
    photoInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);

        if (photoFiles.length + files.length > 5) {
            alert('You can upload a maximum of 5 photos.');
            return;
        }

        files.forEach((file) => {
            if (!photoFiles.includes(file)) {
                photoFiles.push(file);
                displayPhoto(file);
            }
        });

        updateInputFiles();
    });

    // Display photo preview
    const displayPhoto = (file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const div = document.createElement('div');
            div.classList.add('photo-preview');

            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview" />
                <button type="button" class="remove-photo">Remove</button>
            `;

            previewContainer.appendChild(div);

            // Add remove functionality
            div.querySelector('.remove-photo').addEventListener('click', () => {
                photoFiles = photoFiles.filter((f) => f !== file);
                div.remove();
                updateInputFiles();
            });
        };

        reader.readAsDataURL(file);
    };

    // Update input files for form submission
    const updateInputFiles = () => {
        const dataTransfer = new DataTransfer();
        photoFiles.forEach((file) => dataTransfer.items.add(file));
        photoInput.files = dataTransfer.files;
    };

    // Enable drag-and-drop reordering
    new Sortable(previewContainer, {
        animation: 150,
        onEnd: () => {
            const reorderedFiles = [];
            Array.from(previewContainer.children).forEach((div) => {
                const imgSrc = div.querySelector('img').src;
                const file = photoFiles.find((f) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(f);
                    return reader.result === imgSrc;
                });
                reorderedFiles.push(file);
            });
            photoFiles = reorderedFiles;
            updateInputFiles();
        },
    });
});







</script>


@endsection