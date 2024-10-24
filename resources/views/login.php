@extends('layouts.admin_layout')
    <body>
        <div class="container">
            <div class="box form-box">
                <form action="login-script.php" method="POST">

                    <div class="logo-login">
                        <p>URSAC Hub</p>
                    </div>

                    <div class="field input">
                        <label for="student-number">Student Number</label> 

                        <input 
                        type="text"
                        name="student-number"
                        id="student-number"
                        placeholder="Student Number"
                        autocomplete="off"
                        required>
                    </div>

                    <div class="field input">
                        <label for="password">Password</label>

                        <input 
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Password"
                        autocomplete="off">
                    </div>
                    
                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Login" required>
                    </div>

                    <div class="links">
                        Don't have account? <a href="register">Sign Up Now</a>
                    </div>

                </form>
            </div>
        </div>
    </body>