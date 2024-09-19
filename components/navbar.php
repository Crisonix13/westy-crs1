<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid justify-content-end">
        <ul class="navbar-nav">
            <?php if (isset($_SESSION['user_logged_in'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="path_to_user_image.jpg" alt="" class="rounded-circle" width="30" height="30">
                        <?php echo $_SESSION['userID']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownProfile">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
