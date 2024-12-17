<?php

//TODO add user input sanitization
// Set the Content-Type header to indicate JSON response
header('Content-Type: application/json');

// Log a message to the console
//echo '<script>console.log("PHP script executed");</script>';

// Initialize the response array
$response = [];
//$response['form_type'] = 'Apt_new';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract data from the POST request
    $apartment_name = $_POST['apartment_name'];
    $county = $_POST['county'];
    $town = $_POST['town'];
    $landmark = $_POST['landmark'];

    // Your existing validation and database insertion logic goes here

    // Set success response data
    $response['success'] = true;
    $response['message'] = 'Apartment registered successfully.';
    // $response['apartment_id'] = $lastInsertedId;
    // $response['user_id'] = $user_id;

    // Output the JSON response

}

// Output the JSON response
echo json_encode($response);
exit();
