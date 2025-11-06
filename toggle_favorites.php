<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$design_id = $_POST['design_id'];
$action = $_POST['action'];

if ($action == 'add') {
    $conn->query("INSERT INTO favorites (user_id, design_id) VALUES ($user_id, $design_id)");
} elseif ($action == 'remove') {
    $conn->query("DELETE FROM favorites WHERE user_id = $user_id AND design_id = $design_id");
}

header("Location: user_home.php");
exit();
?>
