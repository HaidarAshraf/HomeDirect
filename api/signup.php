<?php
require('../config.php'); 
session_start();

$error = '';
$email = $_GET['email'] ?? '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($fullName && $email && $phone && $password) {
        // check if the email exist 
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = "Email already exists. Please use another.";
        } else {
            // hide pass and save user data
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (full_name, email, phone, password) VALUES (:full, :email, :phone, :hash)");
            $insert->bindParam(':full', $fullName);
            $insert->bindParam(':email', $email);
            $insert->bindParam(':phone', $phone);
            $insert->bindParam(':hash', $hashed);
            $insert->execute();

            // save hte session
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['full_name'] = $fullName;

            // nacl to hjome
            header("Location: ../index.php");
            exit;
        }
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sign Up - Home Direct</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <link rel="stylesheet" href="navbar.css" />
  <style>
    body {
      background-color: #f9fafb;
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
    }

    .signup-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 2rem;
    }

    .signup-box {
      width: 100%;
      max-width: 400px;
      background: white;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    .signup-box h2 {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 0.5rem;
      text-align: center;
    }

    .signup-box p {
      font-size: 0.95rem;
      color: #6b7280;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .signup-box form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .signup-box input {
      padding: 0.75rem 1rem;
      border-radius: 999px;
      border: 1px solid #e5e7eb;
      font-size: 1rem;
      background-color: #fff;
    }

    .signup-box button {
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

    .signup-box button:hover {
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

<div class="signup-wrapper">
  <div class="signup-box">
    <h2>Create your account</h2>
    <p>Fill in your details to get started</p>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="full_name" placeholder="Full Name" required>
      <input type="tel" name="phone" placeholder="Phone Number" required>
      <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($email) ?>">
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Sign Up</button>
    </form>
  </div>
</div>

</body>
</html>
