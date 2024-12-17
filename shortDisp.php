<?php
// Sample apartment data, replace with your actual data retrieval logic
$apartmentData = [
    "apartment_id" => 65,
    "user_id" => 31,
    "name" => "trial56",
    "county" => "Kiambu",
    "town" => "Juja",
    "landmark" => "near gateway butchery",
    "plot_number" => "",
    "location" => "Gate B",
    "latitude" => "-1.28122880",
    "longitude" => "36.81812480",
    "facilities" => "City Water,Cemented,Lock with key,CCTVs,Alarm,Generator,Caretaker,Tokens,kitchen shelves,balcony,Natural lighting",
    "created_at" => "2024-07-20 10:56:22",
    "updated_at" => "2024-07-20 10:56:22",
    "deleted" => 0
];

// HTML structure with formatted data and the map container
$formattedHtml = "
<div class='card-body'>
    <ul class='list-group list-group-flush'>
        <div class='card-body'>
            <ul class='list-group list-group-flush'>
                <li class='list-group-item d-flex justify-content-between'>
                    <span class='fw-bold'>Apartment Name:</span>
                    <span>{$apartmentData['name']}</span>
                </li>
                <li class='list-group-item d-flex justify-content-between'>
                    <span class='fw-bold'>County:</span>
                    <span>{$apartmentData['county']}</span>
                </li>
                <li class='list-group-item d-flex justify-content-between'>
                    <span class='fw-bold'>Town:</span>
                    <span>{$apartmentData['town']}</span>
                </li>
                <li class='list-group-item d-flex justify-content-between'>
                    <span class='fw-bold'>Landmark:</span>
                    <span>{$apartmentData['landmark']}</span>
                </li>
                <li class='list-group-item d-flex justify-content-between'>
                    <span class='fw-bold'>Plot Number:</span>
                    <span>{$apartmentData['plot_number']}</span>
                </li>
                <li class='list-group-item d-flex justify-content-between'>
                    <span class='fw-bold'>Location:</span>
                    <span>{$apartmentData['location']}</span>
                </li>
                <li class='list-group-item d-flex justify-content-between'>
                    <span class='fw-bold'>Facilities:</span>
                    <span>{$apartmentData['facilities']}</span>
                </li>
                <li class='list-group-item d-flex justify-content-between'>
                    <span class='fw-bold'>Created At:</span>
                    <span>{$apartmentData['created_at']}</span>
                </li>
                <li class='list-group-item d-flex justify-content-between'>
                    <span class='fw-bold'>Updated At:</span>
                    <span>{$apartmentData['updated_at']}</span>
                </li>
            </ul>
        </div>
    </ul>
</div>
<div id='map' style='height: 500px; width: 100%;'></div>
<div class='card-footer d-flex justify-content-end'>
    <a href='./list_apt.php' class='btn btn-outline-dark me-2 px-3'>Back</a>
    <a href='./edit_apt.php?apartment_id={$apartmentData['apartment_id']}' class='btn btn-success mx-2 px-4'>Edit</a>
</div>
";
echo $formattedHtml;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Location Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
// Apartment data
const apartmentData = <?php echo json_encode($apartmentData); ?>;

// Initialize the map and set its view to the apartment location
const map = L.map('map').setView([apartmentData.latitude, apartmentData.longitude], 13);

// Set up the OSM layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

// Add a marker at the apartment location
L.marker([apartmentData.latitude, apartmentData.longitude]).addTo(map)
    .bindPopup(`<b>${apartmentData.name}</b><br>${apartmentData.landmark}`)
    .openPopup();
</script>

</body>
</html>
