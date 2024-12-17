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
        <style>
     .nav-home {
            background-color: rgb(119, 243, 156);
            background-image:
              radial-gradient(at 39% 30%, rgb(160, 12, 42) 0px, transparent 50%),
              radial-gradient(at 80% 0%, rgb(209, 139, 92) 0px, transparent 50%),
              radial-gradient(at 0% 70%, rgb(7, 50, 114) 0px, transparent 50%);
            background-blend-mode: screen;
            
            }
            .get-started{
                text-align: center;
                
            }
            
        </style>
    </head>
    <body>
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-success">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="../landlord/dashboard.php">EazyHunt</a>
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
                            <a class="nav-link collapsed" href="./" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-houses" viewBox="0 0 16 16">
                                    <path d="M5.793 1a1 1 0 0 1 1.414 0l.647.646a.5.5 0 1 1-.708.708L6.5 1.707 2 6.207V12.5a.5.5 0 0 0 .5.5.5.5 0 0 1 0 1A1.5 1.5 0 0 1 1 12.5V7.207l-.146.147a.5.5 0 0 1-.708-.708zm3 1a1 1 0 0 1 1.414 0L12 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l1.854 1.853a.5.5 0 0 1-.708.708L15 8.207V13.5a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 13.5V8.207l-.146.147a.5.5 0 1 1-.708-.708zm.707.707L5 7.207V13.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5V7.207z"/>
                                  </svg></div>
                                Apartments
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="./new_apt.php">New apartment</a>
                                    <a class="nav-link" href="./list_apt.php">My apartments</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRooms" aria-expanded="false" aria-controls="collapseRooms">
                                <div class="sb-nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-closed" viewBox="0 0 16 16">
                                        <path d="M4 1v12a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V1H4zm1 1h4v4H5V2zm5 0h2v4h-2V2zM2.5 2H1a.5.5 0 0 0-.5.5v11a.5.5 0 0 0 .5.5h1.5v-1H2V3a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v2h1V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5V5H3V3.5a.5.5 0 0 0-.5-.5zM11 7v2h1V7h-1zm0 3v2h1v-2h-1z"/>
                                    </svg>
                                </div>
                                Rooms
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseRooms" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="./new_room.php">New Room</a>
                                    <a class="nav-link" href="./my_rooms.php">My Rooms</a>
                                </nav>
                            </div>
                            
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                HELP
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Add Room
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="#">NEW Room</a>
                                            <a class="nav-link" href="../auth/register.php">Register</a>
                                            <a class="nav-link" href="../auth/password.php">Forgot Password</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Error
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="401.html">401 Page</a>
                                            <a class="nav-link" href="404.html">404 Page</a>
                                            <a class="nav-link" href="500.html">500 Page</a>
                                        </nav>
                                    </div>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Addons</div>
                            <a class="nav-link" href="faqs.php">
                                    <div class="sb-nav-link-icon"> <i class="fas fa-bell"></i></div>
                                     FAQ's
                                </a>
                                <!--
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a> -->
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                            Landlord
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4 mt-2">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"></li>
                        </ol>
                        
                        <div class="card mb-4">
                            <div class="card-body nav-home" >
                                <div class="container ">
                                    <div class="row gy-1 gy-md-4 gy-lg-0 align-items-center">
                                        <div class="col-12 col-lg-5">
                                            <h2 class="display-6 mb-2 mb-xl-4 text-dark">Stop Struggling to Find Tenants!</h2>
                                            <p class="mb-2 mb-xl-5">
                                                Advertise Smarter, Not Harder! Showcase your properties to a diverse online audience and step into the digital spotlight. 
                                                Reach a vast online population, connect with students, and redefine your rental adventure.
                                            </p>
                                            <a href="#!" class="btn btn-sm btn-success">Explore Benefits</a>
                                        </div>
                                        
                                        <div class="col-12 col-lg-7">
                                        <div class="row justify-content-xl-end">
                                            <div class="col-12 col-xl-11">
                                            <div class="row gy-2 gy-md-3">
                                                <div class="col-12 col-sm-6">
                                                <div class="card border-0 border-bottom border-success shadow-sm">
                                                    <div class="card-body text-center p-2 p-xxl-5">
                                                    <h3 class="display-3 mb-2">60+</h3>
                                                    <p class="fs-5 mb-0 text-secondary">Apartments Managed</p>
                                                    </div>
                                                </div>
                                                </div>
                                                <!--<div class="col-12 col-sm-6">-->
                                                <!--<div class="card border-0 border-bottom border-success shadow-sm">-->
                                                <!--    <div class="card-body text-center p-2 p-xxl-5">-->
                                                <!--    <h3 class="display-3 mb-2">180k+</h3>-->
                                                <!--    <p class="fs-5 mb-0 text-secondary">Target Students</p>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <!--</div>-->
                                                <div class="col-12 col-sm-6">
                                                <div class="card border-0 border-bottom border-success shadow-sm">
                                                    <div class="card-body text-center p-2 p-xxl-5">
                                                    <h3 class="display-3 mb-2">300+</h3>
                                                    <p class="fs-5 mb-0 text-secondary">Successful Bookings</p>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                <div class="card border-0 border-bottom border-success shadow-sm">
                                                    <div class="card-body text-center p-2 p-xxl-5">
                                                    <h3 class="display-3 mb-2">78+</h3>
                                                    <p class="fs-5 mb-0 text-secondary">Happy Landlords</p>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                       <h3 class="text-center mt-4 mb-6">Let's get started. What do you want to do?</h3>
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="card shadow mx-1 my-2" style="width: 150px;">
                                    <div class="card-body text-center">
                                        <!-- House icon from Font Awesome -->
                                        <i class="fas fa-laptop-house fa-2x text-success mb-3 mt-4"></i>
                                        <p class="card-text text-success fs-6 mb-4">
                                            New Apartment
                                        </p>
                                        <a href="./new_apt.php" class="stretched-link"></a>
                                    </div>
                                </div>
                                <div class="card shadow mx-1 my-2" style="width: 150px;">
                                    <div class="card-body text-center">
                                        <!-- House icon from Font Awesome -->
                                        <i class="fas fa-plus-circle fa-2x text-success mb-3 mt-4"></i>
                                        <p class="card-text text-success mb-4">
                                            New Room
                                        </p>
                                        <a href="./new_room.php" class="stretched-link"></a>
                                    </div>
                                </div>
                                <div class="card shadow mx-1 my-2" style="width: 150px;">
                                    <div class="card-body text-center">
                                        <!-- House icon from Font Awesome -->
                                        <i class="fas fa-feather fa-2x text-success mb-3 mt-4"></i>
                                        <p class="card-text text-success mb-4">
                                            Create Lease
                                        </p>
                                        <a href="#" class="stretched-link"></a>
                                    </div>
                                </div>
                                <div class="card shadow mx-1 my-2" style="width: 150px;">
                                    <div class="card-body text-center">
                                        <!-- House icon from Font Awesome -->
                                        <i class="far fa-frown fa-2x text-success mb-3 mt-4"></i>
                                        <p class="card-text text-success mb-4">
                                            Help &amp; Support
                                        </p>
                                        <a href="#" class="stretched-link"></a>
                                    </div>
                                </div>
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
        <script src="js/script.js"></script>
  
    </body>
</html>
