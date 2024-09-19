<?php
    session_start();

    if (isset($_SESSION['user_logged_in'])) {
        header('Location: index');
        exit();
    }

    require 'components/header.php';
    include 'components/navbar.php';
?>

<style>
    body {
        background-color: #E6F0F1; /* Light Sage Green */
        font-family: 'Arial', sans-serif;
        color: #2C6B6F; /* Sage Green */
        margin: 0;
        padding: 0;
    }

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #D8E2DC; /* Light Coral */
    }

    .card {
        background-color: #FFFFFF; /* White */
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        max-width: 450px;
        width: 100%;
        padding: 20px;
        transition: transform 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card img {
        max-width: 200px; /* Set a max width for the image */
        height: auto; /* Maintain the aspect ratio */
        margin-bottom: 20px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .card h1 {
        color: #253E23; /* Dark Forest Green */
        font-size: 1.8rem;
        margin-bottom: 20px;
        text-align: center;
    }

    .input-group {
        margin-bottom: 15px;
    }

    .form-control {
        border-radius: 50px;
        padding: 10px 15px;
        box-shadow: none;
        border: 1px solid #2C6B6F;
    }

    .input-group-text {
        background-color: #FFFFFF; /* White */
        border: 1px solid #2C6B6F; /* Sage Green */
        border-radius: 50px;
    }

    .btn-primary {
        background-color: #253E23; /* Dark Forest Green */
        border-color: #253E23;
        color: #FFFFFF; /* White */
        width: 100%;
        border-radius: 50px;
        padding: 10px 20px;
        font-size: 1rem;
        transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    .btn-primary:hover {
        background-color: #1D4F51; /* Darker Forest Green */
        transform: translateY(-3px);
    }

    .link-secondary {
        color: #2C6B6F; /* Sage Green */
        text-decoration: none;
    }

    .link-secondary:hover {
        text-decoration: underline;
    }
</style>

<div class="login-container">
    <div class="card">
        <img src="assets/logo.png" alt="Westy Transporter Logo">

        <form action="functions.php" method="post">
            <div class="input-group">
                <input class="form-control" type="email" name="email" placeholder="Email" required>
                <span class="input-group-text">
                    <i class="fa-regular fa-envelope"></i>
                </span>
            </div>
            <div class="input-group">
                <input class="form-control" type="password" name="password" placeholder="Password" required>
                <span class="input-group-text">
                    <i class="fa-solid fa-lock"></i>
                </span>
            </div>
            <button type="submit" class="btn btn-primary" name="login">Sign in</button>
        </form>
        <div class="text-center mt-3">
            <small>Don't have an account? <a class="link-secondary" href="registration">Register</a></small>
        </div>
    </div>
</div>
