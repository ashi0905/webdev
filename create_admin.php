<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: home.php");
    exit();
}

$username = $_SESSION['username'] ?? 'Admin';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $uploaded_by = $username;

        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg','jpeg','png','gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO designs (title, description, image, uploaded_by) 
                        VALUES ('$title','$description','$image_name','$uploaded_by')";
                if ($conn->query($sql)) {
                    header("Location: admin.php");
                    exit;
                } else {
                    $message = "Database error: " . $conn->error;
                }
            } else {
                $message = "File upload failed.";
            }
        } else {
            $message = "Invalid file type. Only JPG, PNG, GIF allowed.";
        }
    } else {
        $message = "Please select a file to upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upload Design | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5 pt-5">
    <h2 class="mb-4 text-center">Upload New Design</h2>
    <?php if($message): ?>
        <div class="alert alert-info text-center"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <input type="text" name="title" class="form-control" placeholder="Title" required>
        </div>
        <div class="mb-3">
            <textarea name="description" class="form-control" rows="3" placeholder="Description" required></textarea>
        </div>
        <div class="mb-3">
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success">Upload</button>
        </div>
    </form>
</div>
</body>
</html>
