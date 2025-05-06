<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Explore Properties</title>
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="/FinalYearProject/navbar.css">

  <style>
    main.container {
      padding: 2rem 1rem;
      max-width: 1000px;
      margin: auto;
    }

    h1.page-title {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 1.5rem;
    }

    .search-result-info {
      margin-bottom: 1rem;
    }

    .results-section {
      width: 100%;
    }

    .property-list {
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }

    .no-results {
      text-align: center;
      color: #6b7280;
    }

    .search-box {
      display: flex;
      flex-direction: row;
      background: #fff;
      border-radius: 0.5rem;
      overflow: hidden;
      text-decoration: none;
      color: inherit;
      box-shadow: 0 0 0 1px #e5e7eb;
    }

    .property-image {
      flex: 0 0 300px;
      height: 200px;
      overflow: hidden;
    }

    .property-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .property-content {
      flex: 1;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .property-content h3 {
      margin-bottom: 0.25rem;
      font-size: 1.25rem;
      font-weight: 600;
    }

    .property-location,
    .property-description {
      font-size: 0.9rem;
      color: #6b7280;
    }

    .property-description {
      margin-bottom: 0.75rem;
    }

    .property-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .property-price {
      font-weight: bold;
      font-size: 1.125rem;
    }

    .btn-search {
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
    }

    .property-meta {
      margin-top: 0.75rem;
      display: flex;
      gap: 1rem;
      font-size: 0.8rem;
      color: #6b7280;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container">
  <h1 class="page-title">Explore Properties</h1>

  <?php
    include 'config.php';
    $search = $_GET['q'] ?? '';
    $stmt = $conn->prepare("
      SELECT p.*, 
             (SELECT image_path FROM property_images WHERE property_id = p.id LIMIT 1) AS image
      FROM properties p
      WHERE p.city LIKE :search OR p.county LIKE :search OR p.address LIKE :search
      ORDER BY p.created_at DESC
    ");
    $stmt->execute(['search' => '%' . $search . '%']);
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <?php if (!empty($search)): ?>
    <p class="search-result-info">Showing results for: <strong><?= htmlspecialchars($search) ?></strong></p>
  <?php endif; ?>

  <section class="results-section">
    <div class="property-list">
      <?php if (empty($properties)): ?>
        <div class="no-results">No listings found.</div>
      <?php else: ?>
        <?php foreach ($properties as $prop): ?>
          <?php $imagePath = $prop['image'] ?? 'assets/img/placeholder1.jpg'; ?>
          <a href="property.php?id=<?= $prop["id"] ?>" class="search-box">
            <div class="property-image">
              <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($prop["title"]) ?>" />
            </div>
            <div class="property-content">
              <div>
                <h3><?= htmlspecialchars($prop["title"]) ?></h3>
                <p class="property-location"><?= htmlspecialchars($prop["city"]) ?>, <?= htmlspecialchars($prop["county"]) ?></p>
                <p class="property-description"><?= htmlspecialchars($prop["description"]) ?></p>
              </div>
              <div class="property-footer">
                <span class="property-price">£<?= number_format($prop["price"]) ?></span>
                <span class="btn-search">View</span>
              </div>
              <div class="property-meta">
                <span><?= $prop["bedrooms"] ?> Beds</span>
                <span><?= $prop["bathrooms"] ?> Baths</span>
                <span><?= $prop["square_feet"] ?> sqft</span>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
