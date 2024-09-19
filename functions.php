<?php
require './class/class_user.php';
$classUser = new User;
session_start();

function handleRegistration($classUser) {
    $firstName      = $_POST['firstName'];
    $lastName       = $_POST['lastName'];
    $email          = $_POST['email'];
    $mobileNumber   = $_POST['mobileNumber'];
    $password       = $_POST['password'];
    $governmentID   = $_FILES['governmentID'];
    $companyID      = $_FILES['companyID'];

    if (empty($firstName) || empty($lastName) || empty($email) || empty($mobileNumber) || empty($password) || empty($governmentID['name']) || empty($companyID['name'])) {
        echo "<script>alert('Please fill in all fields.');window.location.href='registration';</script>";
        exit();
    }

    $governmentIDDir = 'government_id/';
    $companyIDDir = 'company_id/';

    if (!is_dir($governmentIDDir)) {
        mkdir($governmentIDDir, 0777, true);
    }
    if (!is_dir($companyIDDir)) {
        mkdir($companyIDDir, 0777, true);
    }

    $governmentIDPath = $governmentIDDir . basename($governmentID['name']);
    if (!move_uploaded_file($governmentID['tmp_name'], $governmentIDPath)) {
        echo "<script>alert('Failed to upload government ID.');window.location.href='registration';</script>";
        exit();
    }

    $companyIDPath = $companyIDDir . basename($companyID['name']);
    if (!move_uploaded_file($companyID['tmp_name'], $companyIDPath)) {
        echo "<script>alert('Failed to upload company ID.');window.location.href='registration';</script>";
        exit();
    }

    $result = $classUser->register($firstName, $lastName, $email, $mobileNumber, $password, $governmentIDPath, $companyIDPath);

    if ($result) {
        $_SESSION['registration_success'] = true;
        header("Location: thanks");
    } else {
        echo "<script>alert('Registration failed. Please try again.');window.location.href='registration';</script>";
    }
}

function handleLogin($classUser) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $result = $classUser->login($email, $password);

        if ($result) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user'] = $email;
            header("Location: index");
        } else {
            echo "<script>alert('Invalid login details. Please try again.');window.location.href='./login';</script>";
        }
    }
}

function handleAddClient($classUser) {
    $clientName                 = $_POST['clientName'];
    $clientAddress              = $_POST['clientAddress'];
    $clientTypeEstablishment    = $_POST['clientTypeEstablishment'];
    $clientContactPerson        = $_POST['clientContactPerson'];
    $clientContactNumber        = $_POST['clientContactNumber'];
    $clientEmail                = $_POST['clientEmail'];
    $clientCRS                  = $_POST['clientCRS'];
    $clientHW                   = $_POST['clientHW'];

    $result = $classUser->addClient($clientName, $clientAddress, $clientTypeEstablishment, $clientContactPerson, $clientContactNumber, $clientEmail, $clientCRS, $clientHW);

    if ($result) {
        header("Location: client");
        exit();
    } else {
        echo "<script>alert('An error occurred. Please try again.');window.location.href='./client';</script>";
    }
}

function handleClientApproval($classUser) {
    $clientID = $_POST['clientID'];
    $action = $_POST['action'];

    // Set clientStatus based on the action
    $clientStatus = ($action === 'approve') ? 'Approved' : 'Rejected';

    // Call the updateClientStatus method with clientStatus
    $result = $classUser->updateClientStatus($clientID, $clientStatus);
    
    if ($result) {
        header("Location: client.php");
    } else {
        echo "<script>alert('An error occurred. Please try again.');window.location.href='client_management.php';</script>";
    }
}


function submitApplication($classUser) {
    // General Information
    $clientID = isset($_POST['clientID']) ? htmlspecialchars($_POST['clientID']) : '';
    $managingHead = isset($_POST['managingHead']) ? htmlspecialchars($_POST['managingHead']) : '';
    $managingHeadMobNum = isset($_POST['managingHeadMobNum']) ? htmlspecialchars($_POST['managingHeadMobNum']) : '';
    $managingHeadTelNum = isset($_POST['managingHeadTelNum']) ? htmlspecialchars($_POST['managingHeadTelNum']) : '';
    $natureBusiness = isset($_POST['natureBusiness']) ? htmlspecialchars($_POST['natureBusiness']) : '';
    $psicNum = isset($_POST['psicNum']) ? htmlspecialchars($_POST['psicNum']) : '';
    $psicDesc = isset($_POST['psicDesc']) ? htmlspecialchars($_POST['psicDesc']) : '';
    $dateEstablishment = isset($_POST['dateEstablishment']) ? htmlspecialchars($_POST['dateEstablishment']) : '';
    $numEmployees = isset($_POST['numEmployees']) ? htmlspecialchars($_POST['numEmployees']) : '';

    // Pollution Control Officer Information
    $pcoName = isset($_POST['pcoName']) ? htmlspecialchars($_POST['pcoName']) : '';
    $pcoMobNum = isset($_POST['pcoMobNum']) ? htmlspecialchars($_POST['pcoMobNum']) : '';
    $pcoTelNum = isset($_POST['pcoTelNum']) ? htmlspecialchars($_POST['pcoTelNum']) : '';
    $pcoEmail = isset($_POST['pcoEmail']) ? htmlspecialchars($_POST['pcoEmail']) : '';
    $pcoAccredNo = isset($_POST['pcoAccredNo']) ? htmlspecialchars($_POST['pcoAccredNo']) : '';
    $pcoAccredDate = isset($_POST['pcoAccredDate']) ? htmlspecialchars($_POST['pcoAccredDate']) : '';

    // Facility Address
    $region = isset($_POST['region']) ? htmlspecialchars($_POST['region']) : '';
    $province = isset($_POST['province']) ? htmlspecialchars($_POST['province']) : '';
    $city = isset($_POST['city']) ? htmlspecialchars($_POST['city']) : '';
    $barangay = isset($_POST['barangay']) ? htmlspecialchars($_POST['barangay']) : '';
    $zipCode = isset($_POST['zipCode']) ? htmlspecialchars($_POST['zipCode']) : '';

    // Geolocation
    $latitude = isset($_POST['latitude']) ? htmlspecialchars($_POST['latitude']) : '';
    $longitude = isset($_POST['longitude']) ? htmlspecialchars($_POST['longitude']) : '';
    
    $result = $classUser->submitApplication($clientID, $managingHead, $managingHeadMobNum, $managingHeadTelNum, $natureBusiness, $psicNum, $psicDesc, $dateEstablishment, $numEmployees, 
                                            $pcoName, $pcoMobNum, $pcoTelNum, $pcoEmail, $pcoAccredNo, $pcoAccredDate, 
                                            $region, $province, $city, $barangay, $zipCode,
                                            $latitude, $longitude, $permits);

    if ($result) {
        header("Location: application");
        exit();
    } else {
        echo "Submission failed.";
    }
}

// Main switch statement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch (true) {
        case isset($_POST['register']):
            handleRegistration($classUser);
            break;
        case isset($_POST['login']):
            handleLogin($classUser);
            break;
        case isset($_POST['addClient']):
            handleAddClient($classUser);
            break;
        case isset($_POST['clientApproval']):
            handleClientApproval($classUser);
            break;
        case isset($_POST['finalizeApplication']):
            submitApplication($classUser);
            break;
        default:
            echo "<script>alert('Invalid action.');window.location.href='index';</script>";
            break;
    }
}
?>