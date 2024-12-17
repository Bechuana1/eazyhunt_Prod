<!-- <div class="row"> -->
<div class="col-md-11 col-xs-12 col-sm-12">
	<div class="alert alert-info" role="alert">
		<?php
		if (isset($errMsg)) {
			echo '<div style="color:#FF0000;text-align:center;font-size:17px;">' . $errMsg . '</div>';
		}

		if (isset($_POST['room_listing'])) {
			$apartment_id = $_GET['apartment_id'];
			$user_id = $_GET['user_id'];
			$apt_name = $_GET['name'];
			if (isset($_POST['room_listing'])) {
				$apartment_id = $_GET['apartment_id'];
				$user_id = $_GET['user_id'];
				$apt_name = $_GET['name'];
				$type = $_POST['room_type'];
				$price = $_POST['price'];
				$vacant = $_POST['vacant'];
				$add_falities = $_POST['facilities1'];
				//include '../../uploads/rooms'

				$targetDirectory = __DIR__ . '/../../uploads/rooms/'; //this take the current dir adn make it the starting piont then relatively find the specifiedlocation.
				$uploadOk = 1;
				$uploadedImageUrls = array();
				$uploadedImagesCount = 0;

				if (isset($_FILES["image"]) && count($_FILES["image"]["name"]) <= 3) {
					$images = $_FILES["image"];

					// Loop through each selected image
					for ($i = 0; $i < count($_FILES["image"]["name"]); $i++) {
						$target_file = $targetDirectory . basename($images["name"][$i]);

						// Check if the file is an actual image
						$check = getimagesize($_FILES["image"]["tmp_name"][$i]);
						if ($check !== false) {
							if (move_uploaded_file($_FILES["image"]["tmp_name"][$i], $target_file)) {
								// Construct the relative image URL
								$imageURL = realpath($target_file);

								// Make the URL relative to the script file
								$scriptDir = realpath(__DIR__);
								$imageURL = str_replace($scriptDir, '', $imageURL);
								$imageURL = ltrim($imageURL, '/');

								// Trim the target_file before inserting it into the database
								$target_file = str_replace($targetDirectory, '', $target_file);
								$uploadedImageUrls[] = $target_file;
								$uploadedImagesCount++;
							} else {
								echo "Error uploading image: " . $images["name"][$i];
								$uploadOk = 0;
							}
						} else {
							echo "File is not an image: " . $images["name"][$i];
							$uploadOk = 0;
						}
					}
				}


				try {
					if ($uploadOk) {
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
							$imageURL = 'uploads/rooms/' . $uploadedImageUrls[$i]; //changes the base url to where the images are stored.

							// Bind the room ID, apartment ID, and image URL to the placeholders in the SQL query
							$stmt1->bindParam(':room_id', $room_id);
							$stmt1->bindParam(':apt_id', $apartment_id);
							$stmt1->bindParam(':image', $imageURL);

							// Execute the statement to insert the room image
							$stmt1->execute();
						}
					}
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
			}
		}


		?>

		<style>
			body {
				background-color: white;
			}

			.form-group input {
				border: none;
				border-bottom: 2px solid lightgreen;
				transition: border-bottom-color 0.3s ease-in-out;
			}

			.form-control:hover,
			.form-group:focus {
				outline: none !important;
				border-bottom-color: green;
			}

			.form-control:focus,
			.form-control:active {
				box-shadow: none !important;
				border-color: green !important;
			}

			label {
				color: green;
			}

			/* File selector container */
			.file-selector-container {
				position: relative;
			}


			/* Hide the file input */
			.file-selector-container input[type="file"] {
				display: none;
			}

			/* File selector button style */
			.file-selector-button {
				background-image: linear-gradient(to right, #28a745, #0f9d58 100%, #0f9d58 200%);
				background-position-x: 0%;
				background-size: 200%;
				border: 0;
				border-radius: 8px;
				color: #fff;
				padding: 1rem 1.25rem;
				text-shadow: 0 1px 1px #333;
				cursor: pointer;
				display: inline-block;
				font-size: 16px;
				transition: all 0.25s;
			}

			/* Material icon style */
			.file-selector-button i {
				vertical-align: middle;
				margin-right: 8px;
			}

			/* File selector button hover effect */
			.file-selector-button:hover {
				background-position-x: 100%;
				transform: scale(1.1);
			}

			/* File selector button disabled style */
			.file-selector-button.disabled {
				background-color: #b3b3b3;
				cursor: not-allowed;
			}

			/* Error message for maximum images */
			.error-message {
				color: red;
				font-size: 12px;
				margin-top: 4px;
			}
		</style>

		<style>
			body {
				background-color: white;
			}

			.form-group input {
				border: none;
				border-bottom: 2px solid lightgreen;
				transition: border-bottom-color 0.3s ease-in-out;
			}

			.form-control:hover,
			.form-group:focus {
				outline: none !important;
				border-bottom-color: green;
			}

			.form-control:focus,
			.form-control:active {
				box-shadow: none !important;
				border-color: green !important;
			}

			label {
				color: green;
			}
		</style>

		<h2 class="text-center">Room listing</h2>
		<form action="" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="apartment_id">Apartment Name</label>
						<input type="text" class="form-control" id="apt_name" placeholder="<?php echo $apt_name; ?>" name="apartment_id" disabled>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="type">Room type</label>
						<select type="select" class="form-control" placeholder="single, bedsitter, double..." name="room_type" style="height: 45px; text-align:center" id="room_type" required>
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
						<label for="price">*Price</label>
						<input type="number" class="form-control" id="price" placeholder="ksh 6,500" name="price" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="facilities1">more Facilities (room specific facilities)</label>
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
						<label for="vacant">Vacant/Occupied</label>
						<select class="form-control" id="vacant" name="vacant">
							<option value="1">Vacant</option>
							<option value="0">Occupied</option>
						</select>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="file-selector-container">
					<input type="file" id="image" name="image[]" accept="image/*" multiple>
					<label for="image" class="file-selector-button">
						<i class="bi  "></i> Choose Images (Max 3)
					</label>
				</div>

			</div>


			<div class="d-flex justify-content-end mr-5">
				<div class="d-flex justify-content-end mr-5">
					<button type="submit" class="btn btn-success" name='room_listing' value="room_listing">Add Room <i class="bi bi-send"></i></button>
				</div>
			</div>
		</form>
	</div>
</div>