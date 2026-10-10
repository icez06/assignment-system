<?php
require_once 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$total_students = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'student'")->fetch_assoc()['total'];
$total_categories = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];
$total_submissions = $conn->query("SELECT COUNT(*) AS total FROM projects")->fetch_assoc()['total'];

$recent = $conn->query("SELECT p.*, u.full_name, c.category_name FROM projects p JOIN users u ON p.user_id = u.id JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 5");
?>

<div class="alert alert-success alert-dismissible fade show">
    Welcome back, Admin!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<h2 class="fw-bold">Admin Overview</h2>
<p class="text-muted">Lecturer & Coordinator Evaluation Portal</p>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white p-3 shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-uppercase">STUDENTS</small>
                    <h2 class="fw-bold mb-0"><?= $total_students; ?></h2>
                </div>
                <i class="bi bi-people fs-1 opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white p-3 shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-uppercase">CATEGORIES</small>
                    <h2 class="fw-bold mb-0"><?= $total_categories; ?></h2>
                </div>
                <i class="bi bi-layers fs-1 opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white p-3 shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-uppercase">SUBMISSIONS</small>
                    <h2 class="fw-bold mb-0"><?= $total_submissions; ?></h2>
                </div>
                <i class="bi bi-folder2-open fs-1 opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<h4 class="fw-bold mb-3">Recent Submissions</h4>
<div class="table-responsive bg-white p-3 rounded shadow-sm">
    <table class="table align-middle">
        <thead class="table-light">
            <tr>
                <th>Student Name</th>
                <th>Title</th>
                <th>Category</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $recent->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']); ?></td>
                <td class="fw-semibold"><?= htmlspecialchars($row['title']); ?></td>
                <td><span class="badge bg-secondary"><?= htmlspecialchars($row['category_name']); ?></span></td>
                <td><?= date('d M Y', strtotime($row['created_at'])); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>