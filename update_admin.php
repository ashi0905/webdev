<?php
session_start();
include 'db_connect.php';

// Only admin can access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login_user.php");
    exit();
}

// Get design ID
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch design details
$sql = "SELECT * FROM designs WHERE id = $id LIMIT 1";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    header("Location: admin.php");
    exit();
}

$design = $result->fetch_assoc();
$success = $error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);

    // Check if a new file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_sql = ", image='$target_file'";
        } else {
            $error = "Error uploading file.";
        }
    } else {
        $image_sql = "";
    }

    if (!$error) {
        $update_sql = "UPDATE designs SET title='$title', description='$description' $image_sql WHERE id=$id";
        if ($conn->query($update_sql)) {
            $success = "Design updated successfully!";
            // Refresh design data
            $result = $conn->query("SELECT * FROM designs WHERE id = $id LIMIT 1");
            $design = $result->fetch_assoc();
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Design - Project Runaway</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Edit Design</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($design['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($design['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <?php if (!empty($design['image'])): ?>
                <img src="<?php echo htmlspecialchars($design['image']); ?>" style="height:200px; object-fit:cover;">
            <?php else: ?>
                <p>No image uploaded</p>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload New Image (optional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-warning">Update Design</button>
        <a href="admin.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
