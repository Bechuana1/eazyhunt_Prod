<?php
include './config/config.php';
$apartment_id = 3; //$_GET['apartment_id']
$room_id = 40; //$_GET['room_id'] fmcdskmfd;

if (!empty($room_id) && !empty($apartment_id)) {
    $query = "SELECT 
                      r.room_id as room_id,
                      GROUP_CONCAT(ri.image_url) AS images,
                      r.type AS room_type, 
                      r.price,
                      a.apartment_id AS apartment_id,
                      a.name AS apartment_name,
                      a.landmark As landmark,
                      a.facilities as facilities,
                      u.mobile_number AS phone,
                      u.full_name as landlord_name,
                      u.user_id AS user_id,
                      a.location AS `location`
                    FROM
                      rooms r
                      INNER JOIN apartments a ON r.apartment_id = a.apartment_id
                      INNER JOIN users u ON a.user_id = u.user_id
                      LEFT JOIN room_images ri ON r.room_id = ri.room_id
                    WHERE
                     r.room_id = :room_id
                    AND 
                     a.apartment_id = :apartment_id";

    $stmnt = $connect->prepare($query);
    $stmnt->bindParam(':room_id', $room_id, PDO::PARAM_INT);
    $stmnt->bindParam(':apartment_id', $apartment_id, PDO::PARAM_INT);
    $stmnt->execute();

    $fulldetails = $stmnt->fetchAll(PDO::FETCH_ASSOC);

    // Output the results in JSON format
    header('Content-Type: application/json');
    echo json_encode($fulldetails, JSON_PRETTY_PRINT);
} else {
    echo json_encode(['error' => 'Missing room_id or apartment_id'], JSON_PRETTY_PRINT);
}


$roomtype = $fulldetails[0]['room_type'];
$more = 'SELECT 
                r.room_id as room_id,
                GROUP_CONCAT(ri.image_url) AS images,
                r.type AS room_type,
                a.apartment_id AS apartment_id,
                a.name AS apartment_name,
                u.user_id AS user_id,
                a.location AS `location`
            FROM
                rooms r
            INNER JOIN apartments a    
                ON r.apartment_id = a.apartment_id
            INNER JOIN users u 
                ON a.user_id = u.user_id
            LEFT JOIN room_images ri 
                ON r.room_id = ri.room_id  
            WHERE r.type LIKE CONCAT("%", ?, "%") GROUP BY r.room_id limit 6';

$stmt = $connect->prepare($more);
$stmt->bindParam(1, $roomtype, PDO::PARAM_STR);
$stmt->execute();

$similarRooms = $stmt->fetchAll();



?>




</html>