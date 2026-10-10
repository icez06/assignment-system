<?php
require_once 'db.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = "SELECT p.*, c.category_name, u.full_name 
        FROM projects p 
        JOIN categories c ON p.category_id = c.id 
        JOIN users u ON p.user_id = u.id";

if (!empty($q)) {
    $sql .= " WHERE p.title LIKE ? OR p.tech_stack LIKE ? OR u.full_name LIKE ? ORDER BY p.created_at DESC";
    $stmt = $conn->prepare($sql);
    $param = "%{$q}%";
    $stmt->bind_param("sss", $param, $param, $param);
} else {
    $sql .= " ORDER BY p.created_at DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-primary"><?= htmlspecialchars($row['category_name']); ?></span>
                        <small class="text-muted"><i class="bi bi-clock me-1"></i><?= date('n/j/Y', strtotime($row['created_at'])); ?></small>
                    </div>
                    <h5 class="card-title fw-bold"><?= htmlspecialchars($row['title']); ?></h5>
                    <p class="card-text text-secondary small"><?= htmlspecialchars(substr($row['description'], 0, 90)) . '...'; ?></p>
                    <p class="mb-2"><small><strong>Tech Stack:</strong><br><span class="badge bg-light text-dark border"><?= htmlspecialchars($row['tech_stack']); ?></span></small></p>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="bi bi-person me-1"></i><?= htmlspecialchars($row['full_name']); ?></small>
                    <a href="<?= htmlspecialchars($row['file_path']); ?>" class="btn btn-sm btn-outline-primary" download><i class="bi bi-file-earmark-text me-1"></i>Report</a>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo '<div class="col-12 text-center text-muted py-5">Tiada projek ditemui.</div>';
}
?>