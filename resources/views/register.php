@extends('layouts.admin_layout')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="icon/2.png" type="image/x-icon">
    <title>MarkIsulat</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@600&display=swap" rel="stylesheet">

    <!-- JavaScript to handle form navigation -->
    <script>
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
    </script>
</head>
<body>
    <div class="container">
        <div class="box form-box">

            <div class="logo-login">
                <p>URSAC Hub</p>
            </div>

            <form id="initial-form" action="" method="post">
                <div class="field input">
                    <label for="student-number">Student Number</label>
                    <input type="text" name="student-number" id="student-number" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="last-name">Last Name</label>
                    <input type="text" name="last-name" id="last-name" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="first-name">First Name</label>
                    <input type="text" name="first-name" id="first-name" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="middle-name">Middle Name</label>
                    <input type="text" name="middle-name" id="middle-name" autocomplete="off" required>
                </div>

                <div class="field">
                    <!-- Button to trigger form transition -->
                    <input type="submit" class="btn" value="Next" onclick="showPasswordForm(event)">
                </div>
                <div class="links">
                    Already have an account? <a href="login.php">Log In</a>
                </div>
            </form>

            <!-- Password form that appears after clicking 'Next' -->
            <form id="password-form" action="submit.php" method="post" style="display:none;">
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" name="confirm-password" id="confirm-password" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Submit">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
