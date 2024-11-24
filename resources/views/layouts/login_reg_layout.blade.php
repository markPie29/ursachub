<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="icon/2.png" type="image/x-icon">
    <title>URSAC HUB</title>
    <link rel="stylesheet" href="/css/main.css">
    
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script defer src="/script/script.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

</head>

<div id="loading-screen">
    <div class="spinner"></div>
    <h1 class="logo-loading">URSAC Hub</h1>
</div>
@yield('content')

<script>
    // Wait until the page and all resources have fully loaded
    window.addEventListener('load', () => {
        const loadingScreen = document.getElementById('loading-screen');
        
        // Hide the loading screen with a fade-out effect
        loadingScreen.style.transition = 'opacity 0.5s ease';
        loadingScreen.style.opacity = '0';

        setTimeout(() => {
            loadingScreen.style.display = 'none'; // Remove it from view
            document.body.classList.remove('hidden'); // Allow scrolling
        }, 500); // Match the transition duration
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


</html>

