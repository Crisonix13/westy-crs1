<?php
session_start();

if (!isset($_SESSION['user_logged_in'])){
    header('Location: login');
    exit();
}

require '../components/header.php';
require '../components/db.php';
include '../components/sidebar.php';
include '../components/navbar.php';

$currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;

if ($currentStep < 1) $currentStep = 1;
if ($currentStep > 5) $currentStep = 5;

$fileTypes = array();
if (isset($_SESSION['files']) && is_array($_SESSION['files'])) {
    foreach ($_SESSION['files'] as $file) {
        if (isset($file['fileType'])) {
            $fileTypes[] = $file['fileType'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRS Application</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #E6F0F1; /* Light Sage Green */
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100%;
            background-color: #2C3E50; /* Dark color for sidebar */
            z-index: 1000;
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 250px;
            width: calc(100% - 250px);
            z-index: 500;
            background-color: #34495E; /* Darker navbar color */
        }
        .container {
            margin-left: 270px; /* Ensure content starts after the sidebar */
            padding-top: 150px; /* Increase padding to avoid overlapping with navbar */
            background-color: #FFFFF0; /* Ivory */
            border-radius: 10px;
            border: 1px solid #D4B483; /* Soft Gold border */
            padding: 20px;
        }
        .form-section {
            margin-bottom: 20px;
        }
        .form-section h2 {
            font-size: 24px;
            color: #4A684A; /* Forest Green */
            margin-bottom: 10px;
        }
        .form-section .form-label {
            font-weight: bold;
        }
        .btn-custom {
            background-color: #253E23; /* Dark Green */
            color: #FFFFFF;
        }
        .btn-custom:hover {
            background-color: #1E331D; /* Darker Green */
        }
    </style>
</head>
<body>
<div class="container">
    <form action="functions.php" method="post" enctype="multipart/form-data">
        <div class="form-section">
            <h2>General Information</h2>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="clientID" class="form-label">Company</label>
                    <select class="form-control" name="clientID" id="clientID" required>
                        <option value="">Select option</option>
                        <?php
                            $clientQuery = "SELECT * FROM client WHERE isActive = 1 AND clientStatus = 'Approved'";
                            $clientResult = mysqli_query($conn, $clientQuery);
                            
                            while($row = mysqli_fetch_assoc($clientResult)) {
                                $clientID   = $row['clientID'];
                                $clientName = $row['clientName'];

                                echo "<option value=\"$clientID\">$clientName</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="managingHead" class="form-label">Managing Head</label>
                    <input class="form-control" type="text" name="managingHead" placeholder="Managing Head" required>
                </div>
                <div class="col-md-3">
                    <label for="managingHeadMobNum" class="form-label">Mobile Number</label>
                    <input class="form-control" type="text" name="managingHeadMobNum" placeholder="Mobile Number" required>
                </div>
                <div class="col-md-3">
                    <label for="managingHeadTelNum" class="form-label">Telephone Number</label>
                    <input class="form-control" type="text" name="managingHeadTelNum" placeholder="Telephone Number" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="natureBusiness" class="form-label">Nature of Business</label>
                    <input class="form-control" type="text" name="natureBusiness" placeholder="Nature of Business" required>
                </div>
                <div class="col-md-6">
                    <label for="psicNum" class="form-label">PSIC Number</label>
                    <input class="form-control" type="text" name="psicNum" placeholder="PSIC Number" required>
                </div>
                <div class="col-md-3">
                    <label for="psicDesc" class="form-label">PSIC Description</label>
                    <input class="form-control" type="text" name="psicDesc" placeholder="PSIC Description" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="dateEstablishment" class="form-label">Date of Establishment</label>
                    <input class="form-control" type="date" name="dateEstablishment" required>
                </div>
                <div class="col-md-6">
                    <label for="numEmployees" class="form-label">No. of Employees</label>
                    <input class="form-control" type="text" name="numEmployees" placeholder="No. of Employees" required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Pollution Control Officer Information</h2>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="pcoName" class="form-label">PCO Name</label>
                    <input class="form-control" type="text" name="pcoName" placeholder="Name of Pollution Control Officer" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="pcoMobNum" class="form-label">PCO Mobile Number</label>
                    <input class="form-control" type="text" name="pcoMobNum" placeholder="Mobile Number of Pollution Control Officer" required>
                </div>
                <div class="col-md-4">
                    <label for="pcoTelNum" class="form-label">PCO Telephone Number</label>
                    <input class="form-control" type="text" name="pcoTelNum" placeholder="Telephone Number of Pollution Control Officer" required>
                </div>
                <div class="col-md-4">
                    <label for="pcoEmail" class="form-label">PCO E-mail Address</label>
                    <input class="form-control" type="email" name="pcoEmail" placeholder="E-mail Address of Pollution Control Officer" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="pcoAccredNo" class="form-label">PCO Accreditation No.</label>
                    <input class="form-control" type="text" name="pcoAccredNo" placeholder="Accreditation No. of Pollution Control Officer" required>
                </div>
                <div class="col-md-6">
                    <label for="pcoAccredDate" class="form-label">PCO Date of Accreditation</label>
                    <input class="form-control" type="date" name="pcoAccredDate" required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Facility Address</h2>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="region" class="form-label">Region</label>
                    <select class="form-control" id="region" name="region" onchange="fetchProvinces(this.value)" required>
                        <option value="">Select Region</option>
                        <?php
                            $regionQuery = "SELECT * FROM refregion";
                            $regionResult = mysqli_query($conn, $regionQuery);
                            while($row = mysqli_fetch_assoc($regionResult)) {
                                echo "<option value=\"".$row['regCode']."\">".$row['regDesc']."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="province" class="form-label">Province</label>
                    <select class="form-control" id="province" name="province" onchange="fetchCities(this.value)" required>
                        <option value="">Select Province</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="city" class="form-label">City/Municipality</label>
                    <select class="form-control" id="city" name="city" onchange="fetchBarangays(this.value)" required>
                        <option value="">Select City/Municipality</option>  
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="barangay" class="form-label">Barangay</label>
                    <select class="form-control" id="barangay" name="barangay" required>
                        <option value="">Select Barangay</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="zipCode" class="form-label">Zip Code</label>
                    <input class="form-control" type="text" name="zipCode" required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Geolocation</h2>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input class="form-control" type="text" name="latitude" placeholder="Latitude coordinates" required>
                </div>
                <div class="col-md-6">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input class="form-control" type="text" name="longitude" placeholder="Longitude coordinates" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 text-end">
                    <a href="https://www.google.com/maps" target="_blank" class="btn btn-custom">
                        <i class="fa-regular fa-map me-2"></i>Open Map
                    </a>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-custom w-100" name="finalizeApplication">
            <i class="fa-solid fa-check-to-slot"></i> Finalize Application
        </button>
    </form>
</div>

<script>
function fetchProvinces(regionCode) {
    if (regionCode === "") {
        document.getElementById('province').innerHTML = '<option value="">Select Province</option>';
        document.getElementById('city').innerHTML = '<option value="">Select City/Municipality</option>';
        document.getElementById('barangay').innerHTML = '<option value="">Select Barangay</option>';
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'get_location.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            document.getElementById('province').innerHTML = this.responseText;
        }
    };
    xhr.send('regionCode=' + encodeURIComponent(regionCode));
}

function fetchCities(provCode) {
    if (provCode === "") {
        document.getElementById('city').innerHTML = '<option value="">Select City/Municipality</option>';
        document.getElementById('barangay').innerHTML = '<option value="">Select Barangay</option>';
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'get_location.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            document.getElementById('city').innerHTML = this.responseText;
        }
    };
    xhr.send('provCode=' + encodeURIComponent(provCode));
}

function fetchBarangays(cityCode) {
    if (cityCode === "") {
        document.getElementById('barangay').innerHTML = '<option value="">Select Barangay</option>';
        return;
    }

    const xhr = new XMLHttpRequest();   
    xhr.open('POST', 'get_location.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            document.getElementById('barangay').innerHTML = this.responseText;
        }
    };
    xhr.send('cityCode=' + encodeURIComponent(cityCode));
}
</script>
</body>
</html>