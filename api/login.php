<?php
require('../config.php');

session_start();

$error = '';
$email = $_GET['email'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($email && $password) {
    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['full_name'] = $user['full_name'];
      header("Location: ../index.php");
      exit;
    } else {
      $error = "Invalid email or password.";
    }
  } else {
    $error = "Please fill in both fields.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Log In - Home Direct</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <link rel="stylesheet" href="navbar.css" />
  <style>
    body {
      background-color: #f9fafb;
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
    }

    .login-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 2rem;
    }

    .login-box {
      width: 100%;
      max-width: 400px;
      background: white;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    .login-box h2 {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 0.5rem;
      text-align: center;
    }

    .login-box p {
      font-size: 0.95rem;
      color: #6b7280;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .login-box form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .login-box input {
      padding: 0.75rem 1rem;
      border-radius: 999px;
      border: 1px solid #e5e7eb;
      font-size: 1rem;
      background-color: #fff;
    }

    .login-box button {
      padding: 0.75rem 1rem;
      border-radius: 999px;
      font-size: 1rem;
      font-weight: 500;
      background-color: #8b5cf6;
      color: #fff;
      border: none;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .login-box button:hover {
      background-color: #7c3aed;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 1rem;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

<?php include('../navbar.php'); ?>

<div class="login-wrapper">
  <div class="login-box">
    <p>Log in to your account</p>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <input class="input" type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($email) ?>">
      <input class="input" type="password" name="password" placeholder="Password" required>
      <button class="btn-search" type="submit">Log In</button>
    </form>
  </div>
</div>

</body>
</html>
