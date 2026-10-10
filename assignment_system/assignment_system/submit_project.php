<?php
require_once 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $category_id = $_POST['category_id'];
    $tech_stack = trim($_POST['tech_stack']);
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];

    $file = $_FILES['documentation'];
    $allowed_exts = ['pdf', 'docx', 'zip', 'txt'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $max_size = 5 * 1024 * 1024; // 5MB Limit

    if (in_array($file_ext, $allowed_exts) && $file['size'] <= $max_size && $file['error'] === 0) {
        $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $destination = $upload_dir . $new_filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $stmt = $conn->prepare("INSERT INTO projects (user_id, category_id, title, description, tech_stack, file_path) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissss", $user_id, $category_id, $title, $description, $tech_stack, $destination);
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Projek berjaya dihantar!</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Gagal memuat naik fail ke pelayan.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Format fail tidak sah atau saiz melebihi 5MB.</div>";
    }
}

$categories = $conn->query("SELECT * FROM categories");
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 p-4">
            <h3 class="mb-3 text-primary fw-bold"><i class="bi bi-cloud-upload-fill me-2"></i>Submit Portfolio Project</h3>
            <?= $message; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Project Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <?php while($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id']; ?>"><?= htmlspecialchars($cat['category_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tech Stack (Comma Separated)</label>
                    <input type="text" name="tech_stack" class="form-control" placeholder="e.g. PHP, MySQL, Bootstrap 5, AJAX" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Project Description</label>
                    <textarea name="description" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Documentation (PDF, DOCX, ZIP, or TXT - Max 5MB)</label>
                    <input type="file" name="documentation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Submit Entry</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>