<?php
    session_start();

    if (!isset($_SESSION['user_logged_in'])){
        header('Location: login');
        exit();
    }

    require 'components/header.php';
    require 'components/db.php';
    include 'components/sidebar.php';
    include 'components/navbar.php';

    $userQuery = "SELECT * FROM user ORDER BY userID DESC";
    $userResult = mysqli_query($conn, $userQuery);

    $users = [];
    
    while($row = mysqli_fetch_assoc($userResult)){
        $userID       = $row['userID'];
        $userFname    = $row['userFname'];
        $userLname    = $row['userLname'];
    
        $userType = isset($row['userType']) && !is_null($row['userType']) ? $row['userType'] : "N/A";
    
        switch ($row['userStatus']) {
            case 0:
                $userStatus = "In Progress";
                break;
            case 1:
                $userStatus = "Approved";
                break;
            case 2:
                $userStatus = "Rejected";
                break;
            default:
                $userStatus = "Unknown"; 
        }
    
        $users[] = [
            'userID' => $userID,
            'userFname' => $userFname,
            'userLname' => $userLname,
            'userType' => $userType,
            'userStatus' => $userStatus
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
<div class="container w-75">
    <div class="d-flex justify-content-center">
        <h1 class="fw-bold my-3 me-2">Employees</h1> 
    </div>
    <table class="table table-responsive table-hover">
        <thead class="text-center">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Department</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="5" class="text-center">No users found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="text-center"><?php echo $user['userFname'] . " " . $user['userLname']; ?></td>
                        <td class="text-center"><?php echo $user['userType']; ?></td>
                        <td class="text-center"><?php echo $user['userStatus']; ?></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center">
                                <!-- View Button -->
                                <div class="text-center me-1">
                                    <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#viewUserModal<?php echo $user['userID']; ?>">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-eye"></i>
                                        </div>
                                    </button>
                                </div>
                                <!-- Edit Button -->
                                <div class="text-center">
                                    <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['userID']; ?>">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editUserModal<?php echo $user['userID']; ?>" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="functions.php" method="post">
                                        <div class="row align-items-center my-2">
                                            <div class="col-xl-3 col-lg-3 col-md-3">
                                                <label for="clientName" class="form-label">Status</label>
                                            </div>
                                            <div class="col-xl-9 col-lg-9 col-md-9">
                                                <select class="input-group form-control" id="inputGroupSelect01">
                                                    <option selected>Select option</option>
                                                    <option value="1">Approve</option>
                                                    <option value="2">Reject</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row align-items-center my-2">
                                            <div class="col-xl-3 col-lg-3 col-md-3">
                                                <label for="clientName" class="form-label">Department</label>
                                            </div>
                                            <div class="col-xl-9 col-lg-9 col-md-9">
                                                <select class="input-group form-control" id="inputGroupSelect01">
                                                    <option selected >Select option</option>
                                                    <option value="ASD">ASD</option>
                                                    <option value="LSD">LSD</option>
                                                    <option value="Database">Database</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" name="editUser" class="btn btn-success w-25">Edit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>