<?php
session_start();
include('db_connect.php');

// ✅ Allow only admin/designer users
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: home.php");
    exit();
}

$username = $_SESSION['username'] ?? 'Designer';
$message = '';

// ✅ Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $target_dir = "uploads/";

    // Create the folder if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allowed file types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $message = "File uploaded successfully!";
        } else {
            $message = "Oh no! Something went wrong during upload.";
        }
    } else {
        $message = "File type not allowed. Please upload an image or PDF.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Designer Dashboard | Project Runaway</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #84fab0, #8fd3f4);
      min-height: 100vh;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Welcome, <?php echo htmlspecialchars($username); ?> </h2>
        <div>
            <a href="home.php" class="btn btn-outline-light me-2"> Home</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="card p-4 mb-5">
        <h4 class="text-center mb-4">Upload Your Designs or Files</h4>

        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Select file to upload:</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-success px-4">Upload</button>
            </div>
        </form>
    </div>

    <h4 class="text-center mb-4"> Uploaded Files</h4>
    <div class="row">
        <?php
        $files = glob("uploads/*");
        if ($files) {
            foreach ($files as $file) {
                $filename = basename($file);
                echo '<div class="col-md-3 mb-4">';
                echo '<div class="card">';
                if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                    echo '<img src="'.$file.'" class="card-img-top" alt="'.$filename.'" style="height: 200px; object-fit: cover;">';
                } else {
                    echo '<div class="p-5 text-center bg-light">📄 PDF File</div>';
                }
                echo '<div class="card-body text-center">';
                echo '<p class="card-text text-truncate">'.$filename.'</p>';
                echo '<a href="'.$file.'" download class="btn btn-primary btn-sm me-2">Download</a>';
                echo '<a href="delete_file.php?file='.urlencode($file).'" class="btn btn-danger btn-sm" onclick="return confirm(\'Delete this file?\')">Delete</a>';
                echo '</div></div></div>';
            }
        } else {
            echo '<p class="text-center">No files uploaded yet.</p>';
        }
        ?>
    </div>
</div>
</body>
</html>


