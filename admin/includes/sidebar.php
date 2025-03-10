<!-- Admin Sidebar -->
<div class="sidebar col-md-3 col-lg-2 d-md-block bg-dark sidebar">
    <div class="position-sticky pt-3">
        <div class="sidebar-brand text-center mb-4">
            <a href="dashboard.php" class="navbar-brand text-white">
                <img src="../assets/img/polyeduhub-logo.png" alt="PolyEduHub Logo" height="40">
                Admin Panel
            </a>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'resources') !== false ? 'active' : '' ?>" href="resources/index.php">
                    <i class="fas fa-folder"></i> Resource Management
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'users') !== false ? 'active' : '' ?>" href="users/index.php">
                    <i class="fas fa-users"></i> User Management
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'categories') !== false ? 'active' : '' ?>" href="resources/categories.php">
                    <i class="fas fa-tags"></i> Categories
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'gamification') !== false ? 'active' : '' ?>" href="gamification/index.php">
                    <i class="fas fa-trophy"></i> Gamification
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'reports') !== false ? 'active' : '' ?>" href="reports/index.php">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'settings') !== false ? 'active' : '' ?>" href="settings/index.php">
                    <i class="fas fa-cogs"></i> System Settings
                </a>
            </li>

            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link text-danger" href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    // Optional: Toggle sidebar on smaller screens
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    });
</script>