@extends('layouts.layout')

@section('content')

<section class="filler-div">

</section>

<body>  
    <div class="account-ctn">
        <div class="main-details">
            <h2> {{ $lastname }}, {{ $firstname }} {{ $middlename }} </h2>
            <h2>{{ $student_id }}</h2>
            <p><strong>Course:</strong> {{ $course->name }}</p>
            <a href="{{ route('student.orders') }}">View My Orders</a>
        </div>
    </div>
</body>
@endsection