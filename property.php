<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
  include 'navbar.php';
  echo '
    <main class="container">
      <h2>Login Required</h2>
      <p>You must be logged in to view property details.</p>
      <a href="/FinalYearProject/api/enter-email.php" class="btn">Go to Sign In</a>
    </main>';
  include 'footer.php';
  exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "Invalid property ID.";
  exit();
}

$propertyId = (int) $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->execute([$propertyId]);
$property = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$property) {
  echo "Property not found.";
  exit();
}

$imgStmt = $conn->prepare("SELECT image_path FROM property_images WHERE property_id = ?");
$imgStmt->execute([$propertyId]);
$images = $imgStmt->fetchAll(PDO::FETCH_COLUMN);

$userStmt = $conn->prepare("SELECT full_name AS name, email, phone FROM users WHERE id = ?");
$userStmt->execute([$property['user_id']]);
$lister = $userStmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <title><?= htmlspecialchars($property['title']) ?> | Home Direct</title>

  <link rel="stylesheet" href="assets/css/styles.css">

  <link rel="stylesheet" href="/FinalYearProject/navbar.css"> 

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />  
  <style>
    main { padding: 2rem 1rem; max-width: 1200px; margin: auto; }

    .carousel { position: relative; margin-bottom: 2rem; height: 500px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; }

    .carousel-image { max-height: 500px; width: 100%; object-fit: contain; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: opacity 0.5s ease; } 

    .carousel button { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none;  border-radius: 9999px; padding: 0.5rem 0.75rem; cursor: pointer; }
    .carousel button:first-of-type {  left: 10px; }
    .carousel button:last-of-type {  right: 10px; }

    .property-container {   max-width: 900px;   margin: auto; }
    .property-title {    font-size: 2rem;    font-weight: bold;   margin-bottom: 0.5rem; }
    .property-price {   font-size: 1.5rem; font-weight: bold;   color: #4c1d95;   margin-bottom: 1rem; }
    .property-description {    line-height: 1.8; font-size: 1rem; color: #374151;   margin-bottom: 1.5rem; }
    .property-meta, .property-features, .lister {   font-size: 0.95rem;    color: #4b5563;      margin-bottom: 1rem; }
    .property-features {    display: flex;   gap: 2rem;    font-weight: 500;    color: #1f2937;    align-items: center; margin-bottom: 2rem; }
    .lister { border-top: 1px solid #e5e7eb; padding-top: 1.5rem; }

    .ai-chatbot-widget {   position: fixed;  bottom:  24px; right:   24px;     z-index: 999; }
    .chat-toggle {  background-color: #8b5cf6;   color: white;    border: none;   border-radius:   9999px;   padding: 14px 18px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; }
    #chat-panel { display: none; position: absolute; bottom: 60px; right: 0; width: 320px; max-height: 400px; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 8px 16px rgba(0,0,0,0.15); overflow: hidden; display: flex; flex-direction: column; }
    #chat-panel > div:first-child { padding: 12px; font-weight: 600; border-bottom: 1px solid #e5e7eb; }
    #chat-log {  flex: 1;   padding: 12px;   overflow-y: auto;   font-size: 14px; }
    #chat-form {   display: flex;     border-top: 1px solid #e5e7eb; }
    #chat-input {    flex: 1;     padding: 10px;    border: none;   font-size: 14px; }
    #chat-form button {    background: #8b5cf6;    color: white;    border: none;    padding: 0 16px;   font-weight: bold; cursor: pointer; }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main>

  <div class="carousel">

    <?php if (!empty($images)): ?>


      <?php foreach ($images as $index => $img): ?>

        <img class="carousel-image" src="<?= htmlspecialchars($img) ?>" style="opacity: <?= $index === 0 ? '1' : '0' ?>;" />

      <?php endforeach; ?>

    <?php else: ?>

      <img src="assets/img/placeholder1.jpg" style="width: 100%; height: 100%; object-fit: cover;" />

    <?php endif; ?>


    <?php if (count($images) > 1): ?>

      <button onclick="prevImage()">❮</button>

      <button onclick="nextImage()">❯</button>

    <?php endif; ?>

  </div>

  <div class="property-container">

    <h1 class="property-title"><?= htmlspecialchars($property['title']) ?></h1>

    <p class="property-price">£<?= number_format($property['price']) ?></p>

    <p class="property-description"><?= nl2br(htmlspecialchars($property['description'])) ?></p>


    <div class="property-meta">

      <div><strong>City:</strong> <?= htmlspecialchars($property['city']) ?></div>

      <div><strong>County:</strong> <?= htmlspecialchars($property['county']) ?></div>

      <div><strong>Address:</strong> <?= htmlspecialchars($property['address'] ?? 'N/A') ?></div>

    </div>

    <div class="property-features">



      <span><i class="fa-solid fa-bed"></i> <?= $property['bedrooms'] ?></span>

      <span><i class="fa-solid fa-bath"></i> <?= $property['bathrooms'] ?></span>

      <span><i class="fa-solid fa-ruler-combined"></i> <?= $property['square_feet'] ?> sqft</span>

    </div>

    <div class="lister">
      <h2>Listed By</h2>
      <?php if ($lister): ?>



        <p><strong>Name:</strong> <?= htmlspecialchars($lister['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($lister['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($lister['phone']) ?></p>
      <?php else: ?>


        <p>Lister details unavailable.</p>


      <?php endif; ?>
    </div>
  </div>
</main>


<div class="ai-chatbot-widget">


  <button id="chat-toggle" class="chat-toggle">💬 AI Assistant</button>



  <div id="chat-panel">
    <div>Legal Draft Assistant</div>
    <div id="chat-log"></div>
    


    <form id="chat-form">
      <input type="text" id="chat-input" placeholder="e.g. Add completion deadline" />
      <button type="submit">></button>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>

<script>
  let current = 0;

  const images = document.querySelectorAll(".carousel-image");

  function showImage(index) {
    images.forEach((img, i) => img.style.opacity = (i === index) ? "1" : "0");
  }

  function nextImage() {
    current = (current + 1) % images.length;
    showImage(current);
  }

  function prevImage() {
    current = (current - 1 + images.length) % images.length;
    showImage(current);
  }

  const toggleBtn = document.getElementById("chat-toggle");
  const chatPanel = document.getElementById("chat-panel");
  const chatForm = document.getElementById("chat-form");
  const chatLog = document.getElementById("chat-log");
  const chatInput = document.getElementById("chat-input");

  toggleBtn.addEventListener("click", () => {
    chatPanel.style.display = chatPanel.style.display === "none" ? "flex" : "none";
  });

  chatForm.addEventListener("submit", async (e) => {


    e.preventDefault();
    const message = chatInput.value.trim();
    if (!message) return;

    chatLog.innerHTML += `<div><strong>You:</strong> ${message}</div>`;
    chatInput.value = "";

    const res = await fetch('/FinalYearProject/api/ai_chat.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
  message,
  property_id: <?= $propertyId ?>



})

    });

    const data = await res.json();
    let botMessage = `<div><strong>Bot:</strong> ${data.reply}</div>`;
if (data.pdf) {
  botMessage += `<div style="margin-top: 8px;"><a href="/FinalYearProject/api/${data.pdf}" target="_blank" style="color: #8b5cf6; font-weight: bold;">📄 Download Agreement (PDF)</a></div>`;
}



chatLog.innerHTML += botMessage;


    chatLog.scrollTop = chatLog.scrollHeight;
  });
</script>

</body>
</html>
