@extends('layouts.admin_layout')

@section('content')
<div class="admin-content-wrapper">
    <div class="content">
        <section class="filler-div"></section>
        
        <div class="product-details-container">
            <div class="product-header">
                <strong>Product Details:</strong>
                <h1>{{ $product->name }}</h1>
            </div>

            <div class="product-details">
                <h3>Stock Availability</h3>
                <ul class="stock-list">
                    <li><strong>Small:</strong> {{ $product->small }}</li>
                    <li><strong>Medium:</strong> {{ $product->medium }}</li>
                    <li><strong>Large:</strong> {{ $product->large }}</li>
                    <li><strong>Extra Large:</strong> {{ $product->extralarge }}</li>
                    <li><strong>2XL:</strong> {{ $product->double_extralarge }}</li>
                </ul>
                <button class="btn btn-primary" id="editStocksButton">Edit Stocks</button>
            </div>

            <!-- Edit Stocks Modal -->
            <div class="modal-overlay" id="editStocksModal" style="display: none;">
                <div class="modal-box">
                    <div class="modal-header">
                        <strong>Edit Stocks</strong>
                        <button class="close-modal" id="closeEditStocksModal">&times;</button>
                    </div>
                    <form action="{{ route('update_stocks', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="small">Small:</label>
                                <input type="number" id="small" name="small" value="{{ $product->small }}" min="0" required>
                            </div>
                            <div class="form-group">
                                <label for="medium">Medium:</label>
                                <input type="number" id="medium" name="medium" value="{{ $product->medium }}" min="0" required>
                            </div>
                            <div class="form-group">
                                <label for="large">Large:</label>
                                <input type="number" id="large" name="large" value="{{ $product->large }}" min="0" required>
                            </div>
                            <div class="form-group">
                                <label for="extralarge">Extra Large:</label>
                                <input type="number" id="extralarge" name="extralarge" value="{{ $product->extralarge }}" min="0" required>
                            </div>
                            <div class="form-group">
                                <label for="double_extralarge">2XL:</label>
                                <input type="number" id="double_extralarge" name="double_extralarge" value="{{ $product->double_extralarge }}" min="0" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="product-images">
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

            <div class="admin-price">
                <p><strong>Price:</strong> ₱{{ $product->price }}</p>
            </div>

            <div class="course-restrictions">
                <h3>Allowed Courses</h3>
                <ul>
                    @if($product->courses->count())
                        @foreach($product->courses as $course)
                            <li>{{ $course->name }}</li>
                        @endforeach
                    @else
                        <li>No courses allowed for this product.</li>
                    @endif
                </ul>
                <button class="btn btn-primary" id="editRestrictionsButton">Update Restrictions</button>
            </div>

            <!-- Update Restrictions Modal -->
            <div class="modal-overlay" id="editRestrictionsModal" style="display: none;">
                <div class="modal-box">
                    <div class="modal-header">
                        <strong>Update Restrictions</strong>
                        <button class="close-modal" id="closeEditRestrictionsModal">&times;</button>
                    </div>
                    <form action="{{ route('update_restrictions', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div>
                                @foreach($courses as $course)
                                    <div class="select-program">
                                        <input class="program-checkbox" type="checkbox" id="course_{{ $course->id }}" name="allowed_courses[]" value="{{ $course->id }}" 
                                        {{ $product->courses->contains($course->id) ? 'checked' : '' }}>
                                        <label for="course_{{ $course->id }}">{{ $course->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Edit Stocks Modal
    document.getElementById('editStocksButton').addEventListener('click', function () {
        document.getElementById('editStocksModal').style.display = 'flex';
    });

    document.getElementById('closeEditStocksModal').addEventListener('click', function () {
        document.getElementById('editStocksModal').style.display = 'none';
    });

    // Update Restrictions Modal
    document.getElementById('editRestrictionsButton').addEventListener('click', function () {
        document.getElementById('editRestrictionsModal').style.display = 'flex';
    });

    document.getElementById('closeEditRestrictionsModal').addEventListener('click', function () {
        document.getElementById('editRestrictionsModal').style.display = 'none';
    });
</script>

@endsection
