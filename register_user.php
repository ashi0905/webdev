<?php
include('db_connect.php');
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $portfolio_option = $_POST['portfolio_option'];

  // Determine role
  $role = ($portfolio_option == 'create') ? 'admin' : 'user';

  if ($password !== $confirm_password) {
    $message = "Passwords do not match!";
  } else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
      $_SESSION['username'] = $username;
      $_SESSION['role'] = $role;

      if ($role == 'admin') {
        header("Location: admin.php");
      } else {
        header("Location: home.php");
      }
      exit();
    } else {
      $message = "Error: " . $stmt->error;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Project Runaway</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="register_user.css"> <!-- link to your CSS -->
</head>
<body>


  <!-- 🌈 background and centering container -->
  <section class="gradient-custom-3">

    <!-- 🧍 registration card -->
    <div class="card p-4" style="width: 400px;">
      <h2 class="text-center mb-4">Create an account</h2>

      <form method="POST" action="register_user.php">
        <!-- Username -->
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>

        <!-- Role options -->
        <div class="mb-3">
          <label class="form-label">Choose your role:</label><br>
          <input type="radio" id="designer" name="role" value="designer" required>
          <label for="designer">Create my own portfolio/page</label><br>

          <input type="radio" id="viewer" name="role" value="viewer">
          <label for="viewer">Viewer only</label>
        </div>

        <!-- Terms of Service -->
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="terms" required>
          <label class="form-check-label" for="terms">
            I agree to the <a href="#">Terms of Service</a>
          </label>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-success w-100">Register</button>

        <p class="text-center mt-3">Already have an account? <a href="login_user.php"><strong>Login here</strong></a></p>
      </form>
    </div>

  </section> <!-- END of section -->

</body>
</html>

