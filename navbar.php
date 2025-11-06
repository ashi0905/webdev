<?php
// Start session kung wala pa
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 fixed-top">
  <div class="container">
    <!-- Logo (goes to home feed / designers' posts) -->
    <a class="navbar-brand fw-bold text-dark" href="uhome.php">
      <i class="bi bi-bag-heart-fill me-2 text-danger"></i> Project Runaway
    </a>

    <!-- Search Bar -->
    <form class="d-flex ms-auto me-3" role="search" action="search.php" method="GET">
      <input 
        class="form-control me-2" 
        type="search" 
        name="q" 
        placeholder="Search designer..." 
        aria-label="Search"
        style="width: 250px;"
      >
      <button class="btn btn-dark" type="submit">
        <i class="bi bi-search"></i>
      </button>
    </form>

    <!-- Profile / Login -->
    <?php if(isset($_SESSION['user'])): ?>
      <div class="dropdown">
        <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <i class="bi bi-person-circle me-1"></i> 
          <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="favorites.php">
              <i class="bi bi-heart-fill text-danger me-2"></i> My Favorites
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="profile.php">
              <i class="bi bi-person-lines-fill me-2"></i> Profile
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item text-danger" href="logout_user.php">
              <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    <?php else: ?>
      <a href="login_user.php" class="btn btn-dark">
        <i class="bi bi-person-fill me-1"></i> Login
      </a>
    <?php endif; ?>
  </div>
</nav>
