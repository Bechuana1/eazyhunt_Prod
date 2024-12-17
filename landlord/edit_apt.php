<?php
require '../config/config.php';


if (empty($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit();
}

$apartment_id = isset($_GET['apartment_id']) ? $_GET['apartment_id'] : null;
$response = [];

try {
    if (!empty($_SESSION['id'])) {
        $stmt = $connect->prepare('
            SELECT 
                * 
            FROM
                apartments a
            WHERE 
                 a.apartment_id = :apt_id AND a.deleted = 0
            LIMIT 1
        ');

        $stmt->execute(array(':apt_id' => $apartment_id));
        $apartmentData = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo $apartmentData;

        // Check if apartment exists
            if ($apartmentData) {
            // Format the data into HTML
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apt_edit'])) {
                            // Validate and sanitize input data
                            $apartment_name = isset($_POST['apartment_name']) ? htmlspecialchars($_POST['apartment_name']) : null;
                            $county = "Kiambu";
                            $town = "Juja";
                            $landmark = isset($_POST['landmark']) ? htmlspecialchars($_POST['landmark']) : null;
                            $plot_number = isset($_POST['plot_number']) ? htmlspecialchars($_POST['plot_number']) : null;
                            $location = isset($_POST['location']) ? htmlspecialchars($_POST['location']) : null;

                            $facilities = isset($_POST['facilities']) ? $_POST['facilities'] : [];

                            // Prepare and execute SQL update statement
                            $stmt = $connect->prepare('
                                UPDATE apartments 
                                SET 
                                    name = :name, 
                                    county = :county, 
                                    town = :town, 
                                    landmark = :landmark, 
                                    plot_number = :plot_number, 
                                    location = :location, 
                                    facilities = :facilities 
                                WHERE 
                                    apartment_id = :apt_id
                            ');

                            $stmt->execute(array(
                                ':name' => $apartment_name,
                                ':county' => $county,
                                ':town' => $town,
                                ':landmark' => $landmark,
                                ':plot_number' => $plot_number,
                                ':location' => $location,
                                ':facilities' => implode(",", $facilities),
                                ':apt_id' => $apartment_id
                            ));

                            // Redirect to success page or display success message
                            header("Location: ./apt_details.php?apartment_id={$apartment_id}");
                            
                            exit();
                        }
            
        
            // Convert the result to JSON
            $response['success'] = true;
            // $response['data'] = $apartmentData;
        } else {
            $response['success'] = false;
            $response['error'] = 'Apartment not found';
        }
    } else {
        $response['success'] = false;
        $response['error'] = 'User not authenticated';
    }
} catch (PDOException $e) {
    $response['success'] = false;
    $response['error'] = 'Database error: ' . $e->getMessage();
}
 if (is_array($apartmentData['facilities'])) {
    // echo 'array';

 }
 else{
    $facilitiesString = $apartmentData['facilities'];
    $facilitiesArray = explode(',', $facilitiesString);
    // print_r($facilitiesArray);
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
        <title>EazyHunt - Landlord</title>
        <link rel="stylesheet" href="./css/nav.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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
                                    <a class="nav-link" href="./list_apt.php">Agent</a>
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
                                    <a class="nav-link" href="#">Failed Payments</a>
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
                                            <a class="nav-link" href="../auth/register.php">Register</a>
                                            <a class="nav-link" href="../auth/forgot.php">Forgot Password</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Rooms
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="#">Vacant Rooms</a>
                                            <a class="nav-link" href="#">Shops</a>
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
                    <div class="container-fluid px-3">
                      <div class="container-fluid h-100">
                        <div class="row h-80 justify-content-center align-items-center">
                          <div class="col-12 p-4 shadow rounded bg-white">
                            <ol class="breadcrumb mb-4 mt-2">
                                <li class="breadcrumb-item"><a href="./list_apt.php">Apartments</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                            
                            <div class="card" id="apartmentDetails">
                                <form id="myForm" method="post">
                                <div class="row">
                                  
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="apartment_name">*Apartment Name</label>
                                      <input type="text" class="form-control" id="apartment_name" placeholder="Apartment Name" name="apartment_name" value="<?php echo isset($apartmentData['name']) ? $apartmentData['name'] : ''; ?>">
                                    </div>
                                  </div>
                            
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="county">County</label>
                                      <input type="text" class="form-control" id="county" placeholder="Default KIAMBU" name="county" value="KIAMBU" disabled>
                                    </div>
                                  </div>
                            
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="town">Town</label>
                                      <input type="text" class="form-control" id="town" placeholder="Default JUJA" name="town" value="JUJA" disabled>
                                    </div>
                                  </div>
                            
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="landmark">*Landmark</label>
                                      <input type="text" class="form-control" id="landmark" placeholder="Landmark" name="landmark" value="<?php echo isset($apartmentData['landmark']) ? $apartmentData['landmark'] : ''; ?>">
                                    </div>
                                  </div>
                                </div>
                            
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="plot_number">Plot Number</label>
                                      <input type="text" class="form-control" id="plot_number" placeholder="Plot Number/Home Number" name="plot_number" value="<?php echo isset($apartmentData['plot_number']) ? $apartmentData['plot_number'] : ''; ?>">
                                    </div>
                                  </div>
                            
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="location">*Location</label>
                                      <select class="form-control" id="location" name="location" value="<?php echo isset($apartmentData['location']) ? $apartmentData['location'] : ''; ?>"required >
                                        <option value="Gate A">Gate A</option>
                                        <option value="Gate B">Gate B</option>
                                        <option value="Gate C">Gate C</option>
                                        <option value="Oasis">Oasis</option>
                                        <option value="Gachororo">Gachororo</option>
                                        <option value="Kalimoni">Kalimoni</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                            
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="facilities">Water</label>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="borehole" name="facilities[]" value="Borehole water" <?php echo is_array($facilitiesArray) &&  (in_array('Borehole water', $facilitiesArray)) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="borehole">Borehole</label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="city_water" name="facilities[]" value="City Water" <?php echo is_array($facilitiesArray) &&  (in_array('City water', $facilitiesArray)) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="city_water">City Water</label>
                                      </div>
                                    </div>
                            
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="floor">Floor</label>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="tiled" name="facilities[]" value="Tiled" <?php echo is_array($facilitiesArray) &&  (in_array('Tiled', $facilitiesArray)) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="tiled">Tiled</label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="cemented" name="facilities[]" value="Cemented" <?php echo is_array($facilitiesArray) &&  (in_array('Cemented', $facilitiesArray)) ? 'checked' : ''; ?>>
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
                                        <input class="form-check-input" type="checkbox" id="lock-with-key" name="facilities[]" value="Lock with key" <?php echo is_array($facilitiesArray) &&  (in_array('Lock with key', $facilitiesArray)) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="lock-with-key">Lock gate with key</label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="gateman" name="facilities[]" value="Gateman" <?php echo (is_array($facilitiesArray)) &&  (in_array('Gateman', $facilitiesArray)) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="gateman">Gateman</label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="biometrics" name="facilities[]" value="Biometrics" <?php echo (is_array($facilitiesArray)) &&  (in_array('Biometrics', $facilitiesArray)) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="biometrics">Biometrics</label>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="facilities">Others facilities</label>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="car_parking" name="facilities[]" value="Parking" <?php echo is_array($facilitiesArray) &&  (in_array('parking', $facilitiesArray)) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="parking">Car Parking</label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="wifi" name="facilities[]" value="WiFi" <?php echo is_array($facilitiesArray) &&  (in_array('Wifi', $facilitiesArray)) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="wifi">Wi-Fi</label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="caretaker" name="facilities[]" value="Caretaker" <?php echo is_array($facilitiesArray) &&  (in_array('Caretaker', $facilitiesArray)) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="caretaker">Caretaker</label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="tokens" name="facilities[]" value="Tokens" <?php echo is_array($facilitiesArray) &&  (in_array('Tokens', $facilitiesArray)) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="tokens">Tokens</label>
                                      </div>
                                    </div>
                            
                                  </div>
                            
                                </div>

                                <div class="d-flex justify-content-end mr-5">
                                  <button class="btn btn-success" name="apt_edit">update<i class="bi bi-house-add"></i></button>
                                </div>
                              </form>
                            
                          </div>
                      
                        
                      </div>

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
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/script.js"></script>
    </body>
</html>

