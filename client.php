<?php
    session_start();

    if (!isset($_SESSION['user_logged_in'])){
        header('Location: login');
        exit();
    }

    require 'components/header.php';
    require 'components/db.php';
    include 'components/navbar.php';
    include 'components/sidebar.php';

    $clientQuery = "SELECT * FROM client ORDER BY clientID DESC";
    $clientResult = mysqli_query($conn, $clientQuery);

    $clients = [];
    
    while($row = mysqli_fetch_assoc($clientResult)){
        $clientID       = $row['clientID'];
        $clientName     = $row['clientName'];
        $clientStatus   = $row['clientStatus'];

        $clientSubmitted = isset($row['clientSubmitted']) && !is_null($row['clientSubmitted']) ? $row['clientSubmitted'] : "N/A";
        $clientExpiry    = isset($row['clientExpiry']) && !is_null($row['clientExpiry']) ? $row['clientExpiry'] : "N/A";

        $clients[] = [
            'clientID' => $clientID,
            'clientName' => $clientName,
            'clientStatus' => $clientStatus,
            'clientSubmitted' => $clientSubmitted,
            'clientExpiry' => $clientExpiry
        ];
    }

?>
<style>
    body {
        background-color: #E6F0F1; /* Light Sage Green */
        color: #2C6B6F; /* Sage Green */
    }

    .container {
        background-color: #F5F5DC; /* Ivory */
        padding: 50px;
        border-radius: 8px;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .btn-primary {
        background-color: #2C6B6F; /* Sage Green */
        border-color: #2C6B6F;
    }
    
    .btn-primary:hover {
        background-color: #1D4F51;
        border-color: #1D4F51;
    }

    .btn-secondary {
        background-color: #F5F5DC; /* Ivory */
        border-color: #F5F5DC;
        color: #2C6B6F; /* Sage Green */
    }

    .btn-secondary:hover {
        background-color: #E0E0E0;
        border-color: #E0E0E0;
    }

    .modal-content {
        background-color: #F5F5DC; /* Ivory */
        color: #2C6B6F; /* Sage Green */
    }

    .modal-header {
        border-bottom: 1px solid #2C6B6F; /* Sage Green */
    }

    .form-control {
        border-color: #2C6B6F; /* Sage Green */
    }

    .table thead {
        background-color: #2C6B6F; /* Sage Green */
        color: #F5F5DC; /* Ivory */
    }

    .table tbody tr:nth-of-type(even) {
        background-color: #E0E0E0; /* Light Gray */
    }

    .table tbody tr:nth-of-type(odd) {
        background-color: #F5F5DC; /* Ivory */
    }

    .table tbody tr:hover {
        background-color: #D0E6E3; /* Light Sage Green */
    }
</style>
</style>
<div class="container w-75">
    <div class="d-flex justify-content-center">
        <h1 class="fw-bold my-3 me-2">Clients</h1> 
    </div>
    <div class="text-end mb-3">
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addClientModal" role="button">
            <i class="fa-solid fa-plus me-1"></i>Add Client
        </button>
    </div>
    <table class="table table-responsive table-hover">
    <thead class="text-center">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Status</th>
            <th scope="col">Date Submitted</th>
            <th scope="col">Date Expiry</th>
            <th scope="col">Detail</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($clients)): ?>
            <tr>
                <td colspan="6" class="text-center">No clients found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td class="text-center"><?php echo htmlspecialchars($client['clientName']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($client['clientStatus']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($client['clientSubmitted']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($client['clientExpiry']); ?></td>
                    <td class="text-center">
                        <a href="/westy-crs/company/crs/<?php echo htmlspecialchars($client['clientID']); ?>" class="btn btn-primary">View Detail</a>
                    </td>
                    <td class="text-center">
                        <!-- Approve/Reject Button -->
                        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editClientModal<?php echo $client['clientID']; ?>">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<!-- Modal Structure -->
<?php foreach ($clients as $client): ?>
    <div class="modal fade" id="editClientModal<?php echo $client['clientID']; ?>" tabindex="-1" aria-labelledby="editClientModalLabel<?php echo $client['clientID']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClientModalLabel<?php echo $client['clientID']; ?>">Approve/Reject Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="functions.php" method="POST">
                        <!-- Hidden field for client ID -->
                        <input type="hidden" name="clientID" value="<?php echo $client['clientID']; ?>">

                        <!-- Client Information -->
                        <p><strong>Client Name:</strong> <?php echo htmlspecialchars($client['clientName']); ?></p>

                        <!-- Action Selection (Approve or Reject) -->
                        <div class="mb-3">
                            <label for="action<?php echo $client['clientID']; ?>" class="form-label">Action</label>
                            <select class="form-control" id="action<?php echo $client['clientID']; ?>" name="action" required onchange="toggleRejectionReason(this)">
                                <option value="" disabled selected>Select Action</option>
                                <option value="approve">Approve</option>
                                <option value="reject">Reject</option>
                            </select>
                        </div>

                        <!-- Reason for Rejection (optional, shown only if rejected) -->
                        <div class="mb-3" id="rejectionReason<?php echo $client['clientID']; ?>" style="display: none;">
                            <label for="reason<?php echo $client['clientID']; ?>" class="form-label">Reason for Rejection</label>
                            <textarea class="form-control" id="reason<?php echo $client['clientID']; ?>" name="reason" rows="3"></textarea>
                        </div>

                        <button type="submit" name="clientApproval" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>



<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">Add Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="functions.php" method="post">
                    <div class="row align-items-center my-2">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="clientName" class="form-label">Name:</label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                            <input type="text" class="form-control" id="clientName" name="clientName" placeholder="Enter client name" required>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="clientAddress" class="form-label">Address:</label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                            <input type="text" class="form-control" id="clientAddress" name="clientAddress" placeholder="Enter client address" required>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="clientTypeEstablishment" class="form-label">Type of Establishment:</label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                            <input type="text" class="form-control" id="clientTypeEstablishment" name="clientTypeEstablishment" placeholder="Enter type of establishment" required>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="clientContactPerson" class="form-label">Contact Person:</label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                            <input type="text" class="form-control" id="clientContactPerson" name="clientContactPerson" placeholder="Enter client contact person" required>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="clientContactNumber" class="form-label">Contact Number:</label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                            <input type="number" class="form-control" id="clientContactNumber" name="clientContactNumber" placeholder="Enter client contact number" required>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="clientEmail" class="form-label">Email:</label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                            <input type="email" class="form-control" id="clientEmail" name="clientEmail" placeholder="Enter client email" required>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="clientCRS" class="form-label">CRS ID No.:</label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                            <input type="text" class="form-control" id="clientCRS" name="clientCRS" placeholder="Enter client CRS ID no." required>
                        </div>
                    </div>
                    <div class="row align-items-center my-2">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <label for="clientHW" class="form-label">HW ID No.:</label>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                            <input type="text" class="form-control" id="clientHW" name="clientHW" placeholder="Enter client HW ID no." required>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" name="addClient" class="btn btn-primary w-25">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRejectionReason(selectElement) {
    var clientID = selectElement.id.replace('action', '');
    var rejectionReasonDiv = document.getElementById('rejectionReason' + clientID);
    
    if (selectElement.value === 'reject') {
        rejectionReasonDiv.style.display = 'block';
    } else {
        rejectionReasonDiv.style.display = 'none';
    }
}
</script>
