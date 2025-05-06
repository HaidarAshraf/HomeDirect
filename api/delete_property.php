<?php
session_start();
require '../config.php';


// checks iof user is logged in firtst then deletes the propety they want 
if (!isset($_SESSION['user_id']) || !isset($_POST['id'])) {
  header("Location: ../sell.php");
  exit;
}

$propertyId = $_POST['id'];
$userId = $_SESSION['user_id'];

// Only delete if the property belongs to the logged-in user
$stmt = $conn->prepare("DELETE FROM properties WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $propertyId, 'user_id' => $userId]);

header("Location: ../sell.php");
exit;
