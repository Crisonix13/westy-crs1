<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client and Application Information</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding-top: 80px; /* Adjust this value as needed */
        }
        h1 {
            font-size: 24px;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 20px;
            color: #333;
            margin-top: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }
        .error {
            color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="container"> 
        <?php
require_once '../components/db.php';
include '../components/sidebar.php';
include '../components/navbar.php';

// Get the clientID from the URL
$clientID = isset($_GET['clientID']) ? (int)$_GET['clientID'] : 0;

if ($clientID > 0) {
    // Query to fetch client details
    $sqlClient = 'SELECT * FROM client WHERE clientID = ?';
    if ($stmtClient = $conn->prepare($sqlClient)) {
        $stmtClient->bind_param('i', $clientID);
        $stmtClient->execute();
        $resultClient = $stmtClient->get_result();
        $client = $resultClient->fetch_assoc();
        $stmtClient->close();

        if ($client) {
            echo "<h1 class='mb-4'>Client Information: " . htmlspecialchars($client['clientName']) . "</h1>";

            // Client Information
            echo "<div class='row'>";
            echo "<div class='col-md-6'>";
            echo "<div class='mb-3'><label><strong>Client Name</strong></label>";
            echo "<input type='text' class='form-control' value='" . htmlspecialchars($client['clientName']) . "' readonly></div>";

            echo "<div class='mb-3'><label><strong>Client Address</strong></label>";
            echo "<input type='text' class='form-control' value='" . htmlspecialchars($client['clientAddress']) . "' readonly></div>";

            echo "<div class='mb-3'><label><strong>Client Type</strong></label>";
            echo "<input type='text' class='form-control' value='" . htmlspecialchars($client['clientType']) . "' readonly></div>";
            echo "</div>";

            echo "<div class='col-md-6'>";
            echo "<div class='mb-3'><label><strong>Client Contact Person</strong></label>";
            echo "<input type='text' class='form-control' value='" . htmlspecialchars($client['clientContactPerson']) . "' readonly></div>";

            echo "<div class='mb-3'><label><strong>Client Contact Number</strong></label>";
            echo "<input type='text' class='form-control' value='" . htmlspecialchars($client['clientContactNumber']) . "' readonly></div>";

            echo "<div class='mb-3'><label><strong>Client Email</strong></label>";
            echo "<input type='text' class='form-control' value='" . htmlspecialchars($client['clientEmail']) . "' readonly></div>";
            echo "</div>";
            echo "</div>";

            // Check for Application Information
            $sqlApplication = 'SELECT * FROM application WHERE clientID = ?'; // Ensure this is correct column
            if ($stmtApplication = $conn->prepare($sqlApplication)) {
                $stmtApplication->bind_param('i', $clientID);
                $stmtApplication->execute();
                $resultApplication = $stmtApplication->get_result();
                $application = $resultApplication->fetch_assoc();
                $stmtApplication->close();

                if ($application) {
                    echo "<h2 class='mb-4'>Application Information</h2>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-6'>";
                    echo "<div class='mb-3'><label><strong>Application ID</strong></label>";
                    echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appID']) . "' readonly></div>";

                    echo "<div class='mb-3'><label><strong>Registration Date</strong></label>";
                    echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appRegistration']) . "' readonly></div>";

                    echo "<div class='mb-3'><label><strong>Application Manager</strong></label>";
                    echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appManager']) . "' readonly></div>";
                    echo "</div>";

                    echo "<div class='col-md-6'>";
                    echo "<div class='mb-3'><label><strong>Application Manager Contact Number</strong></label>";
                    echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appManagerContactNumber']) . "' readonly></div>";

                    echo "<div class='mb-3'><label><strong>Application Manager Telephone Number</strong></label>";
                    echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appManagerTelephoneNumber']) . "' readonly></div>";

                    echo "<div class='mb-3'><label><strong>Number of Employees</strong></label>";
                    echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appNumEmployees']) . "' readonly></div>";
                    echo "</div>";
                    echo "</div>";

                            // PCO Section
                            echo "<h2 class='mb-4'>Pollution Control Officer (PCO)</h2>";
                        
                            echo "<div class='row'>";
                            echo "<div class='col-md-6'>";
                            echo "<div class='mb-3'>";
                            echo "<label><strong>PCO Name</strong></label>";
                            echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appPCOName']) . "' readonly>";
                            echo "</div>";
                        
                            echo "<div class='mb-3'>";
                            echo "<label><strong>PCO Mobile Number</strong></label>";
                            echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appPCOMobileNumber']) . "' readonly>";
                            echo "</div>";
                            echo "</div>"; // Close col-md-6
                        
                            echo "<div class='col-md-6'>";
                            echo "<div class='mb-3'>";
                            echo "<label><strong>PCO Telephone Number</strong></label>";
                            echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appPCOTelephoneNumber']) . "' readonly>";
                            echo "</div>";
                        
                            echo "<div class='mb-3'>";
                            echo "<label><strong>PCO Accreditation Number</strong></label>";
                            echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appPCOAccredNo']) . "' readonly>";
                            echo "</div>";
                        
                            echo "<div class='mb-3'>";
                            echo "<label><strong>PCO Accreditation Date</strong></label>";
                            echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appPCODateAccred']) . "' readonly>";
                            echo "</div>";
                            echo "</div>"; // Close col-md-6
                            echo "</div>"; // Close row
                        
                            // Facility Section
                            echo "<h2 class='mb-4'>Facility Information</h2>";
                        
                            echo "<div class='row'>";
                            echo "<div class='col-md-6'>";
                            echo "<div class='mb-3'>";
                            echo "<label><strong>Facility Region</strong></label>";
                            echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appFaciRegion']) . "' readonly>";
                            echo "</div>";
                        
                            echo "<div class='mb-3'>";
                            echo "<label><strong>Facility Province</strong></label>";
                            echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appFaciProvince']) . "' readonly>";
                            echo "</div>";
                        
                            echo "<div class='mb-3'>";
                            echo "<label><strong>Facility City</strong></label>";
                            echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appFaciCity']) . "' readonly>";
                            echo "</div>";
                            echo "</div>"; // Close col-md-6
                        
                            echo "<div class='col-md-6'>";
                            echo "<div class='mb-3'>";
                            echo "<label><strong>Facility Barangay</strong></label>";
                            echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appFaciBarangay']) . "' readonly>";
                            echo "</div>";
                        
                            echo "<div class='mb-3'>";
                            echo "<label><strong>Facility Zip Code</strong></label>";
                            echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appFaciZip']) . "' readonly>";
                            echo "</div>";
                            echo "</div>"; // Close col-md-6
                            echo "</div>"; // Close row
                        
                            // Geolocation Section
                            echo "<h2 class='mb-4'>Geolocation</h2>";
                        
                            echo "<div class='row'>";
                            echo "<div class='col-md-6'>";
                            echo "<div class='mb-3'>";
                            echo "<label><strong>Latitude</strong></label>";
                            echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appGeoLatitude']) . "' readonly>";
                            echo "</div>";
                            echo "</div>"; // Close col-md-6
                        
                            echo "<div class='col-md-6'>";
                            echo "<div class='mb-3'>";
                            echo "<label><strong>Longitude</strong></label>";
                            echo "<input type='text' class='form-control' value='" . htmlspecialchars($application['appGeoLongitude']) . "' readonly>";
                            echo "</div>";
                            echo "</div>"; // Close col-md-6
                            echo "</div>"; // Close row

                            // Fetch client details including uploaded media
                            $sql = "SELECT clientName, uploaded_media FROM client WHERE clientID = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $clientID);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $clientDetails = $result->fetch_assoc();
                            $stmt->close();

                            // Check if there is an uploaded media file
                            if (!empty($clientDetails['uploaded_media'])) {
                                echo "<h2 class='mb-4'>Uploaded Media</h2>";
                                echo "<div class='row'>";
                                echo "<div class='col-md-6'>";
                                echo "<div class='mb-3'>";
                                echo "<label><strong>Uploaded Media</strong></label>";

                                // Assuming it's a PDF, provide a link to view it
                                $mediaPath = 'services/uploads/' . htmlspecialchars($clientDetails['uploaded_media']);
                                if (pathinfo($clientDetails['uploaded_media'], PATHINFO_EXTENSION) === 'pdf') {
                                    echo "<a href='" . htmlspecialchars($mediaPath) . "' target='_blank'>View Uploaded PDF</a>";
                                } else {
                                    echo "<p>No media uploaded or file type is not supported for display.</p>";
                                }

                                echo "</div>";
                                echo "</div>"; // Close col-md-6
                                echo "</div>"; // Close row
                            } else {
                                echo "<p>No media file uploaded for this client.</p>";
                            }



                            // Add other application-related information here
                        } else {
                            echo "<p class='error'>No application found for this client.</p>";
                        }
                    } else {
                        echo "<p class='error'>Error preparing application statement: " . $conn->error . "</p>";
                    }
                } else {
                    echo "<p class='error'>Client not found.</p>";
                }
            } else {
                echo "<p class='error'>Error preparing client statement: " . $conn->error . "</p>";
            }
        } else {
            echo "<p class='error'>Invalid client ID.</p>";
        }
        ?>
    </div>
</body>
</html>
