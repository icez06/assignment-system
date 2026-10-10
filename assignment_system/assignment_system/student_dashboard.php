<?php
require_once 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
?>

<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>Welcome back, <?= htmlspecialchars($_SESSION['full_name']); ?>!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="fw-bold">Project Showcase Directory</h2>
        <p class="text-muted mb-0">Explore student portfolio submissions and FYP projects.</p>
    </div>
    <a href="submit_project.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Submit New Project</a>
</div>

<div class="mb-4">
    <div class="input-group input-group-lg shadow-sm">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Live search by project title, tech stack, or student name...">
    </div>
</div>

<div id="projectList" class="row g-3">
    <!-- Data projek dimuatkan secara AJAX di sini -->
</div>

<script>
function fetchProjects(query = '') {
    fetch('ajax_search.php?q=' + encodeURIComponent(query))
        .then(response => response.text())
        .then(data => {
            document.getElementById('projectList').innerHTML = data;
        });
}

document.getElementById('searchInput').addEventListener('keyup', function() {
    fetchProjects(this.value);
});

fetchProjects();
</script>

<?php include 'footer.php'; ?>