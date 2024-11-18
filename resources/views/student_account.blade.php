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
        </div>
    </div>
</body>
@endsection