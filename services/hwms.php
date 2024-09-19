<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTT Page - Company Selection and Media Upload</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
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
        .error {
            color: #ff0000;
        }
    </style>
</head>
<body>
<?php
require_once '../components/db.php';
include '../components/sidebar.php';
include '../components/navbar.php';

// Include your necessary PHP code here

?>
    <div class="container w-75 my-5">
        <div class="card my-3">
            <div class="card-body">
                <div class="row align-items-center my-2">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h1 class="fw-bold my-3 me-2">Upload Attachments</h1>
                    </div>
                </div>
                <div class="row align-items-center my-2">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <?php
                        $fileTypes = []; // Assume $fileTypes is populated with relevant data
                        ?>
                        <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckNotarizedAffidavit" disabled
                                    <?php if (in_array('notarizedAffidavit', $fileTypes)) echo 'checked'; ?>>
                                <label class="form-check-label" for="flexCheckNotarizedAffidavit">
                                    Duly notarized affidavit attesting to the truth, accuracy, and genuineness of all information, documents, and records contained and attached in the application.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckWasteManagementPlan" disabled
                                    <?php if (in_array('wasteManagementPlan', $fileTypes)) echo 'checked'; ?>>
                                <label class="form-check-label" for="flexCheckWasteManagementPlan">
                                    Description of existing waste management plan
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckPcoAccreditation" disabled
                                    <?php if (in_array('pcoAccreditation', $fileTypes)) echo 'checked'; ?>>
                                <label class="form-check-label" for="flexCheckPcoAccreditation">
                                    Pollution Control Officer accreditations certificate
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckEmergencyPlan" disabled
                                    <?php if (in_array('emergencyPlan', $fileTypes)) echo 'checked'; ?>>
                                <label class="form-check-label" for="flexCheckEmergencyPlan">
                                    Contingency and Emergency Plan
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckStorageAreaPhotos" disabled
                                    <?php if (in_array('storageAreaPhotos', $fileTypes)) echo 'checked'; ?>>
                                <label class="form-check-label" for="flexCheckStorageAreaPhotos">
                                    Photographs of the hazardous waste storage area
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckRequestLetter" disabled
                                    <?php if (in_array('requestLetter', $fileTypes)) echo 'checked'; ?>>
                                <label class="form-check-label" for="flexCheckRequestLetter">
                                    Official letter of request
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center my-2">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <button type="button" class="btn text-white w-100" style="background-color:#586854" href="#" role="button" onclick="showFileTable()">
                            <i class="fa-solid fa-plus me-1"></i>Add Files
                        </button>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <button type="submit" class="btn text-white w-100" style="background-color:#253E23" name="finalizeApplication" role="button">
                            <i class="fa-solid fa-check-to-slot"></i>Finalize Application
                        </button>
                    </div>
                </div>
                <div class="row align-items-center my-2">
                    <table class="table table-responsive table-hover">
                        <thead class="text-start">
                            <tr>
                                <th scope="col" class="col-3">File Name</th>
                                <th scope="col" class="col-1"></th>
                                <th scope="col" class="col-5">File Type</th>
                                <th scope="col" class="col-3">
                                    <button type="button" class="btn text-white w-100" style="background-color:#253E23" href="#" data-bs-toggle="modal" data-bs-target="#addFileModal" role="button">
                                        <i class="fa-solid fa-check-to-slot"></i>Upload File
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if (isset($_SESSION['files'])) {
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
                                        "memberInfo" => "Information on the individual member establishment per approved cluster",
                                        "embClusteringLetter" => "Letter from the EMB Central Office on the approved clustering",
                                        "jointUnderstandingAffidavit" => "Affidavit of Joint Understanding among individual member establishments, the cluster Managing Head, and the cluster PCO",
                                        "clusterMap" => "Map of clustered individual establishments including geotagged photos of the facade of the establishments"
                                    ];
                                    foreach ($_SESSION['files'] as $key => $file) { ?>
                                        <tr>
                                            <td class='text-start'><?php echo htmlspecialchars($file['fileName']); ?></td>
                                            <td class='text-start'><?php echo htmlspecialchars($file['fileSize']); ?></td>
                                            <td class='text-start'><?php echo htmlspecialchars($fileTypeDescriptions[$file['fileType']] ?? 'Unknown File Type'); ?></td>
                                            <td class='text-start'>
                                                <form action="functions.php" method="post">
                                                    <input type="hidden" name="delete_key" value="<?php echo $key; ?>">
                                                    <button type="submit" name="delete_file" class="btn btn-outline-danger">
                                                        <i class='fa-solid fa-trash'></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- Add File Modal -->
<div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="addFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFileModalLabel">Add File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="functions.php" method="post" enctype="multipart/form-data">
                    <div class="row align-items-center my-2">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="fileType" class="form-label">File Type:</label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                            <select class="form-select" name="fileType" id="documentSelect">
                                <option value="">Select file type</option>
                                <option value="notarizedAffidavit">Duly notarized affidavit attesting to the truth, accuracy, and genuineness of all information, documents, and records contained and attached in the application.</option>
                                <!-- Add other options here -->
                            </select>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="fileName" class="form-label">File Name:</label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                            <input type="file" class="form-control" id="fileName" name="fileName" placeholder="File Name" required>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" name="addFile" class="btn btn-success w-25">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>