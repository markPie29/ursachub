@extends('layouts.admin_layout')

@section('content')

<div class="admin-main">
    <div class="admin-content-wrapper">
        <div class="content">
            <section class="filler-div"></section>
            
            <div class="product-details-container">
                <div class="product-header">
                    <strong>Product Details:</strong>
                    <h1>{{ $product->name }}</h1>
                </div>

                <div class="details-wrapper">
                    <!-- Left Side: Details -->
                    <div class="details-left">
                        <div class="product-details">
                            <h3 class="stock-title">Stock Availability</h3>
                            <ul class="stock-list">
                                <li><strong>Small:</strong> {{ $product->small }}</li>
                                <li><strong>Medium:</strong> {{ $product->medium }}</li>
                                <li><strong>Large:</strong> {{ $product->large }}</li>
                                <li><strong>Extra Large:</strong> {{ $product->extralarge }}</li>
                                <li><strong>2XL:</strong> {{ $product->double_extralarge }}</li>
                            </ul>
                            <button class="btn btn-primary" id="editStocksButton">Edit Stocks</button>
                        </div>

                        <div class="admin-price">
                            <p><strong>Price:</strong> ₱{{ $product->price }}</p>
                            <button class="btn btn-price" id="editPriceButton">Edit Price</button>
                        </div>
                    </div>

                    <!-- Right Side: Photos -->
                    <div class="details-right">
                        <div class="product-col-md-6 image-carousel">
                            @php
                                $photos = json_decode($product->photos, true);
                            @endphp

                            @if(is_array($photos) && count($photos) > 0)
                                <div class="carousel">
                                    @foreach($photos as $index => $photo)
                                        <img src="{{ asset('storage/' . $photo) }}" alt="Product Photo" class="product-image" id="image-{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                                    @endforeach
                                </div>
                                <button class="carousel-button left" onclick="showPreviousImage()">&#10094;</button>
                                <button class="carousel-button right" onclick="showNextImage()">&#10095;</button>
                            @else
                                <p class="product-no-image">No images available</p>
                            @endif
                        </div>
                    </div>
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
                                <div class="form-group-stocks">
                                    <label for="small">Small:</label>
                                    <input type="number" id="small" name="small" value="{{ $product->small }}" min="0" required>
                                </div>
                                <div class="form-group-stocks">
                                    <label for="medium">Medium:</label>
                                    <input type="number" id="medium" name="medium" value="{{ $product->medium }}" min="0" required>
                                </div>
                                <div class="form-group-stocks">
                                    <label for="large">Large:</label>
                                    <input type="number" id="large" name="large" value="{{ $product->large }}" min="0" required>
                                </div>
                                <div class="form-group-stocks">
                                    <label for="extralarge">Extra Large:</label>
                                    <input type="number" id="extralarge" name="extralarge" value="{{ $product->extralarge }}" min="0" required>
                                </div>
                                <div class="form-group-stocks">
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

                <!-- Edit Price Modal -->
                <div class="modal-overlay" id="editPriceModal" style="display: none;">
                    <div class="modal-box">
                        <div class="modal-header">
                            <strong>Edit Price</strong>
                            <button class="close-modal" id="closeEditPriceModal">&times;</button>
                        </div>
                        <form action="{{ route('update_price', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="price">Price:</label>
                                    <input type="number" id="price" name="price" value="{{ $product->price }}" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn">Save Changes</button>
                            </div>
                        </form>
                    </div>
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
            </div>
        </div>
    </div>
</div>

<script>
    // Carousel Initialization
    let currentIndex = 0;
    const images = document.querySelectorAll('.product-image');

    function showImage(index) {
        images.forEach((img, idx) => {
            img.style.display = idx === index ? 'block' : 'none';
        });
    }

    function showNextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage(currentIndex);
    }

    function showPreviousImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showImage(currentIndex);
    }

    // Modal Scripts
    document.getElementById('editStocksButton').addEventListener('click', function () {
        document.getElementById('editStocksModal').style.display = 'flex';
    });

    document.getElementById('closeEditStocksModal').addEventListener('click', function () {
        document.getElementById('editStocksModal').style.display = 'none';
    });

    document.getElementById('editPriceButton').addEventListener('click', function () {
        document.getElementById('editPriceModal').style.display = 'flex';
    });

    document.getElementById('closeEditPriceModal').addEventListener('click', function () {
        document.getElementById('editPriceModal').style.display = 'none';
    });

    document.getElementById('editRestrictionsButton').addEventListener('click', function () {
        document.getElementById('editRestrictionsModal').style.display = 'flex';
    });

    document.getElementById('closeEditRestrictionsModal').addEventListener('click', function () {
        document.getElementById('editRestrictionsModal').style.display = 'none';
    });
</script>

@endsection