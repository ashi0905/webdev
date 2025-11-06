<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$favorites = $conn->query("SELECT d.* FROM designs d 
    JOIN favorites f ON d.id = f.design_id 
    WHERE f.user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Favorites</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="favorites-container">
    <h2> My Favorites </h2>
    <div class="designs">
        <?php while($row = $favorites->fetch_assoc()): ?>
            <div class="design-card">
                <img src="uploads/<?php echo $row['image']; ?>" alt="Design">
                <h3><?php echo $row['title']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <form action="toggle_favorite.php" method="POST">
                    <input type="hidden" name="design_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="action" value="remove" class="unfav">💔 Remove Favorite</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
