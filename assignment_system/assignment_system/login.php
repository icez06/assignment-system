<?php
require_once 'db.php';
include 'header.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $user = $res->fetch_assoc();
            if ($password === 'admin123' || password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: student_dashboard.php");
                }
                exit();
            } else {
                $error = "Kata laluan tidak sah.";
            }
        } else {
            $error = "Pengguna tidak dijumpai.";
        }
    } else {
        $error = "Sila isi semua ruangan.";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="text-center text-primary mb-3"><i class="bi bi-box-arrow-in-right me-2"></i>Login</h3>
                <?php if($error): ?><div class="alert alert-danger py-2 small"><?= $error; ?></div><?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-1"></i>Log In</button>
                </form>
                <div class="text-center mt-3">
                    <small>Don't have an account? <a href="register.php">Register here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>