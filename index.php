<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home Direct | Home</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <?php include 'navbar.php'; ?> 

  <main>

    <!-- hero -->
    <div class="hero-wrapper">
      <div class="hero-bg"></div>
      <div class="hero-container">
        <div class="hero-content">
          <h1 class="hero-title">Find Your Dream Home</h1>
          <p class="hero-subtitle">
            Discover beautiful properties in your area, connect with people, and find the perfect place to call home.
          </p>
          
          <!-- search bar  -->
          <div class="search-box">
            <form action="explore-properties.php" method="GET" class="search-grid">
              <div class="search-item wide">
                <input type="text" name="q" class="input" placeholder="Enter location, ZIP, or address" />
              </div>
              <div class="search-item">
                <button class="btn-search" type="submit">Search</button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>


    <!-- featured property -->
    <section class="featured-section">
      <div class="container">
        <h2 class="section-title">Featured Properties</h2>

        <div class="property-grid">
          <?php
            include 'config.php';

            $stmt = $conn->prepare("
              SELECT p.*, 
                (SELECT image_path FROM property_images WHERE property_id = p.id LIMIT 1) AS image
              FROM properties p
              ORDER BY created_at DESC
              LIMIT 6
            ");
            $stmt->execute();
            $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($properties as $property):
              $imagePath = $property['image'] ?? 'assets/img/placeholder.jpg';
              $type = isset($property['type']) && $property['type'] ? ucfirst($property['type']) : 'Property';
          ?>
            <div class="property-card">
              <div class="property-image-wrapper">
                <img
                  src="<?= htmlspecialchars($imagePath) ?>"
                  alt="<?= htmlspecialchars($property['title']) ?>"
                  class="property-image"
                />
                <div class="property-type"><?= htmlspecialchars($type) ?></div>
              </div>

              <div class="property-content">
                <div class="property-title"><?= htmlspecialchars($property['title']) ?></div>
                <div class="property-location">
                  <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 10c0 5-5.5 10.2-7.4 11.8a1 1 0 0 1-1.2 0C9.5 20.2 4 15 4 10a8 8 0 0 1 16 0"/>
                    <circle cx="12" cy="10" r="3"/>
                  </svg>
                  <?= htmlspecialchars($property['city'] . ', ' . $property['county']) ?>
                </div>
                <div class="property-desc"><?= htmlspecialchars($property['description']) ?></div>

                <div class="property-meta">
                  <span>🛏 <?= $property['bedrooms'] ?></span>
                  <span>🛁 <?= $property['bathrooms'] ?></span>
                  <span>📐 <?= $property['square_feet'] ?> sqft</span>
                </div>

                <div class="property-bottom">
                  <span class="property-price">£<?= number_format($property['price']) ?></span>
                  <a href="property.php?id=<?= $property['id'] ?>" class="btn-view">View Details</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="view-all-wrapper">
          <a href="explore-properties.php" class="btn-view-all">View All Properties</a>
        </div>
      </div>
    </section>

  </main>

  <?php include 'footer.php'; ?>

</body>
</html>

