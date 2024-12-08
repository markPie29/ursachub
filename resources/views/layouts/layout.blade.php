<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

</head>

<header class="header-main">
    <div>
        <a href="{{ route('student.home') }}" class="header-text logo">URSAC Hub</a>
        <a href="{{ route('student.home') }}" class='header-icon'><i class='bx bxs-home'></i></a>
    </div>

    <div>
        <ul class="navbar">
            <li>
                <a href="{{ route('freedomwall') }}" class="header-text" >Wall</a>
                <a href="{{ route('freedomwall') }}" class="header-icon"><i class='bx bxs-news' ></i></a>
            </li>

            <li>
                <a href="{{ route('news_page') }}" class="header-text" >News</a>
                <a href="{{ route('news_page') }}" class="header-icon"><i class='bx bxs-news' ></i></a>
            </li>
            <li>
                <a href="{{ route('products_page') }}" class="header-text">Shop</a>
                <a href="{{ route('products_page') }}" class="header-icon"><i class='bx bxs-store-alt' ></i></a>
            </li>
            <li>
                <a href="{{ route('orgs_page') }}" class="header-text">Orgs</a>
                <a href="{{ route('orgs_page') }}" class="header-icon"><i class='bx bxs-group' ></i></a>
            </li>
        </ul>
    </div>

    <div class="menu-icon-div">
        <h1 class="bx bx-menu" id="menu-icon" href=""></h1>
    </div>
</header>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <i class='bx bx-x' id="close-btn"></i> <!-- Close button -->
        <div class="sidebar-header">
            <h2>Account & Cart Details</h2>
        </div>
        <ul>
            <li><a href="{{ route('student.account') }}"><i class='bx bx-user'></i> Account</a></li>
            <li><a href="{{ route('student.cart') }}"><i class='bx bx-box'></i> Cart</a></li>
            <li>
                <form action="{{ route('student.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn">Logout</button>
                </form>
            </li>
        </ul>


    </div>


    <div id="loading-screen">
        <div class="spinner"></div>
        <h1 class="logo-loading">URSAC Hub</h1>
    </div>
@yield('content')
</html>
