<?php
require '../config/config.php';
$response = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = []; // Initialize response array

    // Get data from FORM
   $usernameOrEmail = isset($_POST['username']) ? $_POST['username'] : null ;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    
    if(isset($usernameOrEmail) && isset($password)){
        try {
            $stmt = $connect->prepare('SELECT * FROM users WHERE username = :username OR email = :username');
            $stmt->execute(array(':username' => $usernameOrEmail));
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($data) {
                if (password_verify($password, $data['password'])) { // Use password_verify for secure comparison
                    // Successful login
                    $_SESSION['id'] = $data['user_id'];
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['fullname'] = $data['full_name'];
                    $_SESSION['role'] = $data['role'];
    
                    $response['success'][] = 'logged in';
                    if ($data['role'] == 'Caretaker' || $data['role'] == 'caretaker') {
                        header('Location: ../landlord/dashboard.php');
                    } elseif ($data['role'] == 'admin') {
                        header('Location: ../admin/dashboard.php');
                    } else {
                        // Handle other roles or no role specified
                        // Redirect to a default page or display an error
                    }
                } else {
                    $response['error'][] = 'Incorrect Password';
                }
            } else {
                $response['error'][] = "User not found.";
            }
        } catch (PDOException $e) {
            $response['error'][] = 'Database error: ' . $e->getMessage();
        }
    }

    


}

//json_encode($response);


?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>User Login</title>
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
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header mb-2">
                    <h3 class="text-center font-weight-light my-4 text-success">
                        Welcome to EazyHunt
                    </h3>
                </div>

                <div class="errorContainer"></div>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="row mb-3 mt-2"></div>
                        <?php
                        if (isset($response['error'])) {
                            foreach ($response['error'] as $error) {
                                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                            }
                        } elseif (isset($response['success'])) {
                            echo '<div class="alert alert-success" role="alert">' . $response['success'] . '</div>';
                        }
                        ?>

                        <div class="form-floating mb-3">
                            <input
                                class="form-control"
                                name="username"
                                type="text"
                                placeholder="Enter your Email or Username" required/>
                            <label for="email">Email or Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input
                                class="form-control"
                                name="password"
                                type="password"
                                placeholder="Enter your Password" required/>
                            <label for="password">Password</label>
                        </div>
                        <div class="row mb-3"></div>
                        <div class="mt-4 mb-0">
                            <div class="d-grid">
                                <button
                                    class="btn btn-success btn-block text-warning"
                                    type="submit">
                                    Sign In
                                </button>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="./forgot.php" class="small text-muted">Forgot Password?</a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small">
                        <a href="register.php">
                            Don't Have an account? Go to Sign Up
                        </a>
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
