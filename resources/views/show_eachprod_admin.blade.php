@extends('layouts.admin_layout')

@section('content')

<div class="admin-content-wrapper">
    <!-- Sidebar is assumed to be handled by admin_layout -->
    <div class="content">
        <section class="filler-div"></section>
        
        <div class="product-details-container">
            <div class="product-header">
                <h2>{{ $product->name }}</h2>
            </div>

            <div class="product-details">
                <h3>Stock Availability</h3>
                <ul class="stock-list">
                    <li>
                        <strong>Small:</strong> 
                        {{ $product->small }}
                        <form action="{{ route('edit_stock', ['id' => $product->id, 'size' => 'small']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="number" name="small" value="{{ $product->small }}" min="0">
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirmEdit()">Edit</button>
                        </form>
                    </li>
                    <li>
                        <strong>Medium:</strong> 
                        {{ $product->medium }}
                        <form action="{{ route('edit_stock', ['id' => $product->id, 'size' => 'medium']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="number" name="medium" value="{{ $product->medium }}" min="0">
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirmEdit()">Edit</button>
                        </form>
                    </li>
                    <li>
                        <strong>Large:</strong> 
                        {{ $product->large }}
                        <form action="{{ route('edit_stock', ['id' => $product->id, 'size' => 'large']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="number" name="large" value="{{ $product->large }}" min="0">
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirmEdit()">Edit</button>
                        </form>
                    </li>
                    <li>
                        <strong>Extra Large:</strong> 
                        {{ $product->extralarge }}
                        <form action="{{ route('edit_stock', ['id' => $product->id, 'size' => 'extralarge']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="number" name="extralarge" value="{{ $product->extralarge }}" min="0">
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirmEdit()">Edit</button>
                        </form>
                    </li>
                    <li>
                        <strong>2XL:</strong> 
                        {{ $product->double_extralarge }}
                        <form action="{{ route('edit_stock', ['id' => $product->id, 'size' => 'double_extralarge']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="number" name="double_extralarge" value="{{ $product->double_extralarge }}" min="0">
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirmEdit()">Edit</button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="product-images">
                <h3>Product Images</h3>
                @php
                $photos = json_decode($product->photos, true); // Decode the JSON column to an array
                @endphp
                @if($photos)
                    <div class="image-gallery">
                        @foreach($photos as $photo)
                            <img src="{{ asset('storage/' . $photo) }}" alt="Product Image">
                        @endforeach
                    </div>
                @else
                    <p>No images available.</p>
                @endif
            </div>

            <div class="product-actions">
                <p><strong>Price:</strong> ${{ $product->price }}</p>
            </div>

            <div class="course-restrictions">
                <h3>Allowed Courses</h3>
                @if(session('edit_mode'))
                    <form action="{{ route('update_restrictions', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            @foreach($courses as $course)
                                <div>
                                    <input type="checkbox" name="allowed_courses[]" value="{{ $course->id }}" 
                                    {{ $product->courses->contains($course->id) ? 'checked' : '' }}>
                                    <label>{{ $course->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary">Update Restrictions</button>
                    </form>
                @else
                    @if($product->courses->count())
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
</div>

<script>
    function confirmEdit() {
        return confirm('Are you sure you want to edit the stock quantity?');
    }
</script>

@endsection
