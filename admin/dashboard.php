<?php
require "../config/config.php";

// header('content-Type: application/json');
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}


$totalUsers = 0;
$totalApartments = 0;
$totalPayments = 0.0;

if (!empty($_SESSION['id'])) {
  // Get total users, total apartments, and total payment amount
  $sql = "SELECT 
                (SELECT COUNT(*) FROM users) AS total_users,  
                (SELECT COUNT(*) FROM apartments WHERE deleted = 0) AS total_apartments, 
                (SELECT SUM(Amount) FROM payments) AS total_payment ";
  $stmt = $connect->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $totalUsers = isset($result['total_users']) ? $result['total_users'] : 0;
    $totalApartments = isset($result['total_apartments']) ? $result['total_apartments'] : 0;
    $totalPayment = isset($result['total_payment']) ? $result['total_payment'] : 0;


//   echo json_encode($result);

  // Get apartment details with room count
  $sql = "SELECT    
            a.name AS apartment_name, 
            a.location, 
            a.apartment_id,
        COUNT(r.room_id) AS room_count
        FROM apartments a
        LEFT JOIN rooms r ON a.apartment_id = r.apartment_id
        WHERE a.deleted = 0
        GROUP BY a.apartment_id
        ORDER BY a.apartment_id DESC
        LIMIT 7";
  $stmt = $connect->prepare($sql);
  $stmt->execute();
  $apartmentDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

//   echo json_encode($apartmentDetails);
// Get payment details
  $sql = "SELECT UserPhoneNumber, TransactionId, Amount, CreatedOn
          FROM payments
          LIMIT 5";
  $stmt = $connect->prepare($sql);
  $stmt->execute();
  $paymentDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

//   echo json_encode($paymentDetails);

  $connect = null; //close connection
   
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
        <title>kejaHunter - Landlord</title>
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
                        <div class="small" style="color: red;">Logged in as:</div>
                            ADMIN
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-3">
                        <ol class="breadcrumb mb-1 mt-2">
                            <strong><li class="breadcrumb-item lead">Dashboard</li></strong>
                            <form class="d-flex search-form " method="GET" role="search" name="search">
                                <div class="input-group">
                                    <input class="form-control m-4" type="search" name="query" placeholder="Search Users" aria-label="Search">
                                    <button class="btn btn-outline-success" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </ol>
                      
                        <div class="row mt-2">
                            <div class="col-md-4 col-sm-6 mb-2"> 
                                <a href="./users_list.php">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Users</h5>
                                            <h1 class="card-text"> <?php if(isset($totalUsers)){
                                                    echo $totalUsers;
                                                } 
                                                ?>
                                            </h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-2">
                                <a href="./list_apt.php">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Properties</h5>
                                            <h1 class="card-text"><?php if(isset($totalApartments)){
                                                    echo $totalApartments;
                                                } 
                                                ?>
                                            </h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-2">
                                <a href="./success_pay.php">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Payments</h5>
                                            <h1 class="card-text"><?php if(isset($totalPayment)){
                                                    echo "Ksh. ". $totalPayment;
                                                } 
                                                ?></h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            </div>
                            <!-- property management -->

                            <div class="row">
                            <div class="col-12 mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-3 ">
                                <h5 class="m-0">Property Management</h5>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary">Add Property</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                        Filters
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a class="dropdown-item" href="#">Type</a></li>
                                            <li><a class="dropdown-item" href="#">Date</a></li>
                                        </ul>
                                    </div>
                                </div>
                            <div class="card">
                                <div class="card-body p-0">
                                    <table class="table table-hover table-responsive">
                                    <thead>
                                        <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Rooms</th>
                                        <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($apartmentDetails)){
                                            foreach ($apartmentDetails as $apartment) {
                                                echo '<tr>
                                                    <td>' . $apartment['apartment_name'] . '</td>
                                                    <td>' . $apartment['location'] . '</td>
                                                    <td>' . $apartment['room_count'] . '</td>
                                                    <td class="text-center me-5">
                                                        <a href="./apt_details.php?apartment_id=' . $apartment['apartment_id'] . '" class="text-primary me-3"><i class="fa fa-pencil"></i></a>
                                                        <a href="./apartment_delete.php?apartment_id='.$apartment['apartment_id'].'" class="text-danger me-3"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>';

                                            }   
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payments now  -->
                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="m-0"><span class="text-success">M-pesa</span>  Payments</h5>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Time</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($paymentDetails)){
                                        foreach ($paymentDetails as $payment) {
                                            echo "<tr> 
                                                    <td>". $payment['UserPhoneNumber'] . "</td>";
                                                echo "<td>". $payment['Amount']  ."</td>";
                                                echo "<td>". $payment['TransactionId'] ."</td>";
                                                echo "<td>". $payment['CreatedOn'] ."</td>"; 
                                                echo ' <td class="text-center me-5">
                                        <a href="#" class="text-danger me-3"><span class="badge bg-success">Paid</span></a>
                                        <a href="#" class="text-primary me-2"><i class="fa fa-eye"></i></a>
                                    </td> </tr>';
                                            }
                                    } ?>
                                
                                    
                                    
                                
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>



                            

                   </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; KejaHunter 2023</div>
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
