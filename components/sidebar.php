<?php
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION)) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF'], ".php");
$userType = isset($_SESSION['userType']) ? $_SESSION['userType'] : 'guest';
?>

<!-- Bootstrap CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .sidebar {
        height: 100vh;
        width: 250px;
        background-color: #2C3E50;
        padding-top: 20px;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    .sidebar a {
        padding: 15px 20px;
        text-decoration: none;
        font-size: 18px;
        color: #F5F5DC;
        display: block;
        transition: background-color 0.3s ease;
    }

    .sidebar a:hover {
        background-color: #1D4F51;
        color: #F5F5DC;
    }

    .sidebar a.active {
        background-color: #1D4F51;
        color: #F5F5DC;
    }

    .main-content {
        margin-left: 250px;
        padding: 20px;
        background-color: #E6F0F1;
        color: #2C6B6F;
    }

    .dropdown-menu {
        background-color: #2C6B6F;
        border: none;
    }

    .dropdown-item {
        color: #F5F5DC;
        padding-left: 30px;
    }

    .dropdown-item:hover {
        background-color: #1D4F51;
        color: #F5F5DC;
    }

    .navbar {
        position: fixed;
        top: 0;
        left: 250px;
        width: calc(100% - 250px);
        z-index: 1000;
        background-color: #34495E;
        height: 60px;
    }

    .navbar .container-fluid {
        display: flex;
        justify-content: flex-end;
    }

    .navbar .navbar-nav {
        margin-right: 15px;
    }

    .navbar .nav-link {
        padding: 0;
    }

    .navbar .dropdown-menu {
        display: none;
    }

    .navbar .nav-item:hover .dropdown-menu {
        display: block;
    }

    .navbar .nav-item img {
        border-radius: 50%;
    }
</style>

<div class="sidebar">
    <a class="<?php echo $current_page == 'client' ? 'active' : ''; ?>" href="/westy-crs/client">Client</a>
    
    <a class="dropdown-toggle" data-bs-toggle="collapse" href="#servicesDropdown" role="button" aria-expanded="false" aria-controls="servicesDropdown">
        Services
    </a>
    <div class="collapse" id="servicesDropdown">
        <ul class="list-unstyled">
            <li><a class="dropdown-item" href="/westy-crs/services/view_crs.php">CRS</a></li>
            <li><a class="dropdown-item" href="/westy-crs/services/hwms.php">HWMS</a></li>
            <li><a class="dropdown-item" href="/westy-crs/services/ptt.php">PTT</a></li>
        </ul>
    </div>

    <?php if ($userType == 'Admin'): ?>
        <a href="/westy-crs/user" class="<?php echo $current_page == 'user' ? 'active' : ''; ?>">Users</a>
    <?php endif; ?>
    
    <a href="/westy-crs/logout">Logout</a>
</div>

