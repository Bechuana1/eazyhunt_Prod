<?php
require_once '../config/config.php';


$apartments = [];

if (!empty($_SESSION['id'])) {
    $stmt = $connect->prepare(' 
        SELECT apartment_id, name
        FROM apartments a
        WHERE user_id = :user_id AND a.deleted = 0;
    ');
    $stmt->execute(array(':user_id' => $_SESSION['id']));
    $apartments = $stmt->fetchAll();

    $responce['apartments'] = $apartments;
}

$response = array('success' => false);
$response['form_type'] = 'new_room';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apartment_id = isset($_POST['apartment_id']) ? $_POST['apartment_id'] : null;
    $user_id = $_SESSION['id'];
    $type = isset($_POST['room_type']) ? $_POST['room_type'] : null;
    $price = isset($_POST['price']) ? $_POST['price'] : null;
    $vacant = isset($_POST['vacant']) ? $_POST['vacant'] : null;
    $add_falities = isset($_POST['facilities1']) ? $_POST['facilities1'] : null;

    if (empty($user_id)) {
        $response['errors'][] = 'System error: User ID is missing. Please log in and try again.';
    }
    if (empty($type) || empty($price) || empty($vacant) || empty($add_falities)) {
        $response['errors'][] = 'All fields are required.';
    } else {
        $targetDirectory = __DIR__ . '/../uploads/rooms/';
        $uploadOk = 1;
        $uploadedImageUrls = array();
        $uploadedImagesCount = 0;

        if (isset($_FILES["image"]) && count($_FILES["image"]["name"]) <= 5) {
            $images = $_FILES["image"];

            foreach ($images["name"] as $key => $imageName) {
                $target_file = $targetDirectory . basename($imageName);

                // Check if the file is an actual image
                $check = getimagesize($images["tmp_name"][$key]);

                if ($check !== false) {
                    // Create an image resource based on the file type
                    $imageResource = null;
                    switch ($check['mime']) {
                        case 'image/jpeg':
                            $imageResource = imagecreatefromjpeg($images["tmp_name"][$key]);
                            break;
                        case 'image/png':
                            $imageResource = imagecreatefrompng($images["tmp_name"][$key]);
                            break;
                        case 'image/gif':
                            $imageResource = imagecreatefromgif($images["tmp_name"][$key]);
                            break;
                            // Add additional cases for other image types if needed

                        default:
                            $response['errors'][] = "Unsupported image type: $imageName";
                            $uploadOk = 0;
                            break;
                    }
                    if ($imageResource) {
                        if (imageistruecolor($imageResource)) {
                            // Image is already true color, proceed with WebP conversion
                            $webpFilePath = $target_file;
                        } else {
                            // Convert paletted image to true color
                            $trueColorImageResource = imagecreatetruecolor(imagesx($imageResource), imagesy($imageResource));
                            imagecopy($trueColorImageResource, $imageResource, 0, 0, 0, 0, imagesx($imageResource), imagesy($imageResource));
                            imagedestroy($imageResource);

                            // Set the image resource to the true color version
                            $imageResource = $trueColorImageResource;
                            $webpFilePath = $target_file;
                        }

                        // Convert to WebP (if PHP is compiled with WebP support)
                        imagewebp($imageResource, $webpFilePath . '.webp', 80); // Adjust the quality

                        // Free up memory
                        imagedestroy($imageResource);

                        // Construct the relative image URL
                        $webpImageURL = str_replace($targetDirectory, '', $webpFilePath);
                        $uploadedImageUrls[] = $webpImageURL;
                        $uploadedImagesCount++;
                    }
                } else {
                    $response['errors'][] = "File is not an image: $imageName";
                    $uploadOk = 0;
                }
            }
        }


        if ($uploadOk) {
            try {
                // Prepare the SQL statement to insert the room data
                $stmt = $connect->prepare('INSERT INTO rooms (`type`, `price`, `additional_facilities`, `is_vacant`, `created_at`, `user_id`, `apartment_id`) VALUES (:type, :price, :additional_facilities, :is_vacant, current_timestamp(), :user_id, :apt_id)');

                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':additional_facilities', $add_falities);
                $stmt->bindParam(':is_vacant', $vacant);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':apt_id', $apartment_id);

                // Execute the statement to insert the room data
                $stmt->execute();

                // Get the room ID of the newly inserted row
                $room_id = $connect->lastInsertId();

                // Prepare the SQL statement to insert the room images
                $stmt1 = $connect->prepare('INSERT INTO room_images (`room_id`, `apt_id`, `image_url`) VALUES (:room_id, :apt_id, :image)');

                // Loop through each uploaded image and insert it into the room_images table
                for ($i = 0; $i < count($uploadedImageUrls); $i++) {
                    $imageURL = 'uploads/rooms/' . $uploadedImageUrls[$i]; //changes the base URL to where the images are stored.

                    // Bind the room ID, apartment ID, and image URL to the placeholders in the SQL query
                    $stmt1->bindParam(':room_id', $room_id);
                    $stmt1->bindParam(':apt_id', $apartment_id);
                    $stmt1->bindParam(':image', $imageURL);


                    $stmt1->execute();
                }


                $response['success'] = true;
                $response['message'] = 'Room listed successfully';
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "./list_apt.php";
                    }, 3000); // 3000 milliseconds (3 seconds)
                </script>';
            } catch (PDOException $e) {
                // Set error message in the response
                $response['errors'][] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

//echo json_encode($response);
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
        <style>
        .upload-container {
            background-color: rgb(239, 239, 239);
            border-radius: 6px;
            padding: 10px;
          }
          
          .border-container {
            border: 5px dashed rgba(198, 198, 198, 0.65);
          /*   border-radius: 4px; */
            padding: 20px;
          }
          
          .border-container p {
            color: #130f40;
            font-weight: 600;
            font-size: 1.1em;
            letter-spacing: -1px;
            margin-top: 30px;
            margin-bottom: 0;
            opacity: 0.65;
          }
          
          #file-browser {
            text-decoration: none;
            color: rgb(22,42,255);
            border-bottom: 3px dotted rgba(22, 22, 255, 0.85);
          }
          
          #file-browser:hover {
            color: rgb(0, 0, 255);
            border-bottom: 3px dotted rgba(0, 0, 255, 0.85);
          }
          
          .icons {
            color: #95afc0;
            opacity: 0.55;
          }
          .form-group span{
            color: red;
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
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-houses" viewBox="0 0 16 16">
                                    <path d="M5.793 1a1 1 0 0 1 1.414 0l.647.646a.5.5 0 1 1-.708.708L6.5 1.707 2 6.207V12.5a.5.5 0 0 0 .5.5.5.5 0 0 1 0 1A1.5 1.5 0 0 1 1 12.5V7.207l-.146.147a.5.5 0 0 1-.708-.708zm3 1a1 1 0 0 1 1.414 0L12 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l1.854 1.853a.5.5 0 0 1-.708.708L15 8.207V13.5a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 13.5V8.207l-.146.147a.5.5 0 1 1-.708-.708zm.707.707L5 7.207V13.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5V7.207z"/>
                                  </svg></div>
                                Apartments
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="./new_apt.php">NEW apartment</a>
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
                                    <a class="nav-link" href="./new_room.php">NEW room</a>
                                    <a class="nav-link" href="./my_rooms.php">My rooms</a>
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
                    <div class="container-fluid px-4">
                        <ol class="breadcrumb mb-4 mt-2">
                            <li class="breadcrumb-item"><a href="./list_apt.php">Apartments</a></li>
                            <li class="breadcrumb-item active">Add Room</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="response col-12" id="apiResponse">
                                
                                <div style="color: green;"></div>
                                <?php
                                    if (isset($response['errors'])) {
                                        foreach ($response['errors'] as $error) {
                                            echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                                        }
                                    } elseif ($response['success'] === true) {
                                        echo '<div class="alert alert-success" role="alert">' . $response['message'] . '</div>';
                                    }
                                ?>


                              </div>
                                <h2 class="text-center">Room listing</h2>
                                        <form method="post" enctype="multipart/form-data" id="roomsForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="apartmentSelect"><span>*</span> Select an Apartment:</label>
                                                        <select id="apartmentSelect" class="form-control" name="apartment_id" style="text-align:center">
                                                            <?php
                                                            if (!empty($apartments)) {
                                                                foreach ($apartments as $apartment) {
                                                                    // Output option element for each apartment
                                                                    echo '<option value="' . $apartment['apartment_id'] . '">' . $apartment['name'] . '</option>';
                                                                }
                                                            } else {
                                                                // Output a default option if no apartments are available
                                                                echo '<option value="" disabled>No apartments available</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="type"><span>*</span> Room type</label>
                                                        <select type="select" class="form-control"  name="room_type" style="text-align:center" id="room_type" required>
                                                            <option value="">Room Type</option>
                                                            <option value="Single">Single</option>
                                                            <option value="Bedsitter">Bedsitter</option>
                                                            <option value="Double">Double</option>
                                                            <option value="1 Bedroom">1 Bedroom</option>
                                                            <option value="2 Bedroom">2 Bedroom</option>
                                                            <option value="commercial">commercial</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="price"> <span>*</span> Price</label>
                                                        <input type="number" class="form-control" id="price" placeholder="ksh 6,500" name="price" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="facilities1"><span>*</span> More Facilities (room specific facilities)</label>
                                                        <input type="text" class="form-control" id="facilities1" placeholder="Wi-Fi, Water, Security..." name="facilities1">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <input type="text" class="form-control" id="description" placeholder="Room number, Floor, e.t.c .." name="description">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="vacant"><span>*</span> Vacant/Occupied</label>
                                                        <select class="form-control" id="vacant" name="vacant">
                                                            <option value="1">Vacant</option>
                                                            <option value="0">Occupied</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="wrapper text-center">
                                                  <div class="container mt-3">
                                                    <div class="upload-container">
                                                      <div class="border-container">
                                                        <div id="selected-images-container" class="row mt-3"></div>
                                                        <div class="icons fa-4x" id="preview-container">
                                                            <i class="fas fa-file-image d-none d-sm-inline" data-fa-transform="shrink-3 down-2 left-6 rotate--45"></i>
                                                            <i class="fas fa-file-alt" data-fa-transform="shrink-2 up-4"></i>
                                                            <i class="fas fa-file-pdf d-none d-sm-inline" data-fa-transform="shrink-3 down-2 right-6 rotate-45"></i>
                                                        </div>
                                                        <label for="file-upload" class="file-selector-button">
                                                          <p>
                                                            Drag and drop files here, or 
                                                            <a href="#" id="file-browser">
                                                              Browse
                                                              <input type="file" id="image" name="image[]" accept="image/*" multiple style="display:none;">
                                                            </a> your phone

                                                          </p>
                                                        </label>
                                                        <input type="file" id="file-upload" name="file[]" accept="image/*" multiple style="display:none;">
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              
                                              <script>
                                                document.getElementById('file-browser').addEventListener('click', function() {
                                                  document.getElementById('image').click();
                                                });
                                                document.getElementById('image').addEventListener('change', function () {
                                                // Get the selected files
                                                var files = this.files;

                                                // Container to display selected images
                                                        var selectedImagesContainer = document.getElementById('selected-images-container');

                                                // Clear existing images
                                                selectedImagesContainer.innerHTML = '';

                                                // Display selected images horizontally
                                                for (var i = 0; i < files.length; i++) {
                                                    var file = files[i];
                                                    var reader = new FileReader();

                                                    reader.onload = function (e) {
                                                        var img = document.createElement('img');
                                                        img.src = e.target.result;
                                                        img.className = 'img-thumbnail m-2';
                                                        img.style.maxHeight = '100px';
                                                        img.style.maxWidth = '100px';

                                                        // Append the image directly to the container
                                                        selectedImagesContainer.appendChild(img);
                                                    };

                                                    reader.readAsDataURL(file);
                                                }

                                                // Hide the icon container
                                                var iconContainer = document.querySelector('.icons');
                                                if (iconContainer) {
                                                    iconContainer.style.display = 'none';
                                                }

                                            });
                                              </script>
                                              

                                            <div class="d-flex justify-content-end mr-5">
                                                <div class="d-flex justify-content-end mr-5">
                                                    <button class="btn btn-success">Add Room <i class="bi bi-send"></i></button>
                                                </div>
                                            </div>
                                        </form>
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
	</script>

    </body>
</html>
