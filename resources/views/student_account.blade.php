@extends('layouts.layout')

@section('content')

<section class="filler-div">
</section>

<div class="profile-section">
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
</script>


@endsection