<?php
session_start();
include('db_connect.php');

$message = '';

// kapag may nagsubmit ng form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // hanapin user sa database
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    // check password
    if (password_verify($password, $user['password'])) {
      $_SESSION['user'] = $user;

      // redirect depende sa role
      if ($user['role'] === 'admin') {
        header("Location: admin_dashboard.php");
      } else {
        header("Location: home.php");
      }
      exit;
    } else {
      $message = "Invalid password!";
    }
  } else {
    $message = "No user found with that email!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Project Runaway</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    .gradient-custom-3 {
      background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));
    }
    .gradient-custom-4 {
      background: linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1));
    }
  </style>
</head>

<body>
<section class="vh-100 bg-image"
  style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px;">
            <div class="card-body p-5">
              <h2 class="text-uppercase text-center mb-5">Login to Your Account</h2>

              <?php if($message): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
              <?php endif; ?>

              <form method="POST" action="">

                <div class="form-outline mb-4">
                  <input type="email" name="email" id="formEmail" class="form-control form-control-lg" required />
                  <label class="form-label" for="formEmail">Your Email</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="password" name="password" id="formPassword" class="form-control form-control-lg" required />
                  <label class="form-label" for="formPassword">Password</label>
                </div>

                <div class="d-flex justify-content-center">
                  <button type="submit" class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Login</button>
                </div>

                <p class="text-center text-muted mt-5 mb-0">
                  Don’t have an account?
                  <a href="register_user.php" class="fw-bold text-body"><u>Register here</u></a>
                </p>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>
