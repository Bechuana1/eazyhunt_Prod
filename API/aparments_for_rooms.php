<?php
require '../config/config.php';
header('content-type: application/json');

if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

$responce = [];

if (!empty($_SESSION['id'])) {
    $stmt = $connect->prepare(' 
        SELECT apartment_id, name
        FROM apartments
        WHERE user_id = :user_id;
    ');
    $stmt->execute(array(':user_id' => $_SESSION['id']));
    $apartments = $stmt->fetchAll();

    $responce['apartments'] = $apartments;
    echo json_encode($responce);
}
