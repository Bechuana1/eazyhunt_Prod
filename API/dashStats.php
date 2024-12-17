<?php
require_once '../config/config.php';
header('Content-Type: application/json');
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') { // Check if the request method is GET
    if (isset($_GET['action']) && $_GET['action'] == 'apt_registered') { //NOTE this si not yet used  we dont get the erro messages here
        $response['message'] = 'Apartment registered successfully.';
    }

if (!empty($_SESSION['id'])) {
    $stmt1 = $connect->prepare('SELECT apartments.apartment_id AS apt_id, apartments.name, COUNT(rooms.room_id) AS room_count, COUNT(apartments.apartment_id) AS total_apartments
          FROM apartments
          LEFT JOIN rooms ON apartments.apartment_id = rooms.apartment_id
          WHERE apartments.user_id = :user_id
          GROUP BY apartments.apartment_id');
    $stmt1->execute(
        array(':user_id' => $_SESSION['id'])
    );
    $apartments = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Access room_count for each apartment and calculate the total rooms
    $totalRooms = 0;
    foreach ($apartments as $apartment) {
        $room_count = $apartment['room_count'];
        $totalRooms += $room_count;
    }

    // Get the count of total apartments
    $totalApartments = $stmt1->rowCount();

    // Add the data to the response array
    $response['total_apartments'] = $totalApartments;
    $response['total_rooms'] = $totalRooms;
    $response['apartments'] = $apartments;
}
} else {
    $response['error'] = 'Unsupported request method. Only GET requests are allowed.';
}

// Encode the response array as JSON and send it
echo json_encode($response);
