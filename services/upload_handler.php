<?php
session_start();
require_once '../components/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clientID = $_POST['clientID'];
    $fileType = $_POST['fileType'];
    $files = $_FILES['fileName'];

    // Validate clientID
    $sqlValidateClient = "SELECT clientID FROM client WHERE clientID = ?";
    $stmtValidateClient = $conn->prepare($sqlValidateClient);
    $stmtValidateClient->bind_param("i", $clientID);
    $stmtValidateClient->execute();
    $stmtValidateClient->store_result();

    if ($stmtValidateClient->num_rows == 0) {
        $_SESSION['message'] = "Invalid client ID.";
        header("Location: hwms.php");
        exit();
    }
    $stmtValidateClient->close();

    // Define the upload directory
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Check if multiple files were uploaded
    if (is_array($files['name'])) {
        // Loop through each file
        for ($i = 0; $i < count($files['name']); $i++) {
            $fileName = $files['name'][$i];
            $fileTmpName = $files['tmp_name'][$i];
            $fileSize = $files['size'][$i];
            $fileError = $files['error'][$i];
            $fileType = $files['type'][$i];

            // Generate a unique file name to avoid conflicts
            $fileNewName = uniqid('', true) . "-" . basename($fileName);
            $fileDestination = $uploadDir . $fileNewName;

            // Check for errors
            if ($fileError === 0) {
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // Insert file metadata into the database
                    $sql = "INSERT INTO uploads (clientID, fileType, fileName, filePath, fileSize) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isssi", $clientID, $fileType, $fileNewName, $fileDestination, $fileSize);
                    if ($stmt->execute()) {
                        $_SESSION['message'] = "File uploaded successfully!";
                    } else {
                        $_SESSION['message'] = "Failed to upload file.";
                    }
                    $stmt->close();
                } else {
                    $_SESSION['message'] = "Failed to move uploaded file.";
                }
            } else {
                $_SESSION['message'] = "Error uploading file.";
            }
        }
    } else {
        // Handle single file upload
        $fileName = $files['name'];
        $fileTmpName = $files['tmp_name'];
        $fileSize = $files['size'];
        $fileError = $files['error'];
        $fileType = $files['type'];

        // Generate a unique file name to avoid conflicts
        $fileNewName = uniqid('', true) . "-" . basename($fileName);
        $fileDestination = $uploadDir . $fileNewName;

        // Check for errors
        if ($fileError === 0) {
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                // Insert file metadata into the database
                $sql = "INSERT INTO uploads (clientID, fileType, fileName, filePath, fileSize) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isssi", $clientID, $fileType, $fileNewName, $fileDestination, $fileSize);
                if ($stmt->execute()) {
                    $_SESSION['message'] = "File uploaded successfully!";
                } else {
                    $_SESSION['message'] = "Failed to upload file.";
                }
                $stmt->close();
            } else {
                $_SESSION['message'] = "Failed to move uploaded file.";
            }
        } else {
            $_SESSION['message'] = "Error uploading file.";
        }
    }
    header("Location: hwms.php");
    exit();
}
?>