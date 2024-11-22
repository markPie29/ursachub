@extends('layouts.layout')

@section('content')

<section class="filler-div">

</section>

    <div class="profile-section">
        <!-- First Design -->
  <div class="profile-card card-style-1">
    <div class="icon">
      <!-- Static User Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="60" height="60" fill="#1d4ed8">
        <circle cx="12" cy="8" r="4"></circle>
        <path d="M12 14c-5 0-8 2.5-8 5v1h16v-1c0-2.5-3-5-8-5z"></path>
      </svg>
    </div>

    <div class="info">
        <div class="filler">
            <h1> {{ $lastname }}, {{ $firstname }} {{ $middlename }} </h1>
        </div>
        <div class="filler">
            <h2> {{ $course->name }}</h2>
        </div>
        <div class="filler">
            <p>{{ $student_id }}</p>
        </div>
    </div>
    <a href="{{ route('student.orders') }}" class="orders-link">View My Orders</a>
  </div>
</div>

@endsection