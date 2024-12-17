<?php
include '../include/header.php';



?>


?>
<html>

<body>
  <style>
    .custom-margin {
      margin-top: 80px;
    }

    .card-counter {
      padding-left: 0 !important;
    }

    .form-title {
      font-weight: bold;
      font-size: 24px;
      color: green;
      margin-bottom: 20px;
    }


    /* Offset the checkboxes to the right */
    .form-check-label {
      padding-left: 35px;
      position: relative;
    }

    /* Custom checkbox style */
    .form-check-input[type="checkbox"] {
      position: absolute;
      left: 0;
      opacity: 0;
      cursor: pointer;
      height: 20px;
      width: 20px;
    }

    /* Create the custom checkbox appearance */
    .form-check-input[type="checkbox"]+label:before {
      content: "";
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 2px solid lightgreen;
      border-radius: 4px;
      margin-right: 10px;
      vertical-align: middle;
    }

    /* Style the custom checkbox when checked */
    .form-check-input[type="checkbox"]:checked+label:before {
      background-color: green;
    }
  </style>
  <div class="container-fluid">
    <div id="layoutSidenav_nav">
      <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
          <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link" href="index.php#menu">
              <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
              Menu
            </a>
            <div class="sb-sidenav-menu-heading">Transfers</div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
              <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
              MONEY
              <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
              <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="index.php#mpesa">Topup</a>
                <a class="nav-link" href="index.php#send">Send to Friend</a>
                <a class="nav-link" href="index.php#balance">Balance</a>
              </nav>
            </div>
            <a class="nav-link" href="index.php#history">
              <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
              HISTORY
            </a>

            <div class="sb-sidenav-menu-heading">Addons</div>
            <a class="nav-link" href="" id="logoutLink1">
              <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
              logout
            </a>

          </div>
        </div>
        <div class="sb-sidenav-footer">


        </div>
      </nav>
    </div>




    <!-- Main content column -->
    <div class="col-10 col-sm-10 col-md-10 col-lg-10 m-0 p-0 d-flex justify-content-end">
      <div class="main">
        <?php
        if (isset($_SESSION['flash_message'])) {
          echo '<div class=" mx-auto alert alert-success mt-2 text-center" id="flash-message">';
          echo $_SESSION['flash_message'];
          unset($_SESSION['flash_message']); // clear the flash message
          echo '</div>';
        }
        ?>


        <!-- now the main contents -->

        <!-- section home -->


        [ ] to be checked

        <!-- <section class="home custom-margin" id="home">
            <div class="container-fluid" style=" margin-top:50px">
              <div class="row custom-margin pl-0">
                <div class="col-md-4 col-sm-6">
                  <div class="card-counter danger">
                    <i class="fa fa-home"></i>
                    <span class="count-numbers" id="apartmentsCount">Loading...</span>
                    <span class="count-name">Apartments</span>
                  </div>
                </div>
                
              </div>
            </div>
          </section> -->




        <section class="home custom-margin" id="home">
          <div class="container-fluid" style=" margin-top:50px">
            <div class="row custom-margin pl-0">

              <!-- the rental apartments  -->
              <div class="col-md-4 col-sm-6">
                <a href="../app/list.php">
                  <div class="card-counter danger">
                    <i class="fa fa-home"></i>
                    <span class="count-numbers" id="apartmentsCount"></span>
                    <span class="count-name">Apartments</span>
                  </div>
                </a>
              </div>

              <div class="col-md-4 col-sm-6">
                <a href="../app/list.php">
                  <div class="card-counter success">
                    <i class="fa fa-home"></i>
                    <span class="count-numbers" id="total_rooms"></span>
                    <span class="count-name">Registered Rooms</span>
                  </div>
                </a>
              </div>

              <div class="col-md-4 col-sm-6">
                <a href="../app/users.php">
                  <div class="card-counter primary">
                    <i class="fa fa-user"></i>
                    <span class="count-numbers"> to be added </span> <!-- TODO  add reserves-->
                    <span class="count-name">Reservations</span>
                  </div>
                </a>
              </div>
            </div>
          </div>

        </section>

        <hr>
        <section class="apartment" id="apartment">
          <!-- this shows the apartment Registration for -->
          <div class="container">
            <div class="row custom-margin">
              <div class="col-md-11 col-xs-12 col-sm-12"><br>
                <div class="alert alert-info" role="alert">
                  <h2 class="text-center">Apartment Registration</h2>
                  <form action="../app/functions/reg_apartment.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="apartment_name">Apartment Name</label>
                          <input type="text" class="form-control" id="apartment_name" placeholder="Apartment Name" name="apartment_name">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="county">County</label>
                          <input type="text" class="form-control" id="county" placeholder="Default KIAMBU" name="county">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="town">Town</label>
                          <input type="text" class="form-control" id="town" placeholder="Default JUJA" name="town">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="landmark">Landmark</label>
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
                          <label for="location">Location</label>
                          <select class="form-control" id="location" name="location">
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
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="facilities">Others facilities</label>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="car_parking" name="facilities[]" value="Car Parking">
                            <label class="form-check-label" for="car_parking">Car Parking</label>
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
                        </div>

                      </div>

                    </div>



                    <div class="d-flex justify-content-end mr-5">
                      <button type="submit" class="btn btn-success" name="register_apartment" value="register_apartment">Add house <i class="bi bi-house-add"></i></button>
                    </div>
                  </form>

                </div>
              </div>
            </div>
        </section>

        <hr>

        <section class="apartment custom-margin" id="apartment">
          <!-- checks all apartment and the no of rooms in them -->
          <div class="container m-0 p-0">
            <div class="row col-md-11 col-xs-12 col-sm-12 m-0 p-0">
              <h4>My Apartments</h4>
              NOTE uses the same DashStat API
              <div class="row" id="apartmentContainer"></div>

            </div>
          </div>
        </section>

        <hr>

        <section class="room custom-margin" id="room">
          <div class="container m-0 p-0">

            <?php include '../app/partials/room.php'; ?>

          </div>

        </section>
        <hr>



        <section class="comments m-0 p-0 custom-margin" id="comments">
          <div class="container m-0 p-0">
            <div class="row col-md-11 col-xs-12 col-sm-12 col-11 m-0 p-0">
              <!-- compliment/complaint -->
              <div class="container-fluid mt-3 m-0 p-0">
                <div class="row m-0 p-0">
                  <div class="col-md-12 m-0 p-0">
                    <h3>Compliments and Complaints</h3>
                    <hr>
                  </div>
                </div>
                <div class="row m-0 p-0">
                  <div class="col-md-12 m-0 p-0">
                    <?php
                    // Define sample data
                    $messages = array(
                      array('name' => 'Samantha Brown', 'message' => 'The view from my room was so stunning, I did not want to leave! Thank you for such a memorable stay!', 'timestamp' => '2023-04-28 10:30:00'),
                      array('name' => 'Jake Johnson', 'message' => 'The bed was so comfortable, I slept like a baby all night. You guys really know how to make a guest feel at home.', 'timestamp' => '2023-04-27 15:45:00'),
                      array('name' => 'Katie Nguyen', 'message' => 'I have to say, your complimentary breakfast was the highlight of my stay. That waffle maker is a game changer!', 'timestamp' => '2023-04-26 08:20:00'),
                      array('name' => 'Max Rodriguez', 'message' => 'I want to give a shoutout to the housekeeping staff for keeping my room so clean and tidy. You guys are the real MVPs!', 'timestamp' => '2023-04-25 12:10:00'),
                      array('name' => 'Olivia Brown', 'message' => 'I had a small issue with my room when I first checked in, but your staff was so quick to respond and resolve the issue. Thanks for the excellent customer service!', 'timestamp' => '2023-04-24 19:05:00')
                    );
                    ?>
                    <!-- // Generate rows for each message -->
                    <div class="container">
                      <?php foreach ($messages as $msg) { ?>
                        <div class="row mb-4">
                          <div class="col-sm-12">
                            <div class="card bg-light mr-0 ">
                              <div class="card-body ">
                                <h5 class="card-title "><u><?php echo $msg['name']; ?></u></h5>
                                <p class="card-text"><?php echo $msg['message']; ?></p>
                                <div class="d-flex justify-content-end align-items-center">
                                  <small class="text-muted"><?php echo $msg['timestamp']; ?></small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      <?php }
                      ?>
                    </div>
                  </div>

        </section>






        <!-- this is the closing tag for the main dashoard section -->
      </div>
    </div>
  </div>
  </div>
  </div>
  </div>





  <script>
    setTimeout(function() {
      var flashMessage = document.getElementById('flash-message');
      if (flashMessage) {
        flashMessage.style.transition = 'opacity 1s ease-in-out';
        flashMessage.style.opacity = 0;
        setTimeout(function() {
          flashMessage.parentNode.removeChild(flashMessage);
        }, 1000);
      }
    }, 5000); // remove message after 15 seconds
  </script>
  <script>
    //
    // JavaScript to fetch data from the API using Ajax
    document.addEventListener("DOMContentLoaded", function() {
      fetchStats();
    });

    function fetchStats() {
      // Make an Ajax request to your API endpoint
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "../api/dashStats.php", true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          updateHTML(response);
          response.apartments.forEach(apartment => {
            createApartmentCard(apartment);
          });
        }
      };
      xhr.send();
    }

    function updateHTML(data) {

      // Update the HTML with data from the API response
      document.getElementById("apartmentsCount").textContent = data.total_apartments;
      document.getElementById("total_rooms").textContent = data.total_rooms;
    }

    function createApartmentCard(apartment) {
      const cardContainer = document.getElementById('apartmentContainer');
      const cardDiv = document.createElement('div');
      cardDiv.className = 'col-md-4';

      const cardLink = document.createElement('a');
      cardLink.href = `../app/list.php?apartment_id=${apartment.apt_id}`;

      const cardCounterDiv = document.createElement('div');
      cardCounterDiv.className = 'card-counter danger';

      const cardIcon = document.createElement('i');
      cardIcon.className = 'fa fa-home';

      const countNumbersSpan = document.createElement('span');
      countNumbersSpan.className = 'count-numbers';
      countNumbersSpan.textContent = apartment.room_count;

      const countNameSpan = document.createElement('span');
      countNameSpan.className = 'count-name';
      countNameSpan.textContent = apartment.name;

      cardContainer.appendChild(cardDiv).appendChild(cardLink)
        .appendChild(cardCounterDiv).appendChild(cardIcon)
        .appendChild(countNumbersSpan).parentNode
        .appendChild(countNameSpan);
    }
  </script>

</body>




<style>
  .card-counter {
    box-shadow: 2px 2px 10px #DADADA;
    margin: 2px;
    padding: 20px 10px;
    background-color: #fff;
    height: 100px;
    border-radius: 5px;
    transition: .3s linear all;
  }

  .card-counter:hover {
    box-shadow: 4px 4px 20px #DADADA;
    transition: .3s linear all;
  }

  .card-counter.primary {
    background-color: #007bff;
    color: #FFF;
  }

  .card-counter.danger {
    background-color: #ef5350;
    color: #FFF;
  }

  .card-counter.success {
    background-color: #66bb6a;
    color: #FFF;
  }

  .card-counter.info {
    background-color: #26c6da;
    color: #FFF;
  }

  .card-counter i {
    font-size: 5em;
    opacity: 0.2;
  }

  .card-counter .count-numbers {
    position: absolute;
    right: 35px;
    top: 20px;
    font-size: 32px;
    display: block;
  }

  .card-counter .count-name {
    position: absolute;
    right: 35px;
    top: 65px;
    text-transform: capitalize;
    opacity: 0.8;
    display: block;
    font-size: 18px;
  }


  @media (max-width: 767px) {
    .nav-pills .nav-link span {
      display: none;
    }

    .nav-pills .nav-link:hover span {
      display: inline;
    }
  }
</style>
<?php include '../include/footer.php'; ?>