<?php
// map.php
session_start();
require_once 'config.php';
require_once 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Property Map | HomeDirect</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <style>
    #map {
      width: 100%;
      height: 600px;
      margin: 2rem 0;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
    }
    body {
      font-family: 'Inter', sans-serif;
    }
    main h1 {
      text-align: center;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container">
  <h1 style="font-size: 2rem; font-weight: bold; margin-top: 2rem;">Property Listings Map</h1>
  <div id="map"></div>
</main>

<?php include 'footer.php'; ?>

<script>
  async function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 6,
      center: { lat: 53.4808, lng: -2.2426 } 
    });

    try {
      const response = await fetch("/FinalYearProject/api/listings_map.php");
      const listings = await response.json();

      listings.forEach(property => {
        if (!property.latitude || !property.longitude) return;

        const marker = new google.maps.Marker({
          position: {
            lat: parseFloat(property.latitude),
            lng: parseFloat(property.longitude)
          },
          map,
          title: property.title
        });

        const info = new google.maps.InfoWindow({
          content: `<strong>${property.title}</strong><br>${property.address}`
        });

        marker.addListener('click', () => info.open(map, marker));
      });

    } catch (error) {
      console.error("Error loading map markers:", error);
    }
  }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= $_ENV['GOOGLE_MAPS_API_KEY']; ?>&callback=initMap" async defer></script>
</body>
</html>
