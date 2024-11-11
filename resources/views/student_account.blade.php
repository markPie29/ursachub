@extends('layouts.layout')

@section('content')

<body>  
    <div class="account-ctn">

        <section class="filler-div">

        </section>

        <div class="main-details">
            <h2> {{ $lastname }}, {{ $firstname }} {{ $middlename }} </h2>
            <h2>{{ $student_id }}</h2>
            <p><strong>Course:</strong> {{ $course->name }}</p>
        </div>


    
       
    </div>
</body>
@endsection