<?php
include('db_connect.php');
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $role = $_POST['role']; // get directly from radio buttons

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
      $_SESSION['user_id'] = $stmt->insert_id;

      // redirect based on role
      if ($role == 'admin' || $role == 'designer') {
        header("Location: admin.php");
      } else {
        header("Location: uhome.php");
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
  <link rel="stylesheet" href="register_user.css">
</head>
<body>

<section class="gradient-custom-3 d-flex justify-content-center align-items-center vh-100">
  <div class="card p-4" style="width: 400px; border-radius: 15px;">
    <h2 class="text-center mb-4">Create an account</h2>

    <?php if ($message): ?>
      <div class="alert alert-danger text-center"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="register_user.php">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Choose your role:</label><br>
        <input type="radio" id="designer" name="role" value="admin" required>
        <label for="designer">Create my own portfolio/page</label><br>

        <input type="radio" id="viewer" name="role" value="user">
        <label for="viewer">Viewer only</label>
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="terms" required>
        <label class="form-check-label" for="terms">
          I agree to the <a href="#">Terms of Service</a>
        </label>
      </div>

      <button type="submit" class="btn btn-success w-100">Register</button>

      <p class="text-center mt-3">
        Already have an account? 
        <a href="login_user.php"><strong>Login here</strong></a>
      </p>
    </form>
  </div>
</section>

</body>
</html>
