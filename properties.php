<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Explore Properties</title>
  <link rel="stylesheet" href="/FinalYearProject/navbar.css">
  <style>



    .explore-container {
      padding: 2rem 1rem;
      max-width: 1000px;
      margin: auto;
    }

    .explore-container h1 {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 1.5rem;
    }





    .property-list {
      display: flex;
      flex-direction: column;
      gap: 2rem;
      width: 100%;
    }

    .property-card {
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

    .property-title {
      margin-bottom: 0.25rem;
      font-size: 1.25rem;
      font-weight: 600;
    }

    .property-meta,
    .property-description {
      font-size: 0.9rem;
      color: #6b7280;
      margin-bottom: 0.5rem;
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

    .property-stats {
      margin-top: 0.75rem;
      display: flex;
      gap: 1rem;
      font-size: 0.8rem;
      color: #6b7280;
    }




    .no-listings {
      text-align: center;
      color: #6b7280;
      font-size: 1rem;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>




<main class="explore-container">
  <h1>Explore Properties</h1>

  <section>
    <div class="property-list">
      <?php
        include 'config.php';
        $stmt = $conn->prepare("
          SELECT p.*, 
                (SELECT image_path FROM property_images WHERE property_id = p.id LIMIT 1) AS image
          FROM properties p
          ORDER BY created_at DESC
        ");
        $stmt->execute();
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($properties)) {
          echo '<div class="no-listings">No listings available.</div>';
        } else {
          foreach ($properties as $prop) {
            $imagePath = $prop["image"] ?? 'assets/img/placeholder1.jpg';
            echo '
            <a href="property.php?id=' . $prop["id"] . '" class="property-card">
              <div class="property-image">
                <img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($prop["title"]) . '" />
              </div>
              <div class="property-content">
                <div>
                  <h3 class="property-title">' . htmlspecialchars($prop["title"]) . '</h3>
                  <p class="property-meta">' . htmlspecialchars($prop["city"]) . ', ' . htmlspecialchars($prop["county"]) . '</p>
                  <p class="property-description">' . htmlspecialchars($prop["description"]) . '</p>
                </div>
                <div class="property-footer">
                  <span class="property-price">£' . number_format($prop["price"]) . '</span>
                  <span class="btn-search" style="padding: 0.5rem 1rem; font-size: 0.875rem;">View</span>
                </div>
                <div class="property-stats">
                  <span>' . $prop["bedrooms"] . ' Beds</span>
                  <span>' . $prop["bathrooms"] . ' Baths</span>
                  <span>' . $prop["square_feet"] . ' sqft</span>
                </div>
              </div>
            </a>';
          }
        }
      ?>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
