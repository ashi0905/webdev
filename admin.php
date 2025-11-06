<?php
session_start();
include 'db_connect.php';

// Allow only admins
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: login_user.php");
  exit();
}

// Handle upload form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
  $title = $conn->real_escape_string($_POST['title']);
  $description = $conn->real_escape_string($_POST['description']);
  $uploaded_by = $conn->real_escape_string($_SESSION['user']['username']); // record uploader name
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["image"]["name"]);

  // Move file to uploads folder
  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    $sql = "INSERT INTO designs (title, description, image, uploaded_by, created_at)
            VALUES ('$title', '$description', '$target_file', '$uploaded_by', NOW())";
    if ($conn->query($sql)) {
      $success = "Design uploaded successfully!";
    } else {
      $error = "Database error: " . $conn->error;
    }
  } else {
    $error = "Error uploading file.";
  }
}

// Fetch all designs
$query = "SELECT * FROM designs ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Project Runaway</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      padding-top: 90px;
    }
    .card-img-top {
      height: 250px;
      object-fit: cover;
    }
  </style>
</head>
<body>

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 fixed-top">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand fw-bold text-dark" href="admin.php">
      <i class="bi bi-bag-heart-fill me-2 text-danger"></i> Project Runaway
    </a>

    <!-- Search Bar -->
    <form class="d-flex ms-auto me-3" role="search" action="search.php" method="GET">
      <input 
        class="form-control me-2" 
        type="search" 
        name="q" 
        placeholder="Search designer..." 
        aria-label="Search"
        style="width: 250px;"
      >
      <button class="btn btn-dark" type="submit">
        <i class="bi bi-search"></i>
      </button>
    </form>

    <!-- Profile Dropdown -->
    <div class="dropdown">
      <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
        <i class="bi bi-person-circle me-1"></i> 
        <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-upload me-2"></i> Upload
          </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <a class="dropdown-item text-danger" href="logout_user.php">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ✅ Main Content -->
<div class="container mt-5">
  <h2 class="fw-bold mb-4 text-center">
    <i class="bi bi-stars text-danger"></i> Admin Dashboard
  </h2>

  <?php if (isset($success)): ?>
    <div class="alert alert-success text-center"><?php echo $success; ?></div>
  <?php elseif (isset($error)): ?>
    <div class="alert alert-danger text-center"><?php echo $error; ?></div>
  <?php endif; ?>

  <h4 class="text-center mb-4">All Uploaded Designs</h4>
    <div class="row">
      <?php if($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <?php if(!empty($row['image'])): ?>
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    <?php else: ?>
                        <div class="p-5 text-center bg-light">No Image</div>
                    <?php endif; ?>
                    <div class="card-body text-center">
                        <h5 class="fw-bold"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <p class="small text-muted"><?php echo htmlspecialchars($row['uploaded_by']); ?></p>
                        <div class="d-flex justify-content-center">
                            <a href="update_admin.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm me-2">Edit</a>
                            <a href="delete_admin.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this design?')">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
    <p class="text-center text-muted">No designs uploaded yet.</p>
  <?php endif; ?>
</div>
</div>

<!-- ✅ Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadModalLabel">Upload New Design</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Upload Image</label>
          <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-dark">Upload</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
