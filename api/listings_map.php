<?php
require_once '../config.php';
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

header('Content-Type: application/json');

$apiKey = $_ENV['GOOGLE_MAPS_API_KEY'];

// get proeprties from database 
$stmt = $conn->prepare("SELECT id, title, address, city, postcode FROM properties WHERE address IS NOT NULL");
$stmt->execute();
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

$results = [];


//find geocode for porpertiues 
foreach ($properties as $property) {
    $fullAddress = urlencode("{$property['address']}, {$property['city']}, {$property['postcode']}");
    $geoUrl = "https://maps.googleapis.com/maps/api/geocode/json?address={$fullAddress}&key={$apiKey}";
    $geoResponse = file_get_contents($geoUrl);
    error_log("Geocoding URL: $geoUrl");
    error_log("Response: $geoResponse"); 

    $geoData = json_decode($geoResponse, true);

    if (!empty($geoData['results'][0]['geometry']['location'])) {
        $location = $geoData['results'][0]['geometry']['location'];
        $results[] = [
            'title' => $property['title'],
            'address' => "{$property['address']}, {$property['city']}, {$property['postcode']}",
            'latitude' => $location['lat'],
            'longitude' => $location['lng']
        ];
    }
}

echo json_encode($results);



//refrences 
//https://developers.google.com/maps/documentation/geocoding/start for geocoding adresses 

//https://developers.google.com/maps/documentation/javascript/adding-a-google-map java script api 

//https://code.tutsplus.com/tutorials/google-maps-api-3-for-beginners--cms-23145
