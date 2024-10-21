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
        background-color: #f8f9fa; /* Light Gray */
        color: #343a40; /* Dark Gray */
        font-family: Arial, sans-serif;
    }

    .container {
        background-color: #ffffff; /* White */
        padding: 50px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: #007bff; /* Blue */
        border-color: #007bff;
    }
    
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d; /* Gray */
        border-color: #6c757d;
        color: #ffffff;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }

    .modal-content {
        background-color: #ffffff; /* White */
        color: #343a40; /* Dark Gray */
    }

    .modal-header {
        border-bottom: 1px solid #dee2e6; /* Light Gray */
    }

    .form-control {
        border-color: #ced4da; /* Light Gray */
    }

    .table thead {
        background-color: #ffffff; /* Dark Gray */
        color: #ffffff; /* White */
    }

    .table tbody tr:nth-of-type(even) {
        background-color: #f8f9fa; /* Light Gray */
    }

    .table tbody tr:nth-of-type(odd) {
        background-color: #ffffff; /* White */
    }

    .table tbody tr:hover {
        background-color: #e9ecef; /* Light Gray */
    }
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
            <th scope="col">PTT Date Submitted</th>
            <th scope="col">PTT Date Expiry</th>
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
                <td class="text-center">
                    <?php echo htmlspecialchars($client['clientSubmitted']); ?>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editDateModal<?php echo $client['clientID']; ?>">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                </td>
                <td class="text-center">
                    <?php echo htmlspecialchars($client['clientExpiry']); ?>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editDateModal<?php echo $client['clientID']; ?>">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                </td>
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

    <!-- Modal Structure for Approve/Reject -->
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Modal Structure for Editing Dates -->
    <?php foreach ($clients as $client): ?>
        <div class="modal fade" id="editDateModal<?php echo $client['clientID']; ?>" tabindex="-1" aria-labelledby="editDateModalLabel<?php echo $client['clientID']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDateModalLabel<?php echo $client['clientID']; ?>">Edit Dates</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="functions.php" method="POST">
                            <!-- Hidden field for client ID -->
                            <input type="hidden" name="clientID" value="<?php echo $client['clientID']; ?>">

                            <!-- Date Submitted -->
                            <div class="mb-3">
                                <label for="clientSubmitted<?php echo $client['clientID']; ?>" class="form-label">Date Submitted</label>
                                <input type="date" class="form-control" id="clientSubmitted<?php echo $client['clientID']; ?>" name="clientSubmitted" value="<?php echo htmlspecialchars($client['clientSubmitted']); ?>" required>
                            </div>

                            <!-- Date Expiry -->
                            <div class="mb-3">
                                <label for="clientExpiry<?php echo $client['clientID']; ?>" class="form-label">Date Expiry</label>
                                <input type="date" class="form-control" id="clientExpiry<?php echo $client['clientID']; ?>" name="clientExpiry" value="<?php echo htmlspecialchars($client['clientExpiry']); ?>" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
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