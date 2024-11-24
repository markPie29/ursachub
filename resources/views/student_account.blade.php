@extends('layouts.layout')

@section('content')

<section class="filler-div">

</section>

<div class="profile-section">
  <div class="profile-card">
    <div class="profile-icon">
      <i class='bx bxs-user-circle'></i>
    </div>

    <div class="info">
      <p> {{ $course->name }}</p>
      
        <div class="filler">
            <h2> {{ $lastname }}, {{ $firstname }} {{ $middlename }} </h2>
            <h3>{{ $student_id }}</h3>
        </div>
    </div>

    <a href="{{ route('student.orders') }}" class="orders-link">View My Orders</a>
  </div>
</div>

@endsection