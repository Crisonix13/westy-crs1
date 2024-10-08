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
        margin: 0;
        padding: 0;
    }

    .container {
        background-color: #F5F5DC; /* Ivory */
        padding: 20px;
        border-radius: 8px;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .input-group {
        margin-bottom: 15px;
    }

    .form-control {
        border-radius: 50px;
        padding: 10px 15px;
        box-shadow: none;
        border: 1px solid #2C6B6F; /* Sage Green */
        width: 100%; /* Make input fields span the full width */
    }

    .input-group-text {
        background-color: #2C6B6F; /* Sage Green */
        color: #FFFFFF; /* White */
        border: none;
        border-radius: 50px;
    }

    label {
        color: #2C6B6F; /* Sage Green */
    }

    .btn-secondary {
        background-color: #253E23; /* Dark Green */
        border-color: #253E23; /* Dark Green */
        border-radius: 50px;
        padding: 10px 20px;
    }

    .btn-secondary:hover {
        background-color: #1E3120; /* Slightly darker green on hover */
        border-color: #1E3120;
    }

    .form-check-label {
        color: #2C6B6F; /* Sage Green */
    }

    a.link-secondary {
        color: #2C6B6F; /* Sage Green */
    }

    a.link-secondary:hover {
        color: #1E3120; /* Darker Green */
    }

    h1.fw-bold {
        color: #253E23; /* Dark Green */
    }

    h1.fw-normal {
        color: #2C6B6F; /* Sage Green */
    }

    #passwordError {
        color: #D9534F; /* Red */
    }
</style>
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="container w-50">
        <div class="d-flex justify-content-center">
            <h1 class="fw-bold my-3 me-2">User</h1> 
            <h1 class="fw-normal my-3">Registration</h1>
        </div>
        <div class="card">
            <form id="registrationForm" action="functions.php" class="mx-3 my-3"method="post" autocomplete="off" enctype="multipart/form-data">
                <div class="row my-2">
                    <div class="col-md-3">
                        <label class="fw-bold" for="firstName">First Name</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input class="form-control" type="text" name="firstName" placeholder="First Name" required>
                            <span class="input-group-text">
                                <i class="fa-solid fa-user"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-3">
                        <label class="fw-bold" for="lastName">Last Name</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input class="form-control" type="text" name="lastName" placeholder="Last Name" required>
                            <span class="input-group-text">
                                <i class="fa-solid fa-user"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-3">
                        <label class="fw-bold" for="email">Email</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input class="form-control" type="email" name="email" placeholder="Email" required>
                            <span class="input-group-text">
                                <i class="fa-regular fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-3">
                        <label class="fw-bold" for="mobileNumber">Mobile Number</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input class="form-control" type="number" name="mobileNumber" placeholder="Mobile Number (09xxxxxxxxx)" required>
                            <span class="input-group-text">
                                <i class="fa-solid fa-mobile-screen-button"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-3">
                        <label class="fw-bold" for="password">Password</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input class="form-control" type="password" name="password" placeholder="Password" id="password" required>
                            <span class="input-group-text">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-3">
                        <label class="fw-bold" for="confirm_password">Confirm Password</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input class="form-control" type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" required>
                            <span class="input-group-text">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                        </div>
                        <div id="passwordError" class="text-danger mt-2" style="display: none;">Passwords do not match.</div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-6">
                        <label class="fw-bold" for="governmentID">Government ID</label>
                        <div class="input-group">
                            <input class="form-control" type="file" name="governmentID" placeholder="Government ID" id="government_id" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold" for="companyID">Company ID</label>
                        <div class="input-group">
                            <input class="form-control" type="file" name="companyID" placeholder="Company ID" id="company_id" required>
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-12 d-flex align-items-center justify-content-between">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="termsCheckbox" required>
                            <label class="form-check-label" for="termsCheckbox"> Accept Terms and Conditions</label>
                        </div>
                        <button type="submit" class="btn btn-secondary" name="register" id="registerButton" disabled>
                            Register
                        </button>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small>Already have an account? <a class="link-secondary" href="login">Sign in</a></small>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const termsCheckbox = document.getElementById('termsCheckbox');
    const registerButton = document.getElementById('registerButton');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const errorDiv = document.getElementById('passwordError');

    function validatePasswords() {
        if (confirmPasswordInput.value === "") {
            errorDiv.style.display = 'none'; 
        } else if (passwordInput.value !== confirmPasswordInput.value) {
            errorDiv.style.display = 'block'; 
        } else {
            errorDiv.style.display = 'none'; 
        }
        checkFormValidity();
    }

    function checkFormValidity() {
        if (termsCheckbox.checked && passwordInput.value === confirmPasswordInput.value) {
            registerButton.disabled = false;
        } else {
            registerButton.disabled = true;
        }
    }

    termsCheckbox.addEventListener('change', checkFormValidity);
    passwordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);

    registerButton.disabled = true;
</script>
