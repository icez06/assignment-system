<?php
require_once 'db.php';
include 'header.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($full_name) || empty($email) || empty($password)) {
        $errors[] = "Sila isi semua ruangan.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format e-mel tidak sah.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Kata laluan tidak sepadan.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "E-mel ini telah didaftarkan.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $role = 'student';
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $role);
            if ($stmt->execute()) {
                echo "<script>alert('Pendaftaran berjaya! Sila log masuk.'); window.location='login.php';</script>";
                exit();
            }
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="text-center text-primary mb-3"><i class="bi bi-person-plus-fill me-2"></i>Create Account</h3>
                <?php foreach($errors as $err): ?>
                    <div class="alert alert-danger py-2 small"><?= $err; ?></div>
                <?php endforeach; ?>
                <form method="POST" onsubmit="return validateRegister()">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-person-plus me-1"></i>Register</button>
                </form>
                <div class="text-center mt-3">
                    <small>Already have an account? <a href="login.php">Log in here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateRegister() {
    let pass = document.getElementById('password').value;
    let confirmPass = document.getElementById('confirm_password').value;
    if (pass !== confirmPass) {
        alert("Sila pastikan kata laluan dan pengesahan kata laluan adalah sepadan!");
        return false;
    }
    return true;
}
</script>

<?php include 'footer.php'; ?>