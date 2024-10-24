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
