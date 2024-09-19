<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTT Page - Company Selection and Media Upload</title>
    <style>
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 100px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .error {
            color: #ff0000;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PTT - Company Selection and Media Upload</h1>

        <?php
        require_once '../components/db.php';
        include '../components/sidebar.php';
        include '../components/navbar.php';

        // Handle the form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $clientID = isset($_POST['clientID']) ? (int)$_POST['clientID'] : 0;

            // Check if clientID is valid
            if ($clientID > 0) {
                // Process the media upload
                if (isset($_FILES['mediaFile']) && $_FILES['mediaFile']['error'] == 0) {
                    $mediaFile = $_FILES['mediaFile'];
                    $targetDir = "uploads/";
                    $targetFile = $targetDir . basename($mediaFile["name"]);
                    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            
                    // Allow only PDF files
                    $allowedTypes = array("pdf");
                    if (in_array($fileType, $allowedTypes)) {
                        if (move_uploaded_file($mediaFile["tmp_name"], $targetFile)) {
                            // Store file path in the database for the selected client
                            $stmt = $conn->prepare("UPDATE client SET uploaded_media = ? WHERE clientID = ?");
                            $stmt->bind_param("si", $targetFile, $clientID);
            
                            if ($stmt->execute()) {
                                echo "<p>File uploaded successfully: <a href='$targetFile'>" . htmlspecialchars($mediaFile["name"]) . "</a></p>";
                            } else {
                                echo "<p class='error'>Failed to store media in the database.</p>";
                            }
                            $stmt->close();
                        } else {
                            echo "<p class='error'>Error uploading the media file.</p>";
                        }
                    } else {
                        echo "<p class='error'>Invalid file type. Only PDF files are allowed.</p>";
                    }
                } else {
                    echo "<p class='error'>Please select a valid media file to upload.</p>";
                }
            }
        }            

        // Fetch list of companies
        $sqlCompanies = "SELECT clientID, clientName FROM client";
        $resultCompanies = $conn->query($sqlCompanies);

        if ($resultCompanies && $resultCompanies->num_rows > 0) {
            echo "<form method='POST' enctype='multipart/form-data'>";
            
            // Company selection
            echo "<div class='form-group'>";
            echo "<label for='clientID'>Select Company</label>";
            echo "<select name='clientID' id='clientID' required>";
            echo "<option value=''>Select Company</option>";
            while ($company = $resultCompanies->fetch_assoc()) {
                echo "<option value='" . (int)$company['clientID'] . "'>" . htmlspecialchars($company['clientName']) . "</option>";
            }
            echo "</select>";
            echo "</div>";

            // Media upload
            echo "<div class='form-group'>";
            echo "<label for='mediaFile'>Upload Media</label>";
            echo "<input type='file' name='mediaFile' id='mediaFile' required>";
            echo "</div>";

            // Submit button
            echo "<button type='submit'>Submit</button>";
            echo "</form>";
        } else {
            echo "<p class='error'>No companies found.</p>";
        }
        ?>

    </div>
</body>
</html>
