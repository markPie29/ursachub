@extends('layouts.admin_layout')

@section('content')
<section class="filler-div">

</section>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <ul>
                <li>Stocks (per size): </li>
                <li>
                    Small - {{ $product->small }}
                    <form action="{{ route('edit_stock', ['id' => $product->id, 'size' => 'small']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="number" name="small" value="{{ $product->small }}" min="0" style="width: 60px;">
                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirmEdit()">Edit</button>
                    </form>
                </li>
                <li>
                    Medium - {{ $product->medium }}
                    <form action="{{ route('edit_stock', ['id' => $product->id, 'size' => 'medium']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="number" name="medium" value="{{ $product->medium }}" min="0" style="width: 60px;">
                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirmEdit()">Edit</button>
                    </form>
                </li>
                <li>
                    Large - {{ $product->large }}
                    <form action="{{ route('edit_stock', ['id' => $product->id, 'size' => 'large']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="number" name="large" value="{{ $product->large }}" min="0" style="width: 60px;">
                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirmEdit()">Edit</button>
                    </form>
                </li>
                <li>
                    Extra Large - {{ $product->extralarge }}
                    <form action="{{ route('edit_stock', ['id' => $product->id, 'size' => 'extralarge']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="number" name="extralarge" value="{{ $product->extralarge }}" min="0" style="width: 60px;">
                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirmEdit()">Edit</button>
                    </form>
                </li>
                <li>
                    2 Extra Large - {{ $product->double_extralarge }}
                    <form action="{{ route('edit_stock', ['id' => $product->id, 'size' => 'double_extralarge']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="number" name="double_extralarge" value="{{ $product->double_extralarge }}" min="0" style="width: 60px;">
                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirmEdit()">Edit</button>
                    </form>
                </li>
            </ul>

            <p><strong>Price:</strong> ${{ $product->price }}</p>
            @php
                $photos = json_decode($product->photos, true); // Decode the JSON column to an array
            @endphp

            @if(is_array($photos) && count($photos) > 0)
                @foreach($photos as $photo)
                    <img src="{{ asset('storage/' . $photo) }}" alt="Product Photo" style="max-width: 100%; height: auto;">
                @endforeach
            @else
                <p>No images available</p>
            @endif

            <form action="{{ route('delete_prod', $product->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
            </form>

            <!-- Display allowed courses -->
            <h3 class="mt-4">Allowed Courses</h3>
            @if(session('edit_mode'))
                <!-- Edit Mode -->
                <form action="{{ route('update_restrictions', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Select Courses:</label>
                        <div>
                            @foreach($courses as $course)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                        name="allowed_courses[]" 
                                        value="{{ $course->id }}" 
                                        id="course_{{ $course->id }}"
                                        {{ $product->courses->contains($course->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="course_{{ $course->id }}">
                                        {{ $course->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Update Restrictions</button>
                </form>
            @else
                <!-- View Mode -->
                @if($product->courses->count() > 0)
                    @foreach($product->courses as $course)
                        <p>{{ $course->name }}</p>
                    @endforeach
                @else
                    <p>No courses allowed for this product.</p>
                @endif
                <form action="{{ route('toggle_edit_mode', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Update Restrictions</button>
                </form>
            @endif


        </div>
    </div>
</div>

<script>
    function confirmEdit() {
        return confirm('Are you sure you want to edit the stock quantity?');
    }
</script>

@endsection
