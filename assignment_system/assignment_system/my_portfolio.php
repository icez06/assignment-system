<?php
require_once 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT p.*, c.category_name FROM projects p JOIN categories c ON p.category_id = c.id WHERE p.user_id = ? ORDER BY p.created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$submissions = $stmt->get_result();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-archive-fill me-2 text-primary"></i>My Portfolio Submissions</h3>
    <a href="submit_project.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Submission</a>
</div>

<div class="table-responsive bg-white p-3 rounded shadow-sm">
    <table class="table align-middle">
        <thead class="table-light">
            <tr>
                <th>Project Title</th>
                <th>Category</th>
                <th>Tech Stack</th>
                <th>File</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if($submissions->num_rows > 0): ?>
                <?php while($row = $submissions->fetch_assoc()): ?>
                <tr>
                    <td class="fw-bold text-primary"><?= htmlspecialchars($row['title']); ?></td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($row['category_name']); ?></span></td>
                    <td><small class="text-muted"><?= htmlspecialchars($row['tech_stack']); ?></small></td>
                    <td><a href="<?= htmlspecialchars($row['file_path']); ?>" class="btn btn-sm btn-outline-secondary" download><i class="bi bi-download me-1"></i>Download</a></td>
                    <td><?= date('d M Y', strtotime($row['created_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center text-muted">Belum ada sebarang penghantaran projek.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>