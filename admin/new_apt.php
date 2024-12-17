<?php
require '../config/config.php';

// Initialize the response array
$response = [];

if (isset($_POST['apt_reg'])) { 
  // Extract data from the POST request
  $apartment_name = isset($_POST['apartment_name']) ? $_POST['apartment_name'] : null;
  $county = isset($_POST['county']) ? $_POST['county'] : null;
  $plot_number = isset($_POST['plot_number']) ? $_POST['plot_number'] : null;
  $town = $_POST['town'];
  $user_id = $_SESSION['id'];
  $landmark = isset($_POST['landmark']) ? $_POST['landmark'] : null;
  $location = isset($_POST['location']) ? $_POST['location'] : null;
  $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
  $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;
  $facilities = isset($_POST['facilities']) ? implode(',', $_POST['facilities']) : null;


  // Validate form data
  if (empty($apartment_name) || empty($county) || empty($town) || empty($landmark) || empty($location) ) {
    // Set error message if validation fails
  $response['error'][] = 'All fields except plot number are required.';
} elseif(empty($facilities)){
  $response['error'][] = 'facilities not selected.';
}
else {
      try {
          // Prepare and execute the SQL query to insert apartment data 
          $stmt = $connect->prepare('INSERT INTO apartments (`name`, `county`, `town`, `location`, `landmark`, `plot_number`, `facilities`, `created_at`, `user_id`, `latitude`, `longitude`) VALUES (:apartment_name, :county, :town, :location, :landmark, :plot_number, :facilities, current_timestamp(), :user_id, :latitude, :longitude)');
          

          $stmt->execute(array(
              ':apartment_name' => $apartment_name,
              ':county' => $county,
              ':town' => $town,
              ':landmark' => $landmark,
              ':location' => $location,
              ':plot_number' => $plot_number,
              ':facilities' => $facilities,
              ':user_id' => $user_id,
              ':latitude' => $latitude,
              ':longitude' => $longitude
          ));

          // Get the ID of the last inserted row
          $lastInsertedId = $connect->lastInsertId();
    

          // Set success response data
          $response['success'] = true;
          $response['message'] = 'Apartment registered successfully.';
          $response['apartment_id'] = $lastInsertedId;
          echo '<script>
      setTimeout(function() {
          window.location.href = "./list_apt.php";
      }, 3000); // 3000 milliseconds (3 seconds)
  </script>';

      } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());

        // Set a more user-friendly error message
        $response['error'][] = 'An error occurred while processing your request. Please try again later.';

        // Display the error message for debugging
        echo "Error: " . htmlspecialchars($e->getMessage());
      }
   }
}


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>EazyHunt - Landlord</title>fo
        <link rel="stylesheet" href="./css/nav.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <style>
          .form-group span{
            color: red;
          }
        </style>
    </head>
    <body>
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-success">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="#">EazyHunt</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars text-white"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="../auth/logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="./dashboard.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Interface</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-houses" viewBox="0 0 16 16">
                                    <path d="M5.793 1a1 1 0 0 1 1.414 0l.647.646a.5.5 0 1 1-.708.708L6.5 1.707 2 6.207V12.5a.5.5 0 0 0 .5.5.5.5 0 0 1 0 1A1.5 1.5 0 0 1 1 12.5V7.207l-.146.147a.5.5 0 0 1-.708-.708zm3 1a1 1 0 0 1 1.414 0L12 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l1.854 1.853a.5.5 0 0 1-.708.708L15 8.207V13.5a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 13.5V8.207l-.146.147a.5.5 0 1 1-.708-.708zm.707.707L5 7.207V13.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5V7.207z"/>
                                  </svg></div>
                                Users
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="./users_list.php">All Users</a>
                                    <a class="nav-link" href="#"><s>Agents</s></a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRooms" aria-expanded="false" aria-controls="collapseRooms">
                                <div class="sb-nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-closed" viewBox="0 0 16 16">
                                        <path d="M4 1v12a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V1H4zm1 1h4v4H5V2zm5 0h2v4h-2V2zM2.5 2H1a.5.5 0 0 0-.5.5v11a.5.5 0 0 0 .5.5h1.5v-1H2V3a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v2h1V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5V5H3V3.5a.5.5 0 0 0-.5-.5zM11 7v2h1V7h-1zm0 3v2h1v-2h-1z"/>
                                    </svg>
                                </div>
                                Payments
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseRooms" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="./success_pay.php">All Mpesa</a>
                                    <a class="nav-link" href="#"><s>Failed Payments</s></a>
                                </nav>
                            </div>
                            
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Properties
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Houses
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="./new_apt.php">New Apartment</a>
                                            <a class="nav-link" href="./list_apt.php">All Apartments</a>
                                            <!-- <a class="nav-link" href="../auth/password.php">Forgot Password</a> -->
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Rooms
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="#"><s>Vacant Rooms</s></a>
                                            <a class="nav-link" href="#"><s>Shops</s></a>
                                            <!-- <a class="nav-link" href="500.html">500 Page</a> -->
                                        </nav>
                                    </div>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Addons</div>
                            <!-- <a class="nav-link" href="charts.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Charts
                            </a>
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a> -->
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                            Admin
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <ol class="breadcrumb mb-4 mt-2">
                            <li class="breadcrumb-item" ><a href="./list_apt.php">Apartments</a></li>
                            <li class="breadcrumb-item active">New Apartment</li>
                        </ol>
                        
                        <div class="card mb-4">
                            <div class="card-body">
                              <div class="response col-12" id="apiResponse">
                                
                              <?php if (!empty($responce['error'])) {
                                foreach ($responce['error'] as $error) {
                                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                                }
                            } 
                            elseif(!empty($responce['success'])){
                                echo '<div class="alert alert-success" role="alert">' . $responce['success'] . '</div>';
                            }
                            ?>


                              </div>
                              <form id="myForm" method="post">
                                <div class="row">
                                  
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="apartment_name"><span>*</span>Apartment Name</label>
                                      <input type="text" class="form-control" id="apartment_name" placeholder="Apartment Name" name="apartment_name">
                                    </div>
                                  </div>
                            
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="county">County</label>
                                      <input type="text" class="form-control" id="county" placeholder="Default KIAMBU" name="county" value="KIAMBU">
                                    </div>
                                  </div>
                            
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="town">Town</label>
                                      <input type="text" class="form-control" id="town" placeholder="Default JUJA" name="town" value="JUJA">
                                    </div>
                                  </div>
                            
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="landmark"><span>*</span> Landmark</label>
                                      <input type="text" class="form-control" id="landmark" placeholder="Landmark" name="landmark">
                                    </div>
                                  </div>
                                </div>
                            
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="plot_number">Plot Number</label>
                                      <input type="text" class="form-control" id="plot_number" placeholder="Plot Number/Home Number" name="plot_number">
                                    </div>
                                  </div>
                            
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="location"><span>*</span> Location</label>
                                      <select class="form-control" id="location" name="location" required>
                                        <option value="Gate A">Gate A</option>
                                        <option value="Gate B">Gate B</option>
                                        <option value="Gate C">Gate C</option>
                                        <option value="Oasis">Oasis</option>
                                        <option value="Gachororo">Gachororo</option>
                                        <option value="Juja Capital">Juja Capital</option>
                                        <option value="High Point">High Point</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                            
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="facilities">Water</label>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="borehole" name="facilities[]" value="Borehole ater">
                                        <label class="form-check-label" for="borehole">Borehole</label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="city_water" name="facilities[]" value="City Water">
                                        <label class="form-check-label" for="city_water">City Water</label>
                                      </div>
                                    </div>
                            
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="floor">Floor</label>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="tiled" name="facilities[]" value="Tiled">
                                        <label class="form-check-label" for="tiled">Tiled</label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="cemented" name="facilities[]" value="Cemented">
                                        <label class="form-check-label" for="cemented">Cemented</label>
                                      </div>
                                    </div>
                                  </div>
                            
                                </div>
                                <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="security">Security</label>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="lock-with-key" name="facilities[]" value="Lock with key">
                                          <label class="form-check-label" for="lock-with-key">Lock gate with key</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="gateman" name="facilities[]" value="Gateman">
                                          <label class="form-check-label" for="gateman">Gateman</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="biometrics" name="facilities[]" value="Biometrics">
                                          <label class="form-check-label" for="biometrics">Biometrics</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="CCTV" name="facilities[]" value="CCTVs">
                                          <label class="form-check-label" for="CCTV">CCTVs</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="Alarm" name="facilities[]" value="Alarm">
                                          <label class="form-check-label" for="Alarm">Alarm responce</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="Generator" name="facilities[]" value="Generator">
                                          <label class="form-check-label" for="Generator">Generator</label>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="facilities">Others facilities</label>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="parking" name="facilities[]" value="Parking">
                                          <label class="form-check-label" for="parking">Parking</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="wifi" name="facilities[]" value="WiFi">
                                          <label class="form-check-label" for="wifi">Wi-Fi</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="caretaker" name="facilities[]" value="Caretaker">
                                          <label class="form-check-label" for="caretaker">Caretaker</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="tokens" name="facilities[]" value="Tokens">
                                          <label class="form-check-label" for="tokens">Tokens</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="kitchen shelves" name="facilities[]" value="kitchen shelves">
                                          <label class="form-check-label" for="kitchen shelves">kitchen shelves</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="wardrobe" name="facilities[]" value="wardrobe">
                                          <label class="form-check-label" for="wardrobe">wardrobe</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="balcony" name="facilities[]" value="balcony">
                                          <label class="form-check-label" for="balcony">balcony</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="Natural lighting" name="facilities[]" value="Natural lighting">
                                          <label class="form-check-label" for="Natural lighting">Natural lighting</label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="Air-Conditioning" name="facilities[]" value="Air Conditioning">
                                          <label class="form-check-label" for="Air_Conditioning">Air Conditioning</label>
                                        </div>

                                      </div>
                              
                                    </div>
                              
                                  </div>
                                  <div class="map">
                                    <div id="map" style="height: 400px;"></div>
                                    <input type="hidden" id="latitude" name="latitude">
                                    <input type="hidden" id="longitude" name="longitude">
                                </div>
                                <div class="d-flex justify-content-end mr-5">
                                  <button class="btn btn-success" name="apt_reg">Add house <i class="bi bi-house-add"></i></button>
                                </div>
                              </form>
                              
                            </div>
                        </div>
                        <!-- <div style="height: 100vh"></div> -->
                        <!-- <div class="card mb-4"><div class="card-body">When scrolling, the navigation stays at the top of the page. This is the end of the static navigation demo.</div></div> -->
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; EazyHunt 2023</div>
                            <div class="text-muted">Powered <i>BY <b>MIKEN Inc.</b></i> </div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <script src="js/script.js"></script>
          <script>
          document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([0, 0], 1);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            function onLocationFound(e) {
              var radius = e.accuracy / 2;

              L.marker(e.latlng).addTo(map)
                .bindPopup("You are within " + radius + " meters from this point").openPopup();

              L.circle(e.latlng, radius).addTo(map);

              // Set the latitude and longitude values in the hidden fields
              document.getElementById('latitude').value = e.latlng.lat;
              document.getElementById('longitude').value = e.latlng.lng;

              console.log("Latitude: " + e.latlng.lat + ", Longitude: " + e.latlng.lng);
            }

            function onLocationError(e) {
              alert("Location access denied or unavailable. Please ensure location access is allowed.");
              console.error(e.message);
            }

            map.on('locationfound', onLocationFound);
            map.on('locationerror', onLocationError);

            map.locate({setView: true, maxZoom: 16});
          });
        </script>
    </body>
</html>
