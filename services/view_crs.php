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

<style>
    /* Ensure body has no margin */
    body {
        background-color: #E6F0F1; /* Light Sage Green */
        margin: 0; /* Remove default margin */
        padding: 0;
        font-family: Arial, sans-serif; /* Optional: Set a clean font */
    }

    .sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    height: 100%;
    background-color: #2C3E50; /* Dark color for sidebar */
    z-index: 1000; /* Ensure sidebar is on top */
    }

    /* Navbar Styles */
    .navbar {
        position: fixed;
        top: 0;
        left: 250px; /* Adjust to account for the sidebar width */
        width: calc(100% - 250px); /* Full width minus the sidebar */
        z-index: 500; /* Lower than the sidebar */
        background-color: #34495E; /* Darker navbar color */
    }

    /* Container Styles */
    .container {
        margin-left: 400px; /* Ensure content starts after the sidebar */
        padding-top: 60px; /* Add padding to avoid overlapping with navbar */
        background-color: #FFFFF0; /* Ivory */
        border-radius: 10px;
        border: 1px solid #D4B483; /* Soft Gold border */
    }


    /* Button Styles */
    .custom-nav-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-size: 1rem;
        flex: 1 1 auto; /* Allow buttons to resize within their space */
        height: 60px;
        white-space: nowrap;
        padding: 5px; /* Minimize padding */
        margin: 0 2px; /* Adjust margin to control spacing between buttons */
        border-radius: 5px; /* Add slight rounding for better aesthetics */
        background-color: #4A684A; /* Forest Green background */
        color: #FFFFF0; /* Ivory text color for contrast */
        border: 1px solid transparent; /* No border for the default state */
    }

    /* Button Hover/Active States */
    .custom-nav-btn:hover,
    .custom-nav-btn:focus {
        background-color: #3C5C3C; /* Darker Forest Green for hover effect */
        border-color: #D4B483; /* Soft Gold border on hover */
    }

    .custom-nav-btn.disabled {
        background-color: #9CAF88; /* Sage Green for disabled state */
        color: #FFFFFF; /* White text color for disabled state */
        border-color: #D4B483; /* Soft Gold border for disabled state */
        cursor: not-allowed; /* Change cursor to indicate disabled state */
    }

    /* Navbar Styles */
    .navbar-nav {
        flex-wrap: nowrap; /* Ensure buttons remain in one line */
        margin: 0; /* Remove margin */
        padding: 0; /* Remove padding */
    }

    .navbar {
        padding: 0; /* Align navbar with container */
        width: 100%;
    }
</style>
<div class="container w-75">
    <form action="functions.php" method="post" enctype="multipart/form-data">
        <div class="card my-3">
            <div class="card-body">
                <h1 class="fw-bold my-3 me-2">General Information</h1>
                <div class="row align-items-center my-2">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <label for="clientID" class="form-label fw-bold">Company</label>
                        <select class="form-control" name="clientID" id="clientID">
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
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label for="managingHead" class="form-label fw-bold">Managing Head</label>
                            <input class="form-control" type="text" name="managingHead" placeholder="Managing Head" required>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="managingHeadMobNum" class="form-label fw-bold">Mobile Number</label>
                            <input class="form-control" type="text" name="managingHeadMobNum" placeholder="Mobile Number" required>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="managingHeadTelNum" class="form-label fw-bold">Telephone Number</label>
                            <input class="form-control" type="text" name="managingHeadTelNum" placeholder="Telephone Number" required>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="natureBusiness" class="form-label fw-bold">Nature of Business</label>
                            <input class="form-control" type="text" name="natureBusiness" placeholder="Nature of Business" required>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label for="psicNum" class="form-label fw-bold">PSIC Number</label>
                            <input class="form-control" type="text" name="psicNum" placeholder="PSIC Number" required>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="psicDesc" class="form-label fw-bold">PSIC Description</label>
                            <input class="form-control" type="text" name="psicDesc" placeholder="PSIC Description" required>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label for="dateEstablishment" class="form-label fw-bold">Date of Establishment</label>
                            <input class="form-control" type="date" name="dateEstablishment" placeholder="Nature of Business" required>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label for="numEmployees" class="form-label fw-bold">No. of Employees</label>
                            <input class="form-control" type="text" name="numEmployees" placeholder="PSIC Number" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container w-75">
            <div class="card my-3">
                <div class="card-body">
                    <h1 class="fw-bold my-3 me-2">Pollution Control Officer Information</h1>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <label for="pcoName" class="form-label fw-bold">PCO Name</label>
                            <input class="form-control" type="text" name="pcoName" placeholder="Name of Pollution Control Officer" required>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <label for="pcoMobNum" class="form-label fw-bold">PCO Mobile Number</label>
                            <input class="form-control" type="text" name="pcoMobNum" placeholder="Mobile Number of Pollution Control Officer" required>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <label for="pcoTelNum" class="form-label fw-bold">PCO Telephone Number</label>
                            <input class="form-control" type="text" name="pcoTelNum" placeholder="Telephone Number of Pollution Control Officer" required>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <label for="pcoEmail" class="form-label fw-bold">PCO E-mail Address</label>
                            <input class="form-control" type="email" name="pcoEmail" placeholder="E-mail Address of Pollution Control Officer" required>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label for="pcoAccredNo" class="form-label fw-bold">PCO Accreditation No.</label>
                            <input class="form-control" type="text" name="pcoAccredNo" placeholder="Accreditation No. of Pollution Control Officer" required>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label for="pcoAccredDate" class="form-label fw-bold">PCO Date of Accreditation</label>
                            <input class="form-control" type="date" name="pcoAccredDate" placeholder="Date of Accreditation of Pollution Control Officer" required>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="card my-3">
                <div class="card-body">
                    <h1 class="fw-bold my-3 me-2">Facility Address</h1>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label for="region" class="form-label fw-bold">Region</label>
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
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label for="province" class="form-label fw-bold">Province</label>
                                <select class="form-control" id="province" name="province" onchange="fetchCities(this.value)" required>
                                    <option value="">Select Province</option>
                                </select>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <label for="city" class="form-label fw-bold">City/Municipality</label>
                            <select class="form-control" id="city" name="city" onchange="fetchBarangays(this.value)" required>
                                <option value="">Select City/Municipality</option>  
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <label for="barangay" class="form-label fw-bold">Barangay</label>
                            <select class="form-control" id="barangay" name="barangay" required>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <label for="zipCode" class="form-label fw-bold">Zip Code</label>
                            <input class="form-control" type="text" name="zipCode" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card my-3">
                <div class="card-body">
                    <div class="row align-items-center my-2">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <h1 class="fw-bold my-3 me-2">Geolocation</h1>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 text-end">
                            <a href="https://www.google.com/maps" target="_blank" class="btn text-white" style="background-color:#253E23">
                                <i class="fa-regular fa-map me-2"></i>Open Map
                            </a>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label for="latitude" class="form-label fw-bold">Latitude</label>
                            <input class="form-control" type="text" name="latitude" placeholder="Latitude coordinates" required>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label for="longitude" class="form-label fw-bold">Longitude</label>
                            <input class="form-control" type="text" name="longitude" placeholder="Longitude coordinates" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn text-white w-100" style="background-color:#253E23" name="finalizeApplication" role="button">
            <i class="fa-solid fa-check-to-slot"></i>Finalize Application
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
