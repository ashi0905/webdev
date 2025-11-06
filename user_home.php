<?php
session_start();
include 'db_connect.php';

// Check DB connection
if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed: " . ($conn->connect_error ?? 'Unknown error'));
}

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all designs uploaded by admins
$designs = $conn->query("
    SELECT d.*, 
    (SELECT COUNT(*) FROM favorites f WHERE f.design_id = d.id AND f.user_id = $user_id) AS is_favorite 
    FROM designs d
    JOIN admins a ON d.admin_id = a.id
    ORDER BY d.created_at DESC
");

if (!$designs) {
    die("Error fetching designs: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Feed | Project Runaway</title>

  <!-- ✅ Add Bootstrap and Icons like in index.php -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- Your custom CSS -->
  <link rel="stylesheet" href="user_home.css">
</head>
<body>

  <!-- ✅ Include navbar -->
  <?php include 'navbar.php'; ?>

  <div class="feed-container">
    <h2>✨ Fashion Feed ✨</h2>

    <div class="designs">
      <?php if ($designs->num_rows > 0): ?>
        <?php while($row = $designs->fetch_assoc()): ?>
          <div class="design-card">
            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Design">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <form action="toggle_favorite.php" method="POST">
              <input type="hidden" name="design_id" value="<?php echo $row['id']; ?>">
              <?php if ($row['is_favorite']): ?>
                <button type="submit" name="action" value="remove" class="unfav">💔 Remove Favorite</button>
              <?php else: ?>
                <button type="submit" name="action" value="add" class="fav">❤️ Add to Favorites</button>
              <?php endif; ?>
            </form>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No designs found.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- ✅ Add Bootstrap JS bundle for dropdown to work -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
