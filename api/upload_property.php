<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id'])) {
    die('Unauthorized. Please login.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $square_feet = $_POST['square_feet'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $county = $_POST['county'];
    $postcode = $_POST['postcode'];

    try {
        $stmt = $conn->prepare("INSERT INTO properties (user_id, title, description, type, price, bedrooms, bathrooms, square_feet, address, city, county, postcode)
                                 VALUES (:user_id, :title, :description, :type, :price, :bedrooms, :bathrooms, :square_feet, :address, :city, :county, :postcode)");

        $stmt->execute([
            ':user_id' => $user_id,
            ':title' => $title,
            ':description' => $description,
            ':type' => $type,
            ':price' => $price,
            ':bedrooms' => $bedrooms,
            ':bathrooms' => $bathrooms,
            ':square_feet' => $square_feet,
            ':address' => $address,
            ':city' => $city,
            ':county' => $county,
            ':postcode' => $postcode
        ]);

        $property_id = $conn->lastInsertId(); 

        //file uploades
        if (!empty($_FILES['images']['name'][0])) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
                $fileName = basename($_FILES['images']['name'][$index]);
                $targetFile = $upload_dir . uniqid() . '_' . $fileName;

                if (move_uploaded_file($tmpName, $targetFile)) {
                    $relative_path = str_replace('../', '', $targetFile);


                    $stmt_image = $conn->prepare("INSERT INTO property_images (property_id, image_path) VALUES (:property_id, :image_path)");
                    $stmt_image->execute([
                        ':property_id' => $property_id,
                        ':image_path' => $relative_path
                    ]);
                }
            }
        }

        header('Location: ../success.php');
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
/*
refrences 
https://www.php.net/manual/en/book.session.php for sessions 
https://www.w3schools.com/php/php_mysql_prepared_statements.asp   for pdo 
*/

