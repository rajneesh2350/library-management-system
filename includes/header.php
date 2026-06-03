<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/style.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-book-open"></i> Library MS</h3>
        </div>
        <div class="sidebar-menu">
            <a href="<?php echo BASE_URL; ?>dashboard.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo BASE_URL; ?>modules/members/index.php" class="sidebar-item <?php echo strpos($_SERVER['PHP_SELF'], 'members') !== false ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Library Members</span>
            </a>
            <a href="<?php echo BASE_URL; ?>modules/books/index.php" class="sidebar-item <?php echo strpos($_SERVER['PHP_SELF'], 'books') !== false ? 'active' : ''; ?>">
                <i class="fas fa-book"></i>
                <span>Books</span>
            </a>
            <a href="<?php echo BASE_URL; ?>modules/transactions/index.php" class="sidebar-item <?php echo strpos($_SERVER['PHP_SELF'], 'transactions') !== false ? 'active' : ''; ?>">
                <i class="fas fa-exchange-alt"></i>
                <span>Transactions</span>
            </a>
            <a href="<?php echo BASE_URL; ?>modules/transactions/issue.php" class="sidebar-item">
                <i class="fas fa-hand-holding-heart"></i>
                <span>Issue Book</span>
            </a>
            <a href="<?php echo BASE_URL; ?>modules/transactions/return.php" class="sidebar-item">
                <i class="fas fa-undo-alt"></i>
                <span>Return Book</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar d-flex justify-content-between align-items-center">
            <button id="sidebarToggle" class="btn btn-link d-md-none">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="mb-0">Welcome, Librarian</h5>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                    <i class="fas fa-user-circle fa-2x"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>