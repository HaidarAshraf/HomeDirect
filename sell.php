<?php
session_start();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sell Page - HomeDirect</title>
  <link rel="stylesheet" href="/FinalYearProject/navbar.css">
  <style>
    :root {
      --primary: #8b5cf6;
      --primary-hover: #7c3aed;
      --muted: #6b7280;
      --border: #e5e7eb;
      --background: #ffffff;
      --foreground: #000000;
    }

    body {
      font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
      margin: 0;
      padding: 0;
      background: var(--background);
      color: var(--foreground);
      padding-top: 3.5rem;
    }

    .container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1rem;
    }

    .tabs {
      background: #f9fafb;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      border-radius: 0.5rem;
      overflow: hidden;
      margin-bottom: 2rem;
    }

    .tab-button {
      padding: 0.75rem;
      text-align: center;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.2s;
      font-size: 0.875rem;
      color: var(--muted);
      border: none;
      background: none;
    }

    .tab-button.active {
      background: var(--background);
      color: var(--foreground);
      font-weight: 600;
      box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .card {
      background: white;
      border: 1px solid var(--border);
      border-radius: 0.5rem;
      box-shadow: 0 1px 2px rgba(0,0,0,0.05);
      padding: 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      font-weight: 500;
      margin-bottom: 0.5rem;
      display: block;
      font-size: 0.875rem;
    }

    .form-input, .form-select, .form-textarea {
      width: 100%;
      padding: 0.5rem;
      font-size: 0.875rem;
      border: 1px solid var(--border);
      border-radius: 0.375rem;
      background: var(--background);
    }

    .form-textarea {
      min-height: 100px;
    }

    .upload-area {
      border: 2px dashed var(--border);
      padding: 2rem;
      border-radius: 0.5rem;
      text-align: center;
      background: #f9fafb;
      margin-top: 0.5rem;
    }

    .btn-primary {
      background: var(--primary);
      color: white;
      padding: 0.75rem;
      width: 100%;
      border: none;
      border-radius: 0.375rem;
      font-weight: 600;
      font-size: 0.875rem;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-primary:hover {
      background: var(--primary-hover);
    }

    .btn-destructive {
      background: #ef4444;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      cursor: pointer;
    }

    .btn-destructive:hover {
      background: #dc2626;
    }

    .hidden {
      display: none;
    }

    .grid {
      display: grid;
      gap: 1rem;
    }

    .grid-cols-2 {
      grid-template-columns: repeat(2, 1fr);
    }

    .grid-cols-3 {
      grid-template-columns: repeat(3, 1fr);
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<?php if (!isset($_SESSION['user_id'])): ?>
  <div class="container">
    <div class="card">
      <h2>Please Sign In to List a Property</h2>
      <p>You need to be logged in to access this page.</p>
      <button class="btn-primary" onclick="window.location.href='/FinalYearProject/api/enter-email.php'">Sign In</button>
    </div>
  </div>
<?php else: ?>
  <div class="container">
    <div class="tabs">
      <button id="tab-list" class="tab-button active" onclick="showTab('list')">List Your Property</button>
      <button id="tab-mylistings" class="tab-button" onclick="showTab('listings')">My Listings</button>
    </div>

    <div id="list-property-tab">
      <div class="card">
        <form action="api/upload_property.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label class="form-label">Property Title</label>
            <input type="text" name="title" class="form-input" required>
          </div>

          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-textarea" required></textarea>
          </div>

          <div class="form-group grid grid-cols-2">
            <div>
              <label class="form-label">Property Type</label>
              <input type="text" name="type" class="form-input" placeholder="e.g. House, Apartment, Studio" required>
            </div>
            <div>
              <label class="form-label">Price (£)</label>
              <input type="number" name="price" class="form-input" required>
            </div>
          </div>

          <div class="form-group grid grid-cols-3">
            <div>
              <label class="form-label">Bedrooms</label>
              <input type="number" name="bedrooms" class="form-input" placeholder="e.g. 3" min="0" required>
            </div>
            <div>
              <label class="form-label">Bathrooms</label>
              <input type="number" name="bathrooms" class="form-input" placeholder="e.g. 2" min="0" step="0.5" required>
            </div>
            <div>
              <label class="form-label">Square Feet</label>
              <input type="number" name="square_feet" class="form-input" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Street Address</label>
            <input type="text" name="address" class="form-input" required>
          </div>

          <div class="form-group grid grid-cols-3">
            <div>
              <label class="form-label">City</label>
              <input type="text" name="city" class="form-input" required>
            </div>
            <div>
              <label class="form-label">County</label>
              <input type="text" name="county" class="form-input" required>
            </div>
            <div>
              <label class="form-label">Postcode</label>
              <input type="text" name="postcode" class="form-input" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Upload Images</label>
            <div class="upload-area">
              <p>Drag & drop images or click to upload</p>
              <input type="file" name="images[]" accept="image/*" multiple required>
            </div>
          </div>

          <button type="submit" class="btn-primary">Submit Listing</button>
        </form>
      </div>
    </div>

    <div id="my-listings-tab" class="hidden">
      <div class="card">
        <h2 class="card-title">My Listings</h2>
        <?php
          $userId = $_SESSION['user_id'];
          $stmt = $conn->prepare("SELECT * FROM properties WHERE user_id = :user_id ORDER BY created_at DESC");
          $stmt->execute(['user_id' => $userId]);
          $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if (count($properties) === 0) {
            echo "<p>You haven't listed any properties yet.</p>";
          } else {
            foreach ($properties as $property) {
              echo "<div style='border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;'>";
              echo "<h3 style='margin: 0 0 0.5rem 0; font-size: 1.1rem; font-weight: 600;'>" . htmlspecialchars($property['title']) . "</h3>";
              echo "<p style='margin: 0 0 0.5rem 0; color: #6b7280;'>" . nl2br(htmlspecialchars($property['description'])) . "</p>";
              echo "<strong>£" . number_format($property['price']) . "</strong>";
              echo "<form action='api/delete_property.php' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this property?\")' style='margin-top: 0.75rem;'>";
              echo "<input type='hidden' name='id' value='" . $property['id'] . "'>";
              echo "<button type='submit' class='btn-destructive'>Delete</button>";
              echo "</form>";
              echo "</div>";
            }
          }
        ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<script>
function showTab(tab) {
  const listTab = document.getElementById('list-property-tab');
  const listingsTab = document.getElementById('my-listings-tab');
  const listButton = document.getElementById('tab-list');
  const listingsButton = document.getElementById('tab-mylistings');

  if (tab === 'list') {
    listTab.classList.remove('hidden');
    listingsTab.classList.add('hidden');
    listButton.classList.add('active');
    listingsButton.classList.remove('active');
  } else {
    listTab.classList.add('hidden');
    listingsTab.classList.remove('hidden');
    listButton.classList.remove('active');
    listingsButton.classList.add('active');
  }
}
</script>

<?php include 'footer.php'; ?>
</body>
</html>
