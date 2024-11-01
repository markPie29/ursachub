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
        </div>
    </div>
</div>

<script>
    function confirmEdit() {
        return confirm('Are you sure you want to edit the stock quantity?');
    }
</script>

@endsection
