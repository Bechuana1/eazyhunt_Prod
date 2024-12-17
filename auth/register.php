<?php
require '../config/config.php';
$response =[];


if (isset($_POST['register'])) {
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : null;
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $confirmpass = isset($_POST['PasswordConfirm']) ? trim($_POST['PasswordConfirm']) : null;
    
    // Input validation
    if (empty($fullname) || empty($username) || empty($mobile) || empty($email) || empty($password) || empty($confirmpass)) {
        $response['error'][] = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['error'][] = 'Invalid email format.';
    } elseif ($password !== $confirmpass) {
        $response['error'][] = 'Passwords do not match.';
    } elseif (strlen($password) < 4) {
        $response['error'][] = 'Password must be at least 4 characters long.';
    }else {
        try {
            // Check if username already exists
            $stmt = $connect->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
            $stmt->execute(array(':username' => $username));
            $usernameExists = $stmt->fetchColumn();

            if ($usernameExists) {
                $response['error'][] = 'Username already exists. Please choose another one.';
                // header('Location: register.php?action=UsernameTaken');
            } else {
                // Prepare statement with parameterized queries
                $stmt = $connect->prepare('INSERT INTO users (full_name, mobile_number, email, password, created_at, username) VALUES (:fullname, :mobile, :email, :password, NOW(), :username)');
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                $stmt->execute(array(
                    ':fullname' => $fullname,
                    ':username' => $username,
                    ':password' => $passwordHash, // Use bcrypt algorithm for password hashing
                    ':email' => $email,
                    ':mobile' => $mobile,
                ));

                // Redirect to success page or display a success message
                header('Location: login.php?action=joined');
                exit;
            }
        } catch (PDOException $e) {
            $response['error'][] = 'Error: ' . $e->getMessage(); // Provide a user-friendly error message
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sign_up</title>
	<link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css">
</head>

<body>




	<nav class="navbar">
		<div class="container-fluid">
			<div class="container" style="padding: 0;">
				<header class="navbar navbar-expand-lg bg-success" style="padding: 5px;">
					<div class="container-fluid">
						<a class="navbar-brand text-warning" href="../index.php">
							<img src="../assets/images/fav.svg" width="30" height="30" alt="">
							EazyHunt
						</a>
						<div class="justify-content-end ml-5" id="navbarNav">
							<ul class="navbar-nav ml-2">
								<li class="nav-item">
									<a href="login.php"><button type="button" class="btn btn-outline-warning ml-2">login</button></a>
								</li>
							</ul>
						</div>
					</div>
				</header>
			</div>
		</div>
	</nav>
	<!-- Services -->
	<!-- <section> --><br>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>User Registration</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
            crossorigin="anonymous" />
    </head>

    <body class="bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-header ">
                            <h3 class="text-center font-weight-light my-3 text-success">
                                Create Account
                            </h3>
                        
                        </div>

                        <?php
                        if (isset($response['error'])) {
                            foreach ($response['error'] as $error) {
                                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                            }
                        } elseif (isset($response['success'])) {
                            echo '<div class="alert alert-success" role="alert">' . $response['success'] . '</div>';
                        }
                        ?>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="row mb-3 mt-2">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input
                                                class="form-control"
                                                name="fullname"
                                                type="text"
                                                placeholder="Enter your first name" required/>
                                            <label for="fullname"
                                                >Full Name</label
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input
                                                class="form-control"
                                                name="username"
                                                type="text"
                                                placeholder="Enter your username"
                                                required />
                                            <label for="username"
                                                >Username</label
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input
                                        class="form-control"
                                        name="mobile"
                                        type="tel"
                                        placeholder="Enter your phone number" 
                                        required/>
                                    <label for="PhoneNumber"
                                        >Phone number</label
                                    >
                                </div>
                                <div class="form-floating mb-3">
                                    <input
                                        class="form-control"
                                        name="email"
                                        type="text"
                                        placeholder="Enter your Email" 
                                        required/>
                                    <label for="email">Email</label>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input
                                                class="form-control"
                                                name="password"
                                                type="password"
                                                placeholder="Create a password" 
                                                required/>
                                            <label for="Password"
                                                >Password</label
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input
                                                class="form-control"
                                                name="PasswordConfirm"
                                                type="password"
                                                placeholder="Confirm password" 
                                                required/>
                                            <label for="PasswordConfirm"
                                                >Confirm Password</label
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 mb-0">
                                    <div class="d-grid">
                                        <button
                                            class="btn btn-success btn-block text-warning"
											name="register">
                                            Create Account
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center py-3">
                            <div class="small">
                                <a href="login.php"
                                    >Have an account? Go to login</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
    </body>
</html>

</body>

</html>