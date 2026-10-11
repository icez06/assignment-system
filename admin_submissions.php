<?php
require_once 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT p.*, u.full_name, u.email, c.category_name 
        FROM projects p 
        JOIN users u ON p.user_id = u.id 
        JOIN categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC";

$submissions = $conn->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-list-check me-2 text-primary"></i>All Student Submissions</h3>
    <span class="badge bg-primary fs-6">Total: <?= $submissions->num_rows; ?> Projects</span>
</div>

<div class="table-responsive bg-white p-3 rounded shadow-sm">
    <table class="table align-middle">
        <thead class="table-dark">
            <tr>
                <th>Student Name</th>
                <th>Project Title</th>
                <th>Category</th>
                <th>Tech Stack</th>
                <th>Submitted File</th>
                <th>Date Submitted</th>
            </tr>
        </thead>
        <tbody>
            <?php if($submissions->num_rows > 0): ?>
                <?php while($row = $submissions->fetch_assoc()): ?>
                <tr>
                    <td>
                        <strong class="d-block"><?= htmlspecialchars($row['full_name']); ?></strong>
                        <small class="text-muted"><?= htmlspecialchars($row['email']); ?></small>
                    </td>
                    <td class="fw-bold text-primary"><?= htmlspecialchars($row['title']); ?></td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($row['category_name']); ?></span></td>
                    <td><small class="text-muted"><?= htmlspecialchars($row['tech_stack']); ?></small></td>
                    <td>
                        <a href="<?= htmlspecialchars($row['file_path']); ?>" class="btn btn-sm btn-outline-primary" download>
                            <i class="bi bi-file-earmark-arrow-down me-1"></i>File
                        </a>
                    </td>
                    <td><?= date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center text-muted">Tiada sebarang projek dihantar lagi.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>