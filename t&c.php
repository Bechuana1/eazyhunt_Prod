<head>
  <meta charset="UTF-8">
  <title>EazyHunt</title>
  <meta name="description" content="Kejahunter is a rental listing website targeting JKUAT main campus in Juja, Kenya. Find affordable bed sitters, single rooms, and double rooms, as well as 1, 2, and 3-bedroom apartments. Get the fastest response and secure your rental today.">
  <meta name="keywords" content="Kejahunter.co.ke, Kejahunter, Kejahunter.co, rentals, Juja, Kenya, JKUAT, bed sitters, single rooms, double rooms, 1 bedroom, 2 bedrooms, 3 bedrooms, affordable, fastest response">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="assets/css/responsive.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"><style>
    .detail-box {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    
        text-align: center;
    }
    .detail-box .heading_container h2 {
        margin-bottom: 20px;
    }
    .detail-box p {
        margin: 0;
    }
    .pdf-link {
        margin-top: 20px;
    }
</style>
</head>

<body>
  <div class="container" style="padding: 0;">
    <header class="navbar navbar-expand-lg navbar-light bg-light" style="padding: 5px;">
      <div class="container-fluid">

        <a class="navbar-brand" href="#">
          <img src="./assets/images/fav.svg" width="30" height="30" alt="">
          EazyHunt

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
                    Terms and Conditions
                </h2>
                </div>
                <p>
                    PDF document, version 20-07-2024:
                <br>
                    <div class="pdf-link">
                        <a href="documents/eazyhunt T&C.pdf" class="btn btn-success" download>Download T&C's PDF</a>
                    </div>
                </p>
            </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->


 
  <!-- contact section -->

  <section class="contact_section " id="contact">
    <div class="container">
      <div class="heading_container">
        <h2>
          Get In Touch
        </h2>
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
                <a href="./landlord/faqs.html">
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
        <a href="#">EazyHunt</a>
      </p>
    </div>
  </section>



  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>