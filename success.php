<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Success | HomeDirect</title>
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="/FinalYearProject/navbar.css">
  <style>
    .success-container {
      text-align: center;
      padding: 4rem 1rem;
      max-width: 600px;
      margin: 5rem auto;
      background: #f9fafb;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .success-container h1 {
      font-size: 2rem;
      color: #16a34a;
      margin-bottom: 1rem;
    }

    .success-container p {
      font-size: 1rem;
      color: #374151;
      margin-bottom: 2rem;
    }

    .success-container a {
      display: inline-block;
      background: #8b5cf6;
      color: #fff;
      padding: 0.75rem 1.5rem;
      border-radius: 999px;
      text-decoration: none;
      font-weight: 500;
      transition: background 0.2s ease;
    }

    .success-container a:hover {
      background: #7c3aed;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="success-container">
  <h1>Success!</h1>
  <p>Your action was completed successfully.</p>
  <a href="index.php">Go to Homepage</a>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
