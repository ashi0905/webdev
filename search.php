<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Designers - Project Runaway</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- Optional custom CSS -->
  <link rel="stylesheet" href="index.css">
</head>
<body>

  <!-- Navbar -->
  <?php include('navbar.php'); ?>

  <div class="container mt-5 pt-5">
    <h3 class="fw-bold mb-4">Search Results</h3>

    <?php
    include('db_connect.php');
    if (isset($_GET['q']) && !empty($_GET['q'])) {
      $search = mysqli_real_escape_string($conn, $_GET['q']);
      $query = "SELECT * FROM designers WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
        echo '<div class="row">';
        while ($row = mysqli_fetch_assoc($result)) {
          echo '
          <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
              <img src="uploads/' . $row['image'] . '" class="card-img-top" alt="' . $row['name'] . '">
              <div class="card-body">
                <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>
                <p class="card-text">' . htmlspecialchars($row['description']) . '</p>
                <a href="designer.php?id=' . $row['id'] . '" class="btn btn-dark btn-sm">View Profile</a>
              </div>
            </div>
          </div>';
        }
        echo '</div>';
      } else {
        echo '<p>No designers found for "<strong>' . htmlspecialchars($search) . '</strong>".</p>';
      }
    } else {
      echo '<p>Please enter a search term.</p>';
    }
    ?>
  </div>

  <!-- Footer -->
  <footer class="text-center py-3 bg-white shadow-sm mt-5">
    <small class="text-muted">&copy; <?php echo date('Y'); ?> Project Runaway. All rights reserved.</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
