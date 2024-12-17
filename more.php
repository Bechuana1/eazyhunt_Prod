<?php
include './config/config.php';

$apartment_id = isset($_GET['apartment_id']) ? $_GET['apartment_id'] : null;
$room_id = isset($_GET['room_id']) ? $_GET['room_id'] : null;

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
                      a.latitude AS latitude,
                      a.longitude AS longitude,
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
                     AND a.apartment_id = :apartment_id
                     AND a.deleted = 0";

    $stmnt = $connect->prepare($query);
    $stmnt->bindParam(':room_id', $room_id, PDO::PARAM_INT);
    $stmnt->bindParam(':apartment_id', $apartment_id, PDO::PARAM_INT);
    $stmnt->execute();

    $fulldetails = $stmnt->fetchAll(PDO::FETCH_ASSOC);

    // echo json_encode($fulldetails);
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
            WHERE r.type LIKE CONCAT("%", ?, "%") 
            AND a.deleted = 0
            GROUP BY r.room_id limit 6';

$stmt = $connect->prepare($more);
$stmt->bindParam(1, $roomtype, PDO::PARAM_STR);
$stmt->execute();

$similarRooms = $stmt->fetchAll();

// echo json_encode($similarRooms);
?>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="EazyHunt is a rental listing website targeting JKUAT main campus in Juja, Kenya. Find affordable bed sitters, single rooms, and double rooms, as well as 1, 2, and 3-bedroom apartments. Get the fastest response and secure your rental today.">
    <meta name="keywords" content="EazyHunt.co.ke, EazyHunt, EazyHunt.co, rentals, Juja, Kenya, JKUAT, bed sitters, single rooms, double rooms, 1 bedroom, 2 bedrooms, 3 bedrooms, affordable, fastest response">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EazyHunt - Your Rental Listing Solution</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <style>

    .detail-box {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    
        text-align: center;
    }
    .detail-box .heading_container h2 {
        margin-bottom: 20px;
    }
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
        
        #map {
            height: 250px; /* Adjust the height as needed */
        }
  
        
</style>
</head>

<body>
  <div class="container" style="padding: 0;">
    <header class="navbar navbar-expand-lg navbar-light bg-light" style="padding: 5px;">
      <div class="container-fluid">

        <a class="navbar-brand" href="#">
          <img src="./assets/images/fav.svg" width="30" height="30" alt="">
          EazyHunt

        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end ml-2" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="index.php#search">Search</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./pricing.php">Pricing</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#contact">FAQs</a>
            </li>
            <li class="nav-item">
              <a href="./auth/login.php"><button type="button" class="btn btn-outline-success">Login</button></a>
            </li>
            <li class="nav-item">
              <a href="./auth/register.php"><button type="button" class="btn btn-success ml-2">Sign-up</button></a>
            </li>
          </ul>
        </div>
      </div>
    </header>
  </div>



  <!-- end find section -->


  <!-- <section id="about" style="margin-top: 18px;">

    <div class="container">
      <div class="row">
    
        <div class="col-md-6">
            <div class="detail-box">
                <div class="heading_container">
                    <h2>F
                        More Details
                    </h2>
                </div>
            </div>
        </div>
      </div>
    </div>
  </section> -->

  <!-- end about section -->
  <section class="contact_section " id="contact">
    <div class="container">
        <div class="container mt-5 mb-1">
            <div class="row">
                <div class="col-md-8">
                    <div id="roomCarousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner" id="carouselContent"></div>
                        <a class="carousel-control-prev" href="#roomCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#roomCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box" id="infoBox"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-9">
                        <div id='map' style='width: 100%;'></div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="features-box" id="featuresBox"></div>
                </div>
            </div>
            <div class="alert alert-warning text-center mt-2" role="alert">
                <h4 class="alert-heading">Important Safety Tips</h4>
                <p>The following safety tips should be observed:</p>
                <ul class="TEXT-LEFT">
                    <li>Only pay the rental fee after verifying the agent/landlord and physically viewing the room and/or apartment in question.</li>
                    <li>There are no agents representing Eazy Hunt, so don't be deceived.</li>
                    <li>Eazy Hunt is not liable for any money transactions made between you and the agent/owner. Eazy Hunt acts solely as a platform for this advertisement and is not involved in the renting of the property.</li>
                    <li>Eazy Hunt is merely facilitating the communication of this property offer and does not provide any guarantees regarding the offers listed on the site.</li>
                    <li>The property descriptions and other details provided on our website are intended for informational and marketing purposes only. While we display them in good faith, we do not accept any responsibility for inaccuracies under any circumstances.</li>
                    <li>Prospective tenants are responsible for verifying the accuracy of property descriptions, and agents/owners are responsible for ensuring the accuracy and integrity of the property descriptions provided on Eazy Hunt's website.</li>
                    <li>Lastly, Eazy Hunt is not responsible for the actions of agents/owners in their interactions with users, whether on or off the Eazy Hunt website.</li>
                </ul>
                <hr>
                <p class="mb-0">Please ensure you follow these guidelines to avoid any issues.</p>
            </div>

        </div>
        
    </div>
  </section>


 


  <!-- end contact section -->

  <!-- info section -->
  <section class="info_section" id="info">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <div class="info_contact">
            <h5>
              About Apartment
            </h5>
            <div>
              <div class="img-box">
                <img src="./assets/images/location.png" width="18px" alt="">
              </div>
              <p>
                Address
              </p>
            </div>
            <div>
              <div class="img-box">
                <img src="./assets/images/phone.png" width="12px" alt="">
              </div>
              <p>
                +1234567890
              </p>
            </div>
            <div>
              <div class="img-box">
                <img src="./assets/images/mail.png" width="18px" alt="">
              </div>
              <p>
                info@eazyhunt.co.ke
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info_info">
            <h5>
              Information
            </h5>
            <p>
              Your desired house at the touch of a button. No hassle, we worry, so that you DON'T. We gat you.
            </p>
          </div>
        </div>

        <div class="col-md-3">
          <div class="info_links">
            <h5>
              Useful Link
            </h5>
            <ul>
              <li>
                <a href="#">
                  Terms And Conditions
                </a>
              </li>
              <li>
                <a href="./landlord/faqs.html">
                  FAQ's
                </a>
              </li>
              <li>
                <a href="#">
                  privacy Policies
                </a>
              </li>
              <li>
                <a href="#">
                  Customer Support
                </a>
              </li>
              <li>
                <a href="#">
                  About US
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info_form ">
            <h5>
              Newsletter
            </h5>
            <form action="">
              <input type="email" placeholder="Enter your email">
              <button>
                Subscribe
              </button>
            </form>
            <div class="social_box">
              <a href="">
                <img src="./assets/images/fb.png" alt="">
              </a>
              <a href="">
                <img src="./assets/images/twitter.png" alt="">
              </a>
              <a href="">
                <img src="./assets/images/linkedin.png" alt="">
              </a>
              <a href="">
                <img src="./assets/images/youtube.png" alt="">
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end info_section -->


  <!-- footer section -->
  <section class="container-fluid footer_section">
    <div class="container">
      <p>
        &copy; <span id="displayYear"></span> All Rights Reserved By
        <a href="#">EazyHunt</a>
      </p>
    </div>
  </section>


  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script type="text/javascript">
        // Simulating PHP JSON data
        var roomsData = <?php echo json_encode($fulldetails); ?>;

        document.addEventListener("DOMContentLoaded", function() {
            var carouselContent = document.getElementById('carouselContent');
            var infoBox = document.getElementById('infoBox');
            var featuresBox = document.getElementById('featuresBox');

            roomsData.forEach(function(room, index) {
                var images = room.image_urls.split(',');

                images.forEach(function(image, imgIndex) {
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
                        <div class="heading_container">
                            <h2>Name: ${room.apartment_name}</h2>
                        </div>   
                        <p><strong>Owner Name:</strong> ${room.landlord_name}</p>
                        <p><strong>Room Type:</strong> ${room.room_type}</p>
                        <p><strong>Landmark:</strong> ${room.landmark}</p>
                        <p><strong>Location:</strong> ${room.location}</p>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <p style="margin: 0;"><strong></strong> ${room.phone}</p> or 
                            <a href="https://wa.me/${room.phone}?text=${encodeURIComponent('Hello! I would like to inquire about vacancy in your apartment listed on Eazy Hunt')}" target="_blank" style="display: flex; align-items: center; color: green;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                    <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                                </svg>
                            </a>
                        </div>


                    `;
                    infoBox.innerHTML = infoContent;

                    var facilities = room.facilities.split(',');

                    var featuresContent = '<div class="heading_container"> <h2>Property Features</h2> </div><ul class="list-unstyled">';
                    for (var i = 0; i < facilities.length; i++) {
                        featuresContent += '<li class="mb-2 d-flex align-items-center"><span class="text-success mr-2">&#10003;</span> ' + facilities[i].trim() + '</li>';
                    }

                    featuresContent += '</ul>';
                    featuresBox.innerHTML = featuresContent;
                }
            });

            // Initialize the map and set its view
            const map = L.map('map').setView([parseFloat(roomsData[0].latitude), parseFloat(roomsData[0].longitude)], 13);

            // Set up the OSM layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 13,
            }).addTo(map);

            // Add a marker at the apartment location
            L.marker([parseFloat(roomsData[0].latitude), parseFloat(roomsData[0].longitude)]).addTo(map)
                .bindPopup(`<b>${roomsData[0].apartment_name}</b><br>${roomsData[0].landmark}`)
                .openPopup();
        });
    </script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>