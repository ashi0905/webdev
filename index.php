<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Project Runaway</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="index.css">
</head>

<body>

  <!-- Include Navbar -->
  <?php include('navbar.php'); ?>

  <!-- Hero Section -->
  <section class="hero d-flex flex-column justify-content-center align-items-center text-center text-white">
    <div class="overlay"></div>
    <div class="hero-content">
      <h1 class="fw-bold mb-3">Because Every Day Deserves a Runway Moment</h1>
      <p class="mb-4">Discover stunning collections and bookmark your favorite designer looks.</p>
      <a href="home.php" class="btn btn-light px-4 py-2 fw-semibold">Explore Designs</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center py-3 bg-white shadow-sm">
    <small class="text-muted">&copy; <?php echo date('Y'); ?> Project Runaway. All rights reserved.</small>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
