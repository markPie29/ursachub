@extends('layouts.admin_layout')

@section('content')
<body>  
    <div class ="admin-main">
        <div class='admin-prod-news-header '>
            <h1>News</h1>

            <div style = "display:flex;">
                <a href="{{ route('addnewspage') }}"> 
                    <div class="btn" >
                        <i class='bx bxs-news'></i> Add News <i class='bx bx-plus'></i>
                    </div> 
                </a>
            </div>
        </div>


        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-events">
            <div class="add-event-modal">
                <h1>Add an Event</h1>
                    <form action="{{ route('add_event') }}" method="POST">
                        @csrf <!-- CSRF token for security -->

                        <!-- Event Name -->
                        <label for="event-name">Event Name:</label>
                        <input maxlength="25" type="text" id="event-name" name="event_name" placeholder="Enter event name" required>

                        <label for="event-venue">Event Venue:</label>
                        <input maxlength="25" type="text" id="event-venue" name="event_venue" placeholder="Enter event venue" required>

                        <!-- Event Date and Time -->
                        <label for="event-date-time">Event Date and Time:</label>
                        <input type="datetime-local" id="event-date-time" name="event_date_time" required>

                        <!-- Buttons -->
                        <button type="submit">Add Event</button>
                    </form>
            </div>

            <div class="admin-each-events">
                <h1>Events</h1>
                <h2>{{ $admin->org }}</h2>
                <!-- Check if there are any events -->
                @if($events->isNotEmpty())
                    @foreach($events as $event)

                    @php
                        $eventDate = \Carbon\Carbon::parse($event->date)->toDateString(); // Extract only the date part
                        $todayDate = \Carbon\Carbon::now()->toDateString(); // Today's date
                    @endphp

                        <div class="event-item">

                            <div class="admin-event-ctn">
                                <div class="event-item-profile">
                                    <div class="news-logo">
                                        <img src="{{ asset('storage/' . $admin->logo) }}" alt="{{ $admin->org }} Logo" class="logo">
                                    </div>
                                    
                                    <div class="event-item-info">
                                        <h3>{{ $event->name }}</h3>
                                        <p>{{ \Carbon\Carbon::parse($event->date)->format('F j, Y, g:i A') }}</p> 
                                        <p>{{ $event->venue }}</p>
                                   
                                    </div>
                                </div>
                                @if($eventDate === $todayDate)
                                    <div class="event-item-status today">
                                        <span>Today!</span>
                                    </div>
                                @elseif($eventDate > $todayDate)
                                    <div class="event-item-status upcoming">
                                        <span>Upcoming!</span>
                                    </div>
                                @else
                                    <div class="event-item-status passed">
                                        <span>Finished</span>
                                    </div>
                                @endif
                            </div>



                            <div class="event-item-btns">
                                <button class="remove-event eventbtn" data-modal="remove-event-modal-{{ $event->id }}">Remove</button>
                                <button class="edit-event eventbtn" data-modal="edit-event-modal-{{ $event->id }}">Edit</button>
                            </div>  

                            <div id="remove-event-modal-{{ $event->id }}" class="modal remove-event-modal">
                                <div class="remove-event-modal-content">
                                    <p>Do you want to remove this event?</p>
                                    <form id="delete-event-form-{{ $event->id }}" action="{{ route('delete_event', $event->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" id="remove">Remove</button>
                                    </form>
                                    <button class="close" id="close">Close</button>
                                </div>
                            </div>

                            <div id="edit-event-modal-{{ $event->id }}" class="modal edit-event-modal">
                                <div class="edit-event-modal-content">
                                    <h1>Edit Event</h1>
                                    <form action="{{ route('edit_event', $event->id) }}" method="POST">
                                        @csrf <!-- CSRF token for security -->
                                        @method('PUT') <!-- Use PUT method for updating data -->

                                        <!-- Event Name -->
                                        <label for="event-name">Event Name:</label>
                                        <input 
                                            maxlength="25" 
                                            type="text" 
                                            id="event-name" 
                                            name="event_name" 
                                            placeholder="Enter event name" 
                                            value = "{{ $event->name }}"
                                            required>

                                        <label for="event-venue">Event Venue:</label>
                                        <input 
                                            maxlength="25" 
                                            type="text" 
                                            id="event-venue" 
                                            name="event_venue" 
                                            placeholder="Enter event venue" 
                                            value = "{{ $event->venue }}"
                                            required>

                                        <!-- Event Date and Time -->
                                        <label for="event-date-time">Event Date and Time:</label>
                                        <input 
                                            type="datetime-local" 
                                            id="event-date-time" 
                                            name="event_date_time" 
                                            value="{{ \Carbon\Carbon::parse($event->date)->format('Y-m-d\TH:i') }}"
                                            required>

                                        <!-- Buttons -->
                                        <button type="submit" id="edit">Edit Event</button>
                                    </form>
                                    <button class="close" id="close">Back</button>
                                </div>
                            </div>

                        </div>
                    
                    @endforeach
                @else
                    <p>No events available.</p>
                @endif
            </div>

        </div>




        <div class="news-container">
        @foreach($news as $newsx)
        <a href="{{ route('show_eachnewspage_admin', $newsx->id) }}" class="news-card">
            <!-- News Content at the Top -->
            <div class="news-card-content">
                <p class="news-org">{{ $newsx->org }}</p>
                <p class="news-timestamp">{{ $newsx->updated_at }}</p>
                <h3 class="news-headline" id = "news-headline"> {{ $newsx->headline }} </h3>
            </div>

            <!-- Image at the Bottom -->
            <div class="news-card-image">
                @php
                    // Decode the JSON-encoded image URLs
                    $images = json_decode($newsx->photos, true);
                @endphp

                @if(is_array($images) && count($images) > 0)
                    <!-- Display the first image in the array -->
                    <img src="{{ asset('storage/' . $images[0]) }}" alt="News Preview">
                @else
                    <!-- Fallback if no images are available -->
                    <p>No images available</p>
                @endif
            </div>
        </a>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        <div class="pagination">
            <!-- Previous Button -->
            @if ($news->onFirstPage())
                <span class="disabled">Previous</span>
            @else
                <a href="{{ $news->previousPageUrl() }}" class="page-link">Previous</a>
            @endif

            <!-- Page Numbers with Limit of 5 -->
            @if ($news->lastPage() <= 5)
                @for ($i = 1; $i <= $news->lastPage(); $i++)
                    @if ($i == $news->currentPage())
                        <span class="current-page">{{ $i }}</span>
                    @else
                        <a href="{{ $news->url($i) }}" class="page-link">{{ $i }}</a>
                    @endif
                @endfor
            @else
                <a href="{{ $news->url(1) }}" class="page-link">1</a>

                @if ($news->currentPage() > 3)
                    <span class="ellipsis">...</span>
                @endif

                @for ($i = max(2, $news->currentPage() - 1); $i <= min($news->lastPage() - 1, $news->currentPage() + 1); $i++)
                    @if ($i == $news->currentPage())
                        <span class="current-page">{{ $i }}</span>
                    @else
                        <a href="{{ $news->url($i) }}" class="page-link">{{ $i }}</a>
                    @endif
                @endfor

                @if ($news->currentPage() < $news->lastPage() - 2)
                    <span class="ellipsis">...</span>
                @endif

                <a href="{{ $news->url($news->lastPage()) }}" class="page-link">{{ $news->lastPage() }}</a>
            @endif

            <!-- Next Button -->
            @if ($news->hasMorePages())
                <a href="{{ $news->nextPageUrl() }}" class="page-link">Next</a>
            @else
                <span class="disabled">Next</span>
            @endif
        </div>
    </div>

    </div>
</body>

<script>




    // var edt_modal = document.getElementById("edit-event-modal");
    // var edt_btn = document.getElementById("edit-event-modal-btn");
    // var closeEdit = document.getElementById("close-edit-modal");

    // closeEdit.onclick = function() {
    //     edt_modal.style.display = "none";
    // };


    // edt_btn.onclick = function() {
    //     edt_modal.style.display = "block";
    // }



    document.addEventListener("DOMContentLoaded", () => {
        const headlines = document.querySelectorAll(".news-headline");
        const maxChars = 80; // Limit to 30 characters

        headlines.forEach((headline) => {
        if (headline.textContent.length > maxChars) {
            headline.textContent = headline.textContent.substring(0, maxChars) + "...";
        }
        });

        // var edt_modal = document.getElementById("edit-event-modal");
        // var edt_btn = document.getElementById("edit-event-modal-btn");
        // var closeEdit = document.getElementById("close-edit-modal");

        // edt_btn.onclick = function() {
        //     edt_modal.style.display = "block";
        // }

        // closeEdit.onclick = function() {
        //     edt_modal.style.display = "none";
        // };

        // var rmv_modal = document.getElementById("remove-event-modal");
        // var rmv_btn = document.getElementById("remove-event-modal-btn");
        // var closeRemove = document.getElementById("close-remove-modal");

        // rmv_btn.onclick = function() {
        //     rmv_modal.style.display = "block";
        // }

        // closeRemove.onclick = function() {
        //     rmv_modal.style.display = "none";
        // };

        // window.onclick = function(event) {
        //     if (event.target == rmv_modal) {
        //         rmv_modal.style.display = "none";
        //     }
        //     if (event.target == edt_modal) {
        //         edt_modal.style.display = "none";
        //     }
        // };
    });

    
    document.addEventListener("DOMContentLoaded", () => {
        // Open Edit Modal
        document.querySelectorAll(".edit-event").forEach(btn => {
            btn.addEventListener("click", function () {
                const modalId = this.dataset.modal; // Get modal ID from data attribute
                const modal = document.getElementById(modalId);
                modal.style.display = "block";
            });
        });

        // Open Remove Modal
        document.querySelectorAll(".remove-event").forEach(btn => {
            btn.addEventListener("click", function () {
                const modalId = this.dataset.modal; // Get modal ID from data attribute
                const modal = document.getElementById(modalId);
                modal.style.display = "block";
            });
        });

        // Close Modal Logic
        document.querySelectorAll(".close").forEach(closeBtn => {
            closeBtn.addEventListener("click", function () {
                const modal = this.closest(".modal"); // Find the parent modal
                modal.style.display = "none";
            });
        });

        // Close Modal on Outside Click
        // window.onclick = function (event) {
        //     if (event.target.classList.contains("modal")) {
        //         event.target.style.display = "none";
        //     }
        // };
    });

</script>
@endsection
