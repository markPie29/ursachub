function showPasswordForm() {
    document.getElementById('initial-form').style.display = 'none';
    document.getElementById('password-form').style.display = 'block';
}


   // Prevent default form submission and handle the transition
   function showPasswordForm(event) {
    event.preventDefault();  // Prevent form submission

    // Validate the initial form inputs
    const studentNumber = document.getElementById('student-number').value;
    const lastName = document.getElementById('last-name').value;
    const firstName = document.getElementById('first-name').value;
    const middleName = document.getElementById('middle-name').value;

    // Check if all fields are filled
    if (studentNumber && lastName && firstName && middleName) {
        // Hide the initial form
        document.getElementById('initial-form').style.display = 'none';
        // Show the password form
        document.getElementById('password-form').style.display = 'block';
    } else {
        alert("Please fill in all the required fields.");
    }
}


// Sidebar toggle
const menuIcon = document.getElementById('menu-icon');
const sidebar = document.getElementById('sidebar');
const closeBtn = document.getElementById('close-btn');

// Open sidebar when menu icon is clicked
menuIcon.addEventListener('click', () => {
    sidebar.classList.add('sidebar-open');
});

// Close sidebar when the close button is clicked
closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('sidebar-open');
});


const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        console.log(entry)
        if (entry.isIntersecting) {
            entry.target.classList.add('show');
        } else {
            entry.target.classList.remove('show');
        }

    });
});

const hiddenElements = document.querySelectorAll('.hidden');
hiddenElements.forEach((el) => observer.observe(el))

// Wait for the entire page to load
window.addEventListener('load', () => {
    // Hide the loading screen
    const loadingScreen = document.getElementById('loading-screen');
    loadingScreen.style.opacity = 0;
    loadingScreen.style.transition = 'opacity 0.5s ease';
  
    // Remove loading screen from the DOM after transition
    setTimeout(() => {
      loadingScreen.style.display = 'none';
      document.body.style.overflow = 'auto'; // Enable scrolling
      document.getElementById('content').style.display = 'block'; // Show main content
    }, 500); // Match the CSS transition duration
  });
  
  function showPassword() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

function showPasswordwC() {
    var x = document.getElementById("password");
    var y = document.getElementById("password_confirmation");
    if (x.type === "password" && y.type === "password") {
        x.type = "text";
        y.type = "text";
    } else {
        x.type = "password";
        y.type = "password";
    }
}

function showPasswordwCN() {
    var x = document.getElementById("password");
    var y = document.getElementById("password_confirmation");
    var z = document.getElementById("current_password");

    if (x.type === "password" && y.type === "password" && z.type === "password") {
        x.type = "text";
        y.type = "text";
        z.type = "text";
    } else {
        x.type = "password";
        y.type = "password";
        z.type = "password";
    }
}

document.querySelectorAll('.slide').forEach(slide => {
    const totalSlides = 5; // Total number of slides
    const currentSlide = parseInt(slide.getAttribute('data-slide'));

    // Left area click: Go to the previous slide
    slide.querySelector('.click-area.left').addEventListener('click', () => {
        const prevSlide = currentSlide === 1 ? totalSlides : currentSlide - 1;
        document.getElementById(`slide${prevSlide}`).checked = true;
    });

    // Right area click: Go to the next slide
    slide.querySelector('.click-area.right').addEventListener('click', () => {
        const nextSlide = currentSlide === totalSlides ? 1 : currentSlide + 1;
        document.getElementById(`slide${nextSlide}`).checked = true;
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form'); // Target your form
    const loadingSpinner = document.getElementById('loading-spinner');

    form.addEventListener('submit', function () {
        loadingSpinner.style.display = 'flex'; // Show the spinner
    });
});
