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

        // Check if apartment exists
            if ($apartmentData) {
            // Format the data into HTML
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
                    <div id='map' style='width: 100%;'></div>
                    <div class='card-footer d-flex justify-content-end'>
                        <a href='./list_apt.php' class='btn btn-outline-dark me-2 px-3'>Back</a>
                        <a href='./edit_apt.php?apartment_id={$apartment_id}' class='btn btn-success mx-2 px-4'>Edit</a>
                    </div>
                ";
            // Convert the result to JSON
            $response['success'][] = true;
            // $response['data'] = $apartmentData;
        } else {
            $response['success'][] = false;
            $response['error'][] = 'Apartment not found';
        }
    } else {
        $response['success'][] = false;
        $response['error'][] = 'User not authenticated';
    }
} catch (PDOException $e) {
    $response['success'][] = false;
    $response['error'][] = 'Database error: ' . $e->getMessage();
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
        <title>EazyHunt - Admin</title>
        <link rel="stylesheet" href="./css/nav.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <style>
        #map {
            height: 300px;
            width: 100%;
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
                    <div class="container-fluid px-3">
                      <div class="container-fluid h-100">
                        <div class="row h-80 justify-content-center align-items-center">
                          <div class="col-12 p-4 shadow rounded bg-white">
                            <ol class="breadcrumb mb-4 mt-2">
                                <li class="breadcrumb-item"><a href="./list_apt.php">Apartments</a></li>
                                <li class="breadcrumb-item active">Details</li>
                            </ol>
                            
                            <div class="card" id="apartmentDetails">
                            <?php
                                if (isset($response['error'])) {
                                    foreach ($response['error'] as $error) {
                                        echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                                    }
                                } elseif ($response['success'] === true) {
                                    echo '<div class="alert alert-success" role="alert">' . $response['message'] . '</div>';
                                }
                                ?>

                                <?php
                                    if(isset($formattedHtml)){
                                        echo $formattedHtml;
                                    }
                                    
                                    
                                ?>
                            
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
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script src="js/script.js"></script>
        <script>
            const apartmentData = <?php echo json_encode($apartmentData); ?>;
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
