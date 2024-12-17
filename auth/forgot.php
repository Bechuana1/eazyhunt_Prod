<?php
require_once '../config/config.php';
$response = [];
$responseClass = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['identification']) && isset($_POST['answer1']) && isset($_POST['answer2'])) {
        $identification = isset($_POST['identification']) ? $_POST['identification'] : null;
        $phone_number = isset($_POST['answer1']) ? $_POST['answer1']: null;
        $apartment_name = isset($_POST['answer2']) ? $_POST['answer2'] : null;
        $default_password = password_hash('kejahunter', PASSWORD_DEFAULT); // Hash the default password

        try {
            // Query to fetch user based on email or username
            $stmt = $connect->prepare('SELECT * FROM users WHERE email = :identification OR username = :identification');
            $stmt->execute(['identification' => $identification]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Verify phone number
                if ($user['mobile_number'] == $phone_number) {
                    // Query to check if the provided apartment name belongs to the user
                    $stmt = $connect->prepare('SELECT * FROM apartments WHERE user_id = :user_id AND name = :apartment_name');
                    $stmt->execute(['user_id' => $user['user_id'], 'apartment_name' => $apartment_name]);
                    $apartment = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($apartment) {
                        // Update the password to the default password
                        $stmt = $connect->prepare('UPDATE users SET password = :password WHERE user_id = :user_id');
                        $stmt->execute(['password' => $default_password, 'user_id' => $user['user_id']]);
                        
                        $response['success'] = "Password has been reset successfully for user ". $identification .". The new default password is 'kejahunter'. Please change it after logging in.";
                        $responseClass = 'alert-success';
                        $shouldRedirect = true;
                    } else {
                        $response['error'][] = "Invalid apartment name.";
                        $responseClass = 'alert-danger';
                    }
                } else {
                    $response['error'][] = "Invalid phone number.";
                    $responseClass = 'alert-danger';
                }
            } else {
                $response['error'][] = "No user found with the provided email or username.";
                $responseClass = 'alert-danger';
            }
        } catch (PDOException $e) {
            $response['error'][] = "Error: " . $e->getMessage();
            $responseClass = 'alert-danger';
        }
    } else {
        $response['error'][] = "Please fill in all the requialert-danger fields.";
        $responseClass = 'alert-danger';
    }
} else {
    // $response['error'][] = "Invalid request method.";
    // $responseClass = 'alert-danger';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <?php if (isset($shouldRedirect)): ?>
        <meta http-equiv="refresh" content="10;url=login.php">
    <?php endif; ?>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mx-auto"> 
                    <div class="card-header text-center card-success text-success">Forgot Password</div> 
                    <div class="card-body">
                    <?php
                                if (isset($response['error'])) {
                                    foreach ($response['error'] as $error) {
                                        echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                                    }
                                } elseif (isset($response['success'])) {
                                   echo '<div class="alert alert-success" role="alert">' . $response['success'] . '</div>';
                                }
                                ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="identification">Enter Email or Username</label>
                                <input type="text" class="form-control" id="identification" name="identification" required>
                            </div>
                            <div class="form-group">
                                <label for="answer1">Enter the Associated Phone number:</label>
                                <input type="text" class="form-control" id="answer1" name="answer1" required>
                            </div>
                            <div class="form-group">
                                <label for="answer2">Tell us one of your apartments:</label>
                                <input type="text" class="form-control" id="answer2" name="answer2" required>
                            </div>
                            <div class="row justify-content-between">
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='login.php'">Back to Login</button>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success text-warning">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <!-- Bootstrap JS (optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
