<?php
include './config/config.php';

$apartment_id = isset($_GET['apartment_id']) ? $_GET['apartment_id'] : 63;
$room_id = isset($_GET['room_id']) ? $_GET['room_id'] : 196;

if (!empty($room_id) && !empty($apartment_id)) {
    $query = "SELECT 
                      r.room_id as room_id,
                      GROUP_CONCAT(CONCAT('./', ri.image_url, '.webp') SEPARATOR ',') AS image_urls,
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

    // echo json_encode($fulldetails, );
} else {
    // echo json_encode(['error' => 'Missing room_id or apartment_id'], JSON_PRETTY_PRINT);
}


$roomtype = $fulldetails[0]['room_type'];
$more = 'SELECT 
            r.room_id AS room_id,
            GROUP_CONCAT(CONCAT("./", ri.image_url, ".webp") SEPARATOR ",") AS images,
            r.type AS room_type,
            a.apartment_id AS apartment_id,
            a.name AS apartment_name,
            u.user_id AS user_id,
            a.location AS location
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>More details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .carousel-item img {
            max-height: 300px; /* Limit the height of the images */
            object-fit: cover; /* Ensure the images cover the area without distortion */
        }
        .info-box {
            padding: 20px;
        }
        .features-box {
            padding: 20px;
        }
        .features-box .row div {
            padding: 5px 0;
        }
    </style>
</head>
<body>



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
    var roomsData = <?php echo json_encode($fulldetails); ?>;
    document.addEventListener("DOMContentLoaded", function() {
        var carouselContent = document.getElementById('carouselContent');
        var infoBox = document.getElementById('infoBox');
        var featuresBox = document.getElementById('featuresBox');

        roomsData.forEach(function(room, index) {
            var images = room.image_urls.split(',');

            images.forEach(function(image, imgIndex) {
                // Remove unnecessary spaces and fix paths
                image = image.trim().replace('./', '');
                
                var carouselItem = document.createElement('div');
                carouselItem.className = 'carousel-item' + (index === 0 && imgIndex === 0 ? ' active' : '');
                
                var imgElement = document.createElement('img');
                imgElement.src = image;
                imgElement.className = 'd-block w-100';
                
                carouselItem.appendChild(imgElement);
                carouselContent.appendChild(carouselItem);
            });

            if (index === 0) {
                var infoContent = `
                    <h4>${room.apartment_name}</h4>
                    <p><strong>Owner Name:</strong> ${room.landlord_name}</p>
                    <p><strong>Room Type:</strong> ${room.room_type}</p>
                    <p><strong>Landmark:</strong> ${room.landmark}</p>
                    <p><strong>Location:</strong> ${room.location}</p>
                `;
                infoBox.innerHTML = infoContent;

                // Adding property features in two columns
                var facilities = room.facilities.split(',');
                var featuresContent = '<div class="row">';
                for (var i = 0; i < facilities.length; i++) {
                    if (i % 2 === 0 && i !== 0) {
                        featuresContent += '</div><div class="row">';
                    }
                    featuresContent += '<div class="col-md-6">' + facilities[i].trim() + '</div>';
                }
                featuresContent += '</div>';
                featuresBox.innerHTML = '<h5>Property Features</h5>' + featuresContent;
            }
        });
    });
</script>
</body>
</html>
