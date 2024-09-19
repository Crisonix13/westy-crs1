<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require $_SERVER['DOCUMENT_ROOT'] . '/westy-transporter/components/db.php';

if (!class_exists('User')) {
    class User {
        // registration
        public function register($firstName, $lastName, $email, $mobileNumber, $password, $governmentID, $companyID) {
            global $conn;

            $query = "SELECT * FROM user WHERE userEmail = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('Email is already taken');window.location.href='./registration';</script>";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $query = "INSERT INTO user (userFname, userLname, userContactNumber, userEmail, userPassword, userGovernmentID, userCompanyID)
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('sssssss', $firstName, $lastName, $mobileNumber, $email, $hashedPassword, $governmentID, $companyID);

                if ($stmt->execute()) {
                    return true;
                } else {
                    error_log('Database error: ' . $stmt->error);
                    return false;
                }
            }
            $stmt->close();
        }

        // login
        public function login($email, $password){
            global $conn;

            $query = "SELECT * FROM user WHERE userEmail = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['userPassword'])) {
                    return true;
                } else {
                    echo "<script>alert('Wrong password. Please try again.');window.location.href='./login';</script>";
                }
            } else {
                return false;
            }
            $stmt->close();
        }

        public function updateClientStatus($clientID, $clientStatus = null) {
            global $conn;  // Use global $conn

            $query = "UPDATE client SET clientStatus = ? WHERE clientID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $clientStatus, $clientID);

            return $stmt->execute();
        }

        public function addClient($clientName, $clientAddress, $clientTypeEstablishment, $clientContactPerson, $clientContactNumber, $clientEmail, $clientCRS, $clientHW) {
            global $conn;

            $query = "INSERT INTO client(clientName, clientAddress, clientType, clientContactPerson, clientContactNumber, clientEmail, clientCRS, clientHW, dateSubmitted, dateExpiry)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL)";
            $stmt = $conn->prepare($query);

            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }

            $stmt->bind_param('ssssssss', $clientName, $clientAddress, $clientTypeEstablishment, $clientContactPerson, $clientContactNumber, $clientEmail, $clientCRS, $clientHW);

            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                error_log("Execute failed: " . $stmt->error); // Log error
                $stmt->close();
                return false;
            }
        }

        public function submitApplication($clientID, $managingHead, $managingHeadMobNum, $managingHeadTelNum, $natureBusiness, $psicNum, $psicDesc, $dateEstablishment, $numEmployees, $pcoName, $pcoMobNum, $pcoTelNum, $pcoEmail, $pcoAccredNo, $pcoAccredDate, $region, $province, $city, $barangay, $zipCode, $latitude, $longitude) {
            global $conn;
        
            // Debugging: Check if clientID exists
            $clientQuery = "SELECT * FROM client WHERE clientID = ?";
            $stmt = $conn->prepare($clientQuery);
            $stmt->bind_param('i', $clientID);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows === 0) {
                error_log("ClientID does not exist: " . $clientID); // Log error for debugging
                die("ClientID does not exist in the database.");
            }
        
            // Insert into the application table
            $query = "INSERT INTO application(clientID, appRegistration, appManager, appManagerContactNumber, appManagerTelephoneNumber, 
                                                natureBusiness, psicNum, psicDesc, appDateClient, appNumEmployees,
                                                appPCOName, appPCOMobileNumber, appPCOTelephoneNumber, appPCOEmail, appPCOAccredNo, 
                                                appPCODateAccred, appFaciRegion, appFaciProvince, appFaciCity, appFaciBarangay, 
                                                appFaciZip, appGeoLatitude, appGeoLongitude, appStatus)
                      VALUES (?, CURDATE(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'PROCESSING')";
        
            $stmt = $conn->prepare($query);
            $stmt->bind_param('isssssssssssssssssssss', $clientID, $managingHead, $managingHeadMobNum, $managingHeadTelNum, 
                              $natureBusiness, $psicNum, $psicDesc, $dateEstablishment, $numEmployees, $pcoName, $pcoMobNum, 
                              $pcoTelNum, $pcoEmail, $pcoAccredNo, $pcoAccredDate, $region, $province, $city, $barangay, 
                              $zipCode, $latitude, $longitude);
        
            if (!$stmt->execute()) {
                error_log("Application insertion failed: " . $stmt->error);
                die("Application insertion failed.");
            }
        
            $stmt->close();
            return "Submission successful.";
        }
    }
}        