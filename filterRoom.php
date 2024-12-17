<?php
require './config/config.php';
//header('Content-Type: application/json'); // Set the response header to JSON

$response = []; // Array for search results
$unique_identifier = getUniqueDeviceIdentifier();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $keywords = $_POST['keywords'] ?? '';
  $location = $_POST['location'] ?? '';

  if (empty($keywords) && empty($location)) {
      // No search criteria provided, return an error response
      $response['error'] = 'No search criteria provided';
  } else {
      // Validate and sanitize user input
      $keywords = sanitizeInput($keywords);
      $location = sanitizeInput($location);

      // Prepare the placeholders for the keyword parameters
      $keywordPlaceholders = implode(',', array_fill(0, count(explode(' ', $keywords)), '?'));

      $premium_access = false;
      if (isset($_COOKIE['premium_access'])) {
          $encryptedData = $_COOKIE['premium_access'];

          // Validate expiry time
          $dataParts = explode(':', $encryptedData);
          if (count($dataParts) == 2) {
              $expiryTime = (int) $dataParts[1];

              if (time() < $expiryTime) {
                  $premium_access = true;
                  $checkoutRequestID = $dataParts[0]; // Extract CheckoutRequestID
              } else {
                  // Cookie has expired
                  $response['error'] = "Cookie 'premium_access' has expired.";
              }
          } else {
              // Handle invalid cookie data format
              $response['error'] = "Invalid cookie data format.";
          }
      }

      try {
          // Prepare the base query
          $baseQuery = 'SELECT 
                          r.room_id as room_id,
                          GROUP_CONCAT(ri.image_url) AS images,
                          r.type AS room_type,
                          r.price,
                          a.apartment_id AS apartment_id,
                          u.user_id AS user_id,
                          a.location AS `location`
                        FROM
                          rooms r
                          INNER JOIN apartments a ON r.apartment_id = a.apartment_id
                          INNER JOIN users u ON a.user_id = u.user_id
                          LEFT JOIN room_images ri ON r.room_id = ri.room_id';

          // Initialize the query and parameter bindings
          $query = $baseQuery;
          $bindings = [];

          // Check if both keywords and location are provided
          if (!empty($keywords) && !empty($location)) {
              $query .= ' WHERE r.type LIKE CONCAT("%", ?, "%") AND a.location LIKE CONCAT("%", ?, "%") GROUP BY r.room_id';
              $bindings = [$keywords, $location];
          }
          // Check if only keywords are provided
          elseif (!empty($keywords)) {
              $query .= ' WHERE r.type LIKE CONCAT("%", ?, "%") GROUP BY r.room_id';
              $bindings = [$keywords];
          }
          // Check if only location is provided
          elseif (!empty($location)) {
              $query .= ' WHERE a.location LIKE CONCAT("%", ?, "%") GROUP BY r.room_id';
              $bindings = [$location];
          }

          // Prepare the search query
          $stmt = $connect->prepare($query);

          // Bind the parameters using prepared statements
          foreach ($bindings as $index => $binding) {
              $stmt->bindValue($index + 1, $binding);
          }

          // Execute the search query
          $stmt->execute();

          // Fetch the search results
          $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

          // Store the search results in the $results array
          foreach ($searchResults as &$result) {
              // Add the redirect button URL with apartment_id and room_id as parameters
              $result['redirect_url'] = $premium_access ? 'more_details.php?apartment_id=' . $result['apartment_id'] . '&room_id=' . $result['room_id'] : 'pricing.php';
          }

          $response['results'] = $searchResults;
      } catch (PDOException $e) {
          // Return an error response as JSON
          $response['error'] = $e->getMessage();
      }
  }
}

// Function to sanitize user input
function sanitizeInput($input)
{
  if (is_array($input)) {
      return array_map('sanitizeInput', $input);
  }

  if (is_string($input)) {
      $input = trim($input);
      $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
      $input = filter_var($input, FILTER_DEFAULT, FILTER_FLAG_NO_ENCODE_QUOTES);
  }

  return $input;
}

// Output the response as JSON
// echo json_encode($response);
?>
<head>
  <meta charset="UTF-8">
  <title>kejaHunter</title>
  <meta name="description" content="Kejahunter is a rental listing website targeting JKUAT main campus in Juja, Kenya. Find affordable bed sitters, single rooms, and double rooms, as well as 1, 2, and 3-bedroom apartments. Get the fastest response and secure your rental today.">
  <meta name="keywords" content="Kejahunter.co.ke, Kejahunter, Kejahunter.co, rentals, Juja, Kenya, JKUAT, bed sitters, single rooms, double rooms, 1 bedroom, 2 bedrooms, 3 bedrooms, affordable, fastest response">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="assets/css/responsive.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- <style>
    .featured {
      text-transform: capitalize;

    }

    .featured .box-container {
      font-size: 62.5%;
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      margin: 0;
      padding: 0;
    }

    .featured .box-container .box {
      border: .1rem solid rgba(0, 0, 0, .2);
      box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
      border-radius: .5rem;
      overflow: hidden;
      background: #fff;
      flex: 1 1 18.75rem;
      margin: 0;
      padding: 0;
    }

    .featured .box-container .box .image-container {
      font-size: 62.5%;
      overflow: hidden;
      position: relative;
      width: 100%;
      height: 15.6rem;
    }

    .featured .box-container .box .image-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: .2s linear;
    }

    .featured .box-container .box:hover .image-container img {
      transform: scale(1.1);
    }

    .featured .box-container .box .image-container .info {
      margin: 0;
      padding: 0;
      font-size: 62.5%;
      position: absolute;
      top: .62rem;
      left: 0;
      display: flex;
    }

    .featured .box-container .box .image-container .info h3 {
      font-weight: 500;
      font-size: 0.8688rem;
      color: #fff;
      background: rgba(0, 0, 0, 0.3);
      border-radius: 0.31rem;
      padding: 0.31rem 0.93rem;
      margin-left: 0.62rem;

    }

    .featured .box-container .box .image-container .icons {
      position: absolute;
      bottom: .62rem;
      right: 0;
      display: flex;
    }

    .featured .box-container .box .image-container .icons a {
      font-size: .8688rem;
      color: #fff;
      display: flex;
      border-radius: .5rem;
      background: rgba(0, 0, 0, .3);
      margin-right: .625rem;
      padding: .437rem;
    }

    .featured .box-container .box .image-container .icons a h3 {
      font-weight: 500;
      padding-left: .31rem;
    }

    .featured .box-container .box .image-container .icons a:hover {
      background: var(--red);
    }

    .featured .box-container .box .content {
      padding: .9375rem;
    }

    .featured .box-container .box .content .price {
      display: flex;
      align-items: center;
    }

    .featured .box-container .box .content .price h3 {
      color: green;
      font-size: 1.2rem;
      margin-right: auto;
    }

    .featured .box-container .box .content .price a {
      color: #666;
      font-size: 0.937rem;
      margin-right: .31rem;
      border-radius: .31rem;
      height: 1.6rem;
      width: 1.6rem;
      line-height: 1.6rem;
      text-align: center;
      background: #f7f7f7;
    }

    .featured .box-container .box .content .price a:hover {
      background: var(--red);
      color: #fff;
    }

    .featured .box-container .box .content .location {
      padding: .625rem 0;
    }

    .featured .box-container .box .content .location h3 {
      margin: 0;
      font-size: 1.8rem;
      color: #333;
    }

    .featured .box-container .box .content .location p {
      font-size: .94rem;
      color: #666;
      line-height: .94;
      padding-top: .31rem;
      margin: 8px 0;
    }

    .featured .box-container .box .content .details {
      margin: 0;
      padding: .31rem 0;
      display: flex;
    }

    .featured .box-container .box .content .details h3 {
      margin-top: 0;
      flex: 1;
      padding: .62rem;
      border: .1rem solid rgba(0, 0, 0, .1);
      color: #999;
      font-size: .81rem;
    }

    .featured .box-container .box .content .details h3 i {
      color: #333;
      padding-left: .31rem;
    }

    .featured .box-container .box .content .buttons {
      display: flex;
      gap: .62rem;
    }

    .featured .box-container .box .content .buttons .btn {
      flex: 1;
      font-size: .94rem;
    }

    .featured .box-container .box .content .buttons .btn a {
      text-decoration: none !important;
    }
  </style> -->


</head>

<body>
  <div class="container" style="padding: 0;">
    <header class="navbar navbar-expand-lg navbar-light bg-light" style="padding: 5px;">
      <div class="container-fluid">

        <a class="navbar-brand" href="#">
          <img src="./assets/images/fav.svg" width="30" height="30" alt="">
          kejaHunter

        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end ml-2" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="#search">Search</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Features</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./pricing.php">Pricing</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#contact">FAQs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#testimonials">About</a>
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



  <!-- hero section -->

  <div class="hero_area">
    <!-- header section strats -->


    <!-- end header section -->
    <!-- slider section -->
<?php if(isset($Message)){
  ?>
  <div class="container mt-3">
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $Message ?? ''; ?>  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>

<?php
}?>

    <section class="slider_section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-4 offset-md-1">
            <div class="detail-box">
              <h1>
                <span> Modern</span> <br>
                Apartments <br>
                Houses
              </h1>
              <p>
                Find a house for rent near JKUAT, Juja absoulutely free. Yes, ni FREE!!
              </p>
              <div class="btn-box">
                <a href="auth/register.php" class="">
                  Sign-up
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end slider section -->
  </div>

  <!-- find section -->
  <section class="find_section" id="search">
    <div class="container">
      <div class="search-form">
        <form id="searchForm" method="POST" action="#featured"> <!-- Removed the action attribute -->
          <div class="form-row">
            <div class="col-md-5">
              <input type="text" class="form-control" placeholder="Bedsitters, Singles, 1bedroom.." name="keywords" value="">
            </div>
            <div class="col-md-5">
              <select type="select" class="form-control" placeholder="Location" name="location" style="height: 45px; text-align:center">
                <option value="">Select Location</option>
                <option value="Gate A">Gate A</option>
                <option value="Gate B">Gate B</option>
                <option value="Gate C">Gate C</option>
                <option value="Oasis">Oasis</option>
                <option value="Gachororo">Gachororo</option>
                <option value="Kalimoni">Kalimoni</option>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary" id="searchButton" name="search">Search</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <section class="featured" id="featured">
    <?php
     if (isset($response['results']) && is_array($response['results'])) {
      echo '
      <section class="search-results">
        <div class="container">
          <h2>Search Results</h2>
          <div class="row">';
      foreach ($response['results'] as $item):
        echo '<div class="col-md-4">
          <div class="card mb-4 box-shadow">
            <div id="carousel-' . $item['room_id'] . '" class="carousel slide image-container" data-ride="carousel">
              <div class="carousel-inner">';
        $images = explode(',', $item['images']);
        foreach ($images as $index => $image)
          echo '<div class="carousel-item ' . ($index === 0 ? 'active' : '') . '">
                <img src="' . $image . '" class="d-block w-100 img-fluid" alt="Room Image" style="height: 260px; object-fit: cover;">
              </div>';
        echo '</div>
              <a class="carousel-control-prev" href="#carousel-' . $item['room_id'] . '" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carousel-' . $item['room_id'] . '" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title">' . $item['room_type'] . '</h5>
                </div>
                <div class="col">
                  <p class="card-text">Ksh' . $item['price'] . '/month</p>
                </div>
              </div>
              <div class="mt-3">
                <a href="' . $item['redirect_url'] . '" class="btn btn-sm btn-outline-success btn-block">More Details</a>
              </div>
            </div>
          </div>
        </div>';
      endforeach;
      echo '
          </div>
        </div>
      </section>';
    } elseif (isset($response['error'])) {
      echo '<div class="alert alert-danger" role="alert">' . $response['error'] . '</div>';
    }
    
    // }
    ?>
    </div>
  </section>






  <!-- end find section -->
  <section class="about_section layout_padding-bottom" id="about">
    <div class="square-box">
      <img src="./assets/images/square.png" alt="">
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="img-box">
            <img src="./assets/images/about-img.jpg" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                About Our Apartment
              </h2>
            </div>
            <p>
              We offer Real Estate Agent services near Juja connecting Landlords and Tenants in just one click of a button.
              Get your properties located near JKUAT, Juja on our site absoulutely FREE of charge. Real Estate Agent services near JKUAT.
              <br>
              Discover the unbeatable deals at kejaHunter. With our extensive network and expertise, we bring you the finest properties at the best prices. Trust us to find your dream home and secure the ultimate value for your investment.
            </p>
            <a href="./auth/register.php">
              Register Now
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end about section -->

  <!-- rent section -->
  <section class="sale_section layout_padding-bottom" id="sales">
    <div class="container-fluid">
      <div class="heading_container">
        <h2>
          HOUSES FOR RENT
        </h2>
        <p>
          Variety of houses available.
        </p>
      </div>
      <div class="sale_container">
        <div class="box">
          <div class="img-box">
            <img src="./assets/images/s-1.jpg" alt="">
          </div>
          <div class="detail-box">
            <h6>
              Bedsitters
            </h6>
            <p>
              Embrace the perfect harmony of comfort and functionality in our stylish and compact bedsitters, where every inch is designed to elevate your living experience.

              <a href="#" onclick="filterRooms('Bedsitter')">
                Find More
              </a>
            </p>



          </div>
        </div>
        <div class="box">
          <div class="img-box">
            <img src="./assets/images/s-2.jpg" alt="">
          </div>
          <div class="detail-box">
            <h6>
              Single Rooms
            </h6>
            <p>
              Experience the allure of simplicity and convenience in a single room designed to exceed your expectations. At the cheapest rates possible.
              <a href="#" onclick="filterRooms('single')">see more</a>
            </p>
          </div>
        </div>
        <div class="box">
          <div class="img-box">
            <img src="./assets/images/s-3.jpg" alt="">
          </div>
          <div class="detail-box">
            <h6>
              double Rooms
            </h6>
            <p>
              The perfect blend of space and intimacy with our inviting double rooms. Featuring ample room for two, they offer a harmonious retreat where you can unwind and connect.
              <a href="#" onclick="filterRooms('double')">see more</a>
            </p>
          </div>
        </div>
        <div class="box">
          <div class="img-box">
            <img src="./assets/images/s-4.jpg" alt="">
          </div>
          <div class="detail-box">
            <h6>
              One Bedrooms
            </h6>
            <p>
              Luxury and comfort meet in our one-bedroom apartments. Experience privacy, spacious living areas, and style at its finest. Live your dream lifestyle in our exceptional one-bedroom apartments
              <a href="#search" onclick="filterRooms('1bedroom')">Find more</a>
            </p>
          </div>
        </div>
        <div class="box">
          <div class="img-box">
            <img src="./assets/images/s-5.jpg" alt="">
          </div>
          <div class="detail-box">
            <h6>
              Two Bedrooms
            </h6>
            <p>
              Experience space, versatility, and cherished togetherness in our stunning two-bedroom residences, featuring generously sized bedrooms and spacious living areas to unleash your creativity.
              <a href="#search" onclick="filterRooms('2bedroom')">
                Find More
              </a>
            </p>
          </div>
        </div>
        <div class="box">
          <div class="img-box">
            <img src="./assets/images/s-6.jpg" alt="">
          </div>
          <div class="detail-box">
            <h6>
              Shop spaces
            </h6>
            <p>
              Unleash your business's potential in our exceptional shop spaces. Elevate your brand and captivate customers with prime retail locations that offer endless possibilities.
              <a href="#search" onclick="filterRooms('shop')">
                Find More
              </a>
            </p>
          </div>
        </div>
      </div>
      <div class="btn-box">
        <a href="#search" onclick="filterRooms('commercial')">
          Find More
        </a>
      </div>
    </div>
  </section>
  <!-- end rent section -->

  <!-- deal section -->
  <section class="deal_section layout_padding-bottom" id="deals">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                Very Good Deal For House
              </h2>
            </div>
            <p>
              Discover the unbeatable deals at kejaHunter. With our extensive network and expertise, we bring you the finest properties at the best prices. Trust us to find your dream home and secure the ultimate value for your investment.
            </p>
            <a href="#search">
              Search A Room
            </a>
          </div>
        </div>
        <div class="col-md-6">
          <div class="img-box">
            <div class="box b1">
              <img src="./assets/images/d-1.jpg" alt="">
            </div>
            <div class="box b1">
              <img src="./assets/images/d-2.jpg" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end deal section -->


  <!-- us section -->
  <section class="us_section layout_padding2" id="us">

    <div class="container">
      <div class="heading_container">
        <h2>
          Why Choose Us
        </h2>
      </div>
      <div class="row">
        <div class="col-md-3 col-sm-6">
          <div class="box">
            <div class="img-box">
              <img src="./assets/images/u-1.png" alt="">
            </div>
            <div class="detail-box">
              <h3 class="price">
                100+
              </h3>
              <h5>
                months of House
              </h5>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="box">
            <div class="img-box">
              <img src="./assets/images/u-2.png" alt="">
            </div>
            <div class="detail-box">
              <h3>
                200+
              </h3>
              <h5>
                Apartments Registered
              </h5>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="box">
            <div class="img-box">
              <img src="./assets/images/u-3.png" alt="">
            </div>
            <div class="detail-box">
              <h3>
                1000+
              </h3>
              <h5>
                Satisfied Customers
              </h5>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="box">
            <div class="img-box">
              <img src="./assets/images/u-4.png" alt="">
            </div>
            <div class="detail-box">
              <h3>
                150+
              </h3>
              <h5>
                Cheap Rates
              </h5>
            </div>
          </div>
        </div>
      </div>
      <div class="btn-box">
        <a href="#search">
          Search Now
        </a>
      </div>
    </div>
  </section>

  <!-- end us section -->
  <section class="client_section layout_padding" id="testimonials">
    <div class="container-fluid">
      <div class="heading_container">
        <h2>
          What Our Customers Say
        </h2>
      </div>
      <div class="client_container">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="box">
                <div class="img-box">
                  <img src="./assets/images/testimonial5-man.jpeg" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    <span>Michael Otieno</span>
                    <hr>
                  </h5>
                  <p>
                    Kejahunter is definitely a life-changing platform. I had six dayss left to vacate my old apartment with little to no-time to look for new housing. i didn't know what to do, but thats when i came across Kejahunter site on the internet. i picked a few option available, i contacted the in-charge persons, went and viewed the chosen options. i finally settle on one option and now i have moved into my new home.
                  </p>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="box">
                <div class="img-box">
                  <img src="./assets/images/testimonial7-mom.jpeg" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    <span>Mary Wambui</span>
                    <hr>
                  </h5>
                  <p>
                    I can't believe i found an apartment of my choice without the hassle of having to walk around in the hot sun looking for a new houses
                    <br>
                    Thanks kejahunter
                  </p>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="box">
                <div class="img-box">
                  <img src="./assets/images/testimonial6-lady.jpeg" alt="">
                </div>
                <div class="detail-box">
                  <h5>
                    <span>Mellissa Momposhi</span>
                    <hr>
                  </h5>
                  <p>
                    One of my close friends introduced me to <b>Kejahunter</b> after unsuccessfully searching for a new house. I had hired a house agent who did his best to help me look for a new house best suited to my needs, but i was still unsatisfied. <b>Kejahunter</b> offers many beautiful housing options that it becomes quit hard for me to settle on just one option.

                  </p>
                </div>
              </div>
            </div>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="sr-only">Next</span>
          </a>
        </div>

      </div>
    </div>
  </section>

  <!-- end client section -->

  <!-- contact section -->

  <section class="contact_section " id="contact">
    <div class="container">
      <div class="heading_container">
        <h2>
          Get In Touch
        </h2>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6 px-0">
          <div class="map_container">
            <div class="map-responsive">
              <!-- <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA0s1a7phLN0iaD6-UE7m4qP-z21pH0eSc&q=Eiffel+Tower+Paris+France" width="600" height="300" frameborder="0" style="border:0; width: 100%; height:100%" allowfullscreen></iframe> -->
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-5 ">
          <div class="form_container">
            <form action="">
              <div>
                <input type="text" placeholder="Name" />
              </div>
              <div>
                <input type="email" placeholder="Email" />
              </div>
              <div>
                <input type="text" placeholder="Phone Number" />
              </div>
              <div>
                <input type="text" class="message-box" placeholder="Message" />
              </div>
              <div class="d-flex ">
                <button>
                  Send
                </button>
              </div>
            </form>
          </div>
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
                kejahunter@gmail.com
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
                <a href="#">
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
              <li>
                <a href="#">
                  Customer Support
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
        <a href="#">kejaHunter</a>
      </p>
    </div>
  </section>
  <!-- end  footer section -->


  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>