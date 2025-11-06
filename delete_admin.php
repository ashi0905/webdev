<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: home.php");
    exit();
}

$id = $_GET['id'] ?? 0;

// Optional: delete image file from uploads folder
$result = $conn->query("SELECT image FROM designs WHERE id=$id");
if($result && $row = $result->fetch_assoc()){
    $file = "uploads/".$row['image'];
    if(file_exists($file)) unlink($file);
}

// Delete from database
$conn->query("DELETE FROM designs WHERE id=$id");
header("Location: admin.php");
exit;
?>
