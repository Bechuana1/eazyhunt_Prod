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
echo json_encode($response);
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

  <?php if (isset($response['results']) && is_array($response['results'])): ?>
  <section class="search-results">
    <div class="container">
      <h2>Search Results</h2>
      <div class="row">
        <?php foreach ($response['results'] as $item): ?>
          <div class="col-md-4">
            <div class="card mb-4 box-shadow">
              <div id="carousel-<?php echo $item['room_id']; ?>" class="carousel slide image-container" data-ride="carousel">
                <div class="carousel-inner">
                  <?php 
                  $images = explode(',', $item['images']);
                  foreach ($images as $index => $image): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo $image; ?>" class="d-block w-100 img-fluid" alt="Room Image" style="height: 260px; object-fit: cover;">

                    </div>
                  <?php endforeach; ?>
                </div>
                <a class="carousel-control-prev" href="#carousel-<?php echo $item['room_id']; ?>" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carousel-<?php echo $item['room_id']; ?>" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title"><?php echo $item['room_type']; ?></h5>
                  </div>
                  <div class="col">
                    <p class="card-text">Ksh<?php echo $item['price']; ?>/month</p>
                  </div>
                </div>
                <div class="mt-3">
                  <a href="<?php echo $item['redirect_url']; ?>" class="btn btn-sm btn-outline-success btn-block">More Details</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php elseif (isset($response['error'])): ?>
  <div class="alert alert-danger" role="alert">
    <?php echo $response['error']; ?>
  </div>
<?php endif; ?>

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
 



  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>