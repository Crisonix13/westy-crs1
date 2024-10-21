<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTT Page - Company Selection and Media Upload</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <style>
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 28px;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .error {
            color: #ff0000;
        }
        .form-section {
            margin-bottom: 20px;
        }
        .form-section h2 {
            font-size: 20px;
            color: #007bff;
            margin-bottom: 10px;
        }
        .form-section .form-check {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<?php
session_start();
require_once '../components/db.php';
include '../components/sidebar.php';
include '../components/navbar.php';
?>
<?php
$sqlCompanies = "SELECT clientID, clientName FROM client";
$resultCompanies = $conn->query($sqlCompanies);
?>
<div class="container my-5">
    <h1 class="fw-bold">Upload Attachments</h1>
    <div class="form-group mb-4">
        <label for="clientSelect">Select Client</label>
        <select class="form-control" id="clientSelect" name="clientID" required>
            <option value="">Select Company</option>
            <?php
            if ($resultCompanies && $resultCompanies->num_rows > 0) {
                while ($company = $resultCompanies->fetch_assoc()) {
                    echo "<option value='" . (int)$company['clientID'] . "'>" . htmlspecialchars($company['clientName']) . "</option>";
                }
            }
            ?>
        </select>
    </div>

    <div class="form-section">
        <h2>Notarized Affidavit</h2>
        <form action="upload_handler.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="fileType" value="notarizedAffidavit">
            <input type="file" class="form-control my-2" name="fileName[]" multiple required>
            <input type="hidden" name="clientID" value="" id="clientIDNotarizedAffidavit">
            <button type="submit" name="addFile" class="btn btn-success">Add File</button>
        </form>
    </div>

    <div class="form-section">
        <h2>Waste Management Plan</h2>
        <form action="upload_handler.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="fileType" value="wasteManagementPlan">
            <input type="file" class="form-control my-2" name="fileName[]" multiple required>
            <input type="hidden" name="clientID" value="" id="clientIDWasteManagementPlan">
            <button type="submit" name="addFile" class="btn btn-success">Add File</button>
        </form>
    </div>

    <div class="form-section">
        <h2>PCO Accreditation</h2>
        <form action="upload_handler.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="fileType" value="pcoAccreditation">
            <input type="file" class="form-control my-2" name="fileName[]" multiple required>
            <input type="hidden" name="clientID" value="" id="clientIDPcoAccreditation">
            <button type="submit" name="addFile" class="btn btn-success">Add File</button>
        </form>
    </div>

    <div class="form-section">
        <h2>Emergency Plan</h2>
        <form action="upload_handler.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="fileType" value="emergencyPlan">
            <input type="file" class="form-control my-2" name="fileName[]" multiple required>
            <input type="hidden" name="clientID" value="" id="clientIDEmergencyPlan">
            <button type="submit" name="addFile" class="btn btn-success">Add File</button>
        </form>
    </div>

    <div class="form-section">
        <h2>Storage Area Photos</h2>
        <form action="upload_handler.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="fileType" value="storageAreaPhotos">
            <input type="file" class="form-control my-2" name="fileName[]" multiple required>
            <input type="hidden" name="clientID" value="" id="clientIDStorageAreaPhotos">
            <button type="submit" name="addFile" class="btn btn-success">Add File</button>
        </form>
    </div>

    <div class="form-section">
        <h2>Request Letter</h2>
        <form action="upload_handler.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="fileType" value="requestLetter">
            <input type="file" class="form-control my-2" name="fileName[]" multiple required>
            <input type="hidden" name="clientID" value="" id="clientIDRequestLetter">
            <button type="submit" name="addFile" class="btn btn-success">Add File</button>
        </form>
    </div>

    <?php if (isset($_SESSION['files'])): ?>
        <div class="form-section">
            <h2>Uploaded Files</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>File Size</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                    <thead>
    <tr>
        <th>File Name</th>
        <th>File Size</th>
        <th>Description</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php
    $fileTypeDescriptions = [
        "notarizedAffidavit" => "Duly notarized affidavit attesting to the truth, accuracy, and genuineness of all information, documents, and records contained and attached in the application.",
        "massBalance" => "Mass balance of manufacturing process",
        "wasteManagementPlan" => "Description of existing waste management plan",
        "wasteAnalysis" => "Analysis of waste(s)",
        "otherInfo" => "Other relevant information e.g. planned changes in production process or output, comparison with relation operation.",
        "eccCnc" => "Copy of Environmental Compliance Certificate (ECC) / Certificate of Non-Coverage (CNC)",
        "pto" => "Copy of Valid Permit to Operate (PTO)",
        "dischargePermit" => "Copy of Valid Discharge Permit (DP)",
        "pcoAccreditation" => "Pollution Control Officer accreditations certificate",
        "emergencyPlan" => "Contingency and Emergency Plan",
        "storageAreaPhotos" => "Photographs of the hazardous waste storage area",
        "requestLetter" => "Official letter of request",
        "tenantsList" => "List of individual tenants/establishments",
        "memberInfo" => "Information on the individual member",
        "embClusteringLetter" => "Letter from the EMB Central Office on the approved clustering",
        "jointUnderstandingAffidavit" => "Affidavit of Joint Understanding among individual member establishments, the cluster Managing Head, and the cluster PCO",
        "clusterMap" => "Map of clustered individual establishments including geotagged photos of the facade of the establishments"
    ];
    foreach ($_SESSION['files'] as $key => $file): ?>
        <tr>
            <td><?php echo htmlspecialchars($file['fileName']); ?></td>
            <td><?php echo htmlspecialchars($file['fileSize']); ?></td>
            <td><?php echo htmlspecialchars($fileTypeDescriptions[$file['fileType']] ?? 'Unknown File Type'); ?></td>
            <td>
                <form action="functions.php" method="post">
                    <input type="hidden" name="delete_key" value="<?php echo $key; ?>">
                    <button type="submit" name="delete_file" class="btn btn-outline-danger">
                        <i class='fa-solid fa-trash'></i>
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
</table>
</div>
<?php endif; ?>
</div>

<script>
    document.getElementById('clientSelect').addEventListener('change', function() {
        var clientID = this.value;
        document.querySelectorAll('input[name="clientID"]').forEach(function(input) {
            input.value = clientID;
        });
    });
</script>
</body>
</html>