<?php
session_start();
include 'db_connect.php'; // database connection mo

// kunin lahat ng designers or posts nila
$query = "SELECT * FROM designers ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Feed - Project Runaway</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      transition: all 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .designer-img {
      height: 250px;
      object-fit: cover;
    }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?> <!-- optional kung hiwalay ang navbar mo -->

  <div class="container mt-5 pt-5">
    <h2 class="fw-bold mb-4 text-center">
      <i class="bi bi-stars text-danger"></i> Featured Designers
    </h2>

    <?php if ($result->num_rows > 0): ?>
      <div class="row">
        <?php while($row = $result->fetch_assoc()): ?>
          <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
              <img src="<?php echo $row['image']; ?>" class="card-img-top designer-img" alt="Designer Image">
              <div class="card-body">
                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($row['name']); ?></h5>
                <p class="card-text text-muted"><?php echo htmlspecialchars($row['description']); ?></p>
                
                <?php if(isset($_SESSION['user'])): ?>
                  <form action="bookmark.php" method="POST">
                    <input type="hidden" name="designer_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                      <i class="bi bi-heart"></i> Bookmark
                    </button>
                  </form>
                <?php else: ?>
                  <a href="login_user.php" class="btn btn-outline-dark btn-sm">
                    <i class="bi bi-person"></i> Login to Bookmark
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p class="text-muted text-center">No designers available yet.</p>
    <?php endif; ?>
  </div>
</body>
</html>
