<?php
require __DIR__ . '/../config/config.php';
if (empty($_SESSION['username']))
    header('Location: ../auth/login.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>kejahunter</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <!-- Font Awesome icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .navbar-toggler:focus {
            outline: none;
            box-shadow: none;
        }
    </style>

</head>


<!-- Header nav -->
<nav class="navbar navbar-expand-lg navbar-expand-md fixed-top" style="background-color:darkgreen; font-style:italic; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif " id="mainNav">

    <div class="container">
        <a class="navbar-brand js-scroll-trigger" style="color: rgb(254,209,54);" href="../index.php">kejahunter</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""><i class="fas fa-bars" style="color:white;"></i></span>
        </button>
        <div class="collapse navbar-collapse justify-content-md-end" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ms-auto">
                <li class="nav-item ">
                    <a class="nav-link" href="#" style="color: white
                  ;"><?php echo $_SESSION['fullname']; ?> <?php if ($_SESSION['role'] == 'admin') {
                                                                echo "(Admin)";
                                                            } ?></a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link" style="color: white
                  ;">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Header nav -->