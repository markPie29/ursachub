<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="icon/2.png" type="image/x-icon">
    <title>URSAC HUB</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="icon" type="image/x-icon" href="/img/icon.png">
    
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script defer src="/script/script.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

</head>

<div class="admin-sidebar">
    <h1 class="logo">URSAC Hub</h1>
    
    <a href="{{ route('admin.account') }}">Account</a>
    <a href="{{ route('admin.products') }}">Products</a>
    <a href="{{ route('admin.news') }}">News</a>

    <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn">Logout</button>
    </form>

</div>

@yield('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


</html>

