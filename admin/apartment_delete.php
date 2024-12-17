<?php
require "../config/config.php";

if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}


    $aptID = isset($_GET['apartment_id']) ? $_GET['apartment_id'] : null;
    // echo $aptID;

    if ($aptID !== false && $aptID !== null) {
        try {
           
            // SQL query to get the required information
            $sql = "
                SELECT 
                    users.full_name AS owner_name,
                    apartments.name AS apartment_name,
                    apartments.location,
                    COUNT(rooms.room_id) AS room_count
                FROM 
                    apartments
                JOIN 
                    users ON apartments.user_id = users.user_id
                LEFT JOIN 
                    rooms ON apartments.apartment_id = rooms.apartment_id
                WHERE 
                    apartments.apartment_id = :apartment_id
                GROUP BY 
                    apartments.apartment_id, 
                    users.full_name, 
                    apartments.name, 
                    apartments.location
            ";
    
            $stmt = $connect->prepare($sql);
            $stmt->bindParam(':apartment_id', $aptID, PDO::PARAM_INT);
            $stmt->execute();
    
            $apartmentData  = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if(isset($_POST['delete_apartment'])) {
                // Begin a transaction
                $connect->beginTransaction();

                $updateRoomsSql = "UPDATE rooms SET deleted = 1 WHERE apartment_id = :apartment_id";
                $updateRoomsStmt = $connect->prepare($updateRoomsSql);
                $updateRoomsStmt->bindParam(':apartment_id', $aptID, PDO::PARAM_INT);
                $updateRoomsStmt->execute();

                // Soft delete the apartment
                $updateAptSql = "UPDATE apartments SET deleted = 1 WHERE apartment_id = :apartment_id";
                $updateAptStmt = $connect->prepare($updateAptSql);
                $updateAptStmt->bindParam(':apartment_id', $aptID, PDO::PARAM_INT);
                $updateAptStmt->execute();

                $connect->commit();

                header("Location: dashboard.php"); 

            }
            
        } catch (Exception $e) {
            // Roll back the transaction if something failed
            $connect->rollBack();


            echo "Failed to delete apartment: " . $e->getMessage();
        }
    } else {
        // Handle invalid apartment ID
        echo "Invalid apartment ID.";
    }
// }
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
        <style>
            a{
                text-decoration: none;
            }
        </style>


    </head>
    <body>
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-success">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="#">Eazzy Hunt</a>
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
                        <div class="small" style="color: red;">Logged in as:</div>
                            ADMIN
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
                                    <li class="breadcrumb-item "><a href="./apartments_list.php">Apartments</a></li>
                                    <li class="breadcrumb-item text-danger">Delete  - Admin<?php echo isset($apartmentData['apartment_name']) ? htmlspecialchars($apartmentData['apartment_name']) : ''; ?></li>
                                </ol>

                                <div class="card" id="apartmentDetails">
                                    <form id="myForm" method="post">
                                        <div class="row">
                                            <div class="col-md-6 mt-2">
                                                <div class="form-group ms-3">
                                                    <label for="apartment_name">Apartment Name</label>
                                                    <input type="text" class="form-control mt-1" id="apartment_name" placeholder="Apartment Name" name="apartment_name" value="<?php echo isset($apartmentData['apartment_name']) ? htmlspecialchars($apartmentData['apartment_name']) : ''; ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mt-2">
                                                <div class="form-group me-2">
                                                    <label for="owner_name">Owner Name</label>
                                                    <input type="text" class="form-control" id="owner_name" placeholder="Owner Name" name="owner_name" value="<?php echo isset($apartmentData['owner_name']) ? htmlspecialchars($apartmentData['owner_name']) : ''; ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mt-2">
                                                <div class="form-group ms-3">
                                                    <label for="location">Location</label>
                                                    <input type="text" class="form-control" id="location" placeholder="Location" name="location" value="<?php echo isset($apartmentData['location']) ? htmlspecialchars($apartmentData['location']) : ''; ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mt-2">
                                                <div class="form-group me-2">
                                                    <label for="room_count">Room Count</label>
                                                    <input type="text" class="form-control" id="room_count" placeholder="Room Count" name="room_count" value="<?php echo isset($apartmentData['room_count']) ? htmlspecialchars($apartmentData['room_count']) : '0'; ?>" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end mr-5 mt-2 mb-2">
                                            <button class="btn btn-success me-3" name="apt_edit">Update <i class="bi bi-house-add"></i></button>
                                            <button type="button" class="btn btn-danger me-3" id="deleteApartmentBtn" data-toggle="modal" data-target="#deleteConfirmationModal">Delete <i class="bi bi-trash"></i></button>
                                        </div>

                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel"> Confirm Deletion</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <i class="fa fa-exclamation-circle text-danger fa-5x align-items-center mb-3 mt-2"></i>
                                                        <p class="text-danger">Are you sure you want to delete this apartment? This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <form id="deleteForm" method="post">
                                                            <input type="hidden" name="apartment_id" value="<?php echo $aptID; ?>">
                                                            <button type="submit" class="btn btn-danger" name="delete_apartment">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
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
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="js/script.js"></script>
        <script>
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                // Submit the form when delete is confirmed
                document.getElementById('confirm').submit();
            });
        </script>
    </body>
</html>