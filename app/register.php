<?php
	require '../config/config.php';
	if(empty($_SESSION['username']))
		header('Location: login.php');


		echo "Session ID is: " . $_SESSION['id'];

	if(isset($_POST['room_listing'])) {   //changed to room listing
			$errMsg = '';
			// Get data from FROM
			$fullname = $_POST['fullname'];
			$email = $_POST['email'];
			$mobile = $_POST['mobile'];
			$alternat_mobile = $_POST['alternat_mobile'];
			$plot_number = $_POST['plot_number'];
			$country = $_POST['country'];
			$state = $_POST['state'];
			$city = $_POST['city'];
			$address = $_POST['address'];
			$landmark = $_POST['landmark'];
			$rent = $_POST['rent'];
			$deposit = $_POST['deposit'];
			$description = $_POST['description'];
			//$open_for_sharing = $_POST['open_for_sharing'];
			$user_id = $_SESSION['id'];
			$accommodation = $_POST['accommodation'];
			//$image = $_POST['image']?$_POST['image']:NULL;
			//$other = $_POST['other'];			
			$rooms = $_POST['rooms'];
			$vacant = $_POST['vacant'];
			$sale = $_POST['sale'];


			//upload an images
			$target_file = "";
			if (isset($_FILES["image"]["name"])) {
				$target_file = "uploads/".basename($_FILES["image"]["name"]);
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				// Check if image file is a actual image or fake image
			    $check = getimagesize($_FILES["image"]["tmp_name"]);			
			    if($check !== false) {
			    	move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $_FILES["image"]["name"]);
			        $uploadOk = 1;
			    } else {
			        echo "File is not an image.";
			        $uploadOk = 0;
			    }
			}
			//end of image upload


			try {
					$stmt = $connect->prepare('INSERT INTO room_rental_registrations (fullname, email, mobile, alternat_mobile, plot_number, rooms, country, state, city, address, landmark, rent, sale, deposit, description, image, accommodation, vacant, user_id) VALUES (:fullname, :email, :mobile, :alternat_mobile, :plot_number, :rooms, :country, :state, :city, :address, :landmark, :rent, :sale, :deposit, :description, :image, :accommodation, :vacant, :user_id)');
					$stmt->execute(array(
						':fullname' => $fullname,
						':email' => $email,
						':mobile' => $mobile,
						':alternat_mobile' => $alternat_mobile,
						':plot_number' => $plot_number,
						//':ap_number_of_plats' => $ap_number_of_plats,
						':rooms' => $rooms,
						':country' => $country,
						':state' => $state,
						':city' => $city,
						':address' => $address,
						':landmark' => $landmark,
						':rent' => $rent,
						':sale' => $sale,
						':deposit' => $deposit,
						':description' => $description,
						':accommodation' => $accommodation,
						':image' => $target_file,
						//':other' => $other,
						':vacant' => $vacant,
						':user_id' => $user_id
						));				

				header('Location: register.php?action=reg');
				exit;
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
	}


	if(isset($_GET['action']) && $_GET['action'] == 'reg') {
		$errMsg = 'Registered. Thank you';
	}
?>
<?php include '../include/header.php';?>
	<!-- Header nav -->	
	<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#212529;" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="../index.php">Kejahunter</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="#"><?php echo $_SESSION['fullname']; ?> <?php if($_SESSION['role'] == 'admin'){ echo "(Admin)"; } ?></a>
            </li>
            <li class="nav-item">
              <a href="../auth/logout.php" class="nav-link">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
	<!-- end header nav -->
<?php include '../include/side-nav.php';?>
<section class="wrapper" style="margin-left: 16%;margin-top: -11%;">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
	  <li class="nav-item">
	    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Room Registration</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">Apartment Registration</a>
	  </li>
	</ul>

	<div class="tab-content">
	<!-- Single room -->
	  <div class="tab-pane active" id="home" role="tabpanel"><br>
	  		<?php include 'partials/room.php';?>
	  </div>

	<!-- Apartment -->
	  <div class="tab-pane" id="profile" role="tabpanel">
	  		<?php include 'partials/apartment.php';?>	  	
	  </div>
	</div>	
</section>
<?php include '../include/footer.php';?>
