<?php
require_once 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);
    $description = trim($_POST['description']);

    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $category_name, $description);
        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success py-2'>Kategori berjaya ditambah!</div>";
        }
    }
}

$categories = $conn->query("SELECT * FROM categories ORDER BY created_at DESC");
?>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 p-3">
            <h4 class="fw-bold text-primary mb-3"><i class="bi bi-folder-plus me-2"></i>Create Category</h4>
            <?= $msg; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="category_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>Add Category</button>
            </form>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card shadow-sm border-0 p-3">
            <h4 class="fw-bold mb-3">Existing Categories</h4>
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Category Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $categories->fetch_assoc()): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($row['category_name']); ?></td>
                        <td><small class="text-muted"><?= htmlspecialchars($row['description']); ?></small></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>