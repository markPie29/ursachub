<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="icon/2.png" type="image/x-icon">
    <title>MarkIsulat</title>
    <link rel="stylesheet" href="/css/main.css">
    
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script defer src="/script/script.js"></script>
</head>

<header>
        <div>
            <a href="{{ url('/home') }}" class="logo">URSAC Hub</a>
        </div>

        <div>
            <ul class="navbar">
                <li><a href="{{ url('/news_page') }}">News</a></li>
                <li><a href="{{ url('/products_page') }}">Products</a></li>
                <li><a href="{{ url('/faqs_page') }}">FAQs</a></li>
            </ul>
        </div>

        <div class="menu-icon-div">
            <h1 class="bx bx-menu" id="menu-icon" href=""></h1>
        </div>

   

        </div>

        <!-- <ul class="navbar-icons">
            <li><a href="#home"><i class='bx bxs-home'></i></a></li>
            <li><a href="about.html"><i class='bx bxs-user'></i></a></li>
            <li><a href="designs.html"><i class='bx bxs-palette'></i></a></li>
            <li><a href="softwares.html"><i class='bx bx-laptop'></i></a></li>
        </ul> -->
    </header>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <i class='bx bx-x' id="close-btn"></i> <!-- Close button -->
        <div class="sidebar-header">
            <h2>Account & Product Details</h2>
        </div>
        <ul>
            <li><a href="account.php"><i class='bx bx-user'></i> Account</a></li>
            <li><a href="products.php"><i class='bx bx-box'></i> Products</a></li>
        </ul>
    </div>

@yield('content')
</html>
