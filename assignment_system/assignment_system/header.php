<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Showcase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; background-color: #f8f9fa; }
        .main-content { flex: 1; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#"><i class="bi bi-mortarboard-fill me-2"></i>Portfolio Showcase</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'student'): ?>
            <li class="nav-item"><a class="nav-link" href="student_dashboard.php"><i class="bi bi-house-door me-1"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="submit_project.php"><i class="bi bi-upload me-1"></i>Submit Project</a></li>
            <li class="nav-item"><a class="nav-link" href="my_portfolio.php"><i class="bi bi-journal-bookmark me-1"></i>My Portfolio</a></li>
        <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php"><i class="bi bi-speedometer2 me-1"></i>Admin Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="admin_categories.php"><i class="bi bi-folder-plus me-1"></i>Categories</a></li>
            <li class="nav-item"><a class="nav-link" href="admin_submissions.php"><i class="bi bi-list-check me-1"></i>All Submissions</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['full_name']); ?> (<?= ucfirst($_SESSION['role']); ?>)
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php"><i class="bi bi-person-plus me-1"></i>Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container my-4 main-content">