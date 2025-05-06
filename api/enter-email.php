<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require('../config.php'); // This MUST create a PDO connection object called $conn
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json');

  $raw = file_get_contents("php://input");
  $data = json_decode($raw, true);
  $email = $data['email'] ?? '';

  if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Missing email']);
    exit;
  }

  // Use PDO syntax here
  $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();
  $exists = $stmt->rowCount() > 0;

  echo json_encode(['success' => true, 'exists' => $exists]);
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sign In - Home Direct</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <link rel="stylesheet" href="navbar.css" />
  <style>
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      background-color: var(--background, #f9f9f9);
    }

    .email-container {
      width: 100%;
      max-width: 400px;
      background: white;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
      text-align: center;
    }

    .email-container h2 {
      margin-bottom: 0.5rem;
      font-size: 1.5rem;
    }

    .email-container p {
      color: #6b7280;
      margin-bottom: 1.5rem;
    }

    #email-check-form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .input {
      padding: 0.75rem;
      font-size: 1rem;
      border-radius: 999px;
      border: 1px solid #e5e7eb;
      background-color: white;
      color: black;
    }

    .btn-search {
      padding: 0.75rem;
      font-size: 1rem;
      font-weight: 500;
      border-radius: 999px;
      background-color: #8b5cf6;
      color: white;
      border: none;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .btn-search:hover {
      background-color: #7c3aed;
    }
  </style>
</head>
<body>

<?php include('../navbar.php'); ?>

<div class="email-container">
  <p>Enter your email to continue</p>
  <form id="email-check-form">
    <input type="email" name="email" placeholder="Enter your email" required class="input" />
    <button type="submit" class="btn-search">Continue</button>
  </form>
</div>

<script>
document.getElementById('email-check-form').addEventListener('submit', async function (e) {
  e.preventDefault();
  const email = this.email.value.trim();

  try {
    const response = await fetch(window.location.href, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email })
    });

    const raw = await response.text();
    console.log("🔍 RAW RESPONSE:", raw);

    const data = JSON.parse(raw);
    if (data.success) {
      if (data.exists) {
        window.location.href = `login.php?email=${encodeURIComponent(email)}`;

      } else {
        window.location.href = `signup.php?email=${encodeURIComponent(email)}`;

      }
    } else {
      alert(data.message || "Something went wrong.");
    }
  } catch (err) {
    console.error("❌ Failed to parse JSON:", err);
    alert("Could not check email. Please try again.");
  }
});
</script>

</body>
</html>
