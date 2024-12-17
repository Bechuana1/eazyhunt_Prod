<?php
	// if(empty($_SESSION['role']))
	// 	header('Location: login.php');

?>
<!-- <section> --><br>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">


<nav class="navbar navbar-expand-sm navbar-default sidebar" style="background-color:#212529;" id="mainNav">
      <div class="container">
        
      	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive1" aria-controls="navbarResponsive1" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive1">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
        <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-success text-decoration-none">
          <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
          <span class="fs-4">Main Nav</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
          <li class="nav-item">
            <a href="#" class="nav-link active" aria-current="page" style="background-color: green;">
                <i class="bi bi-house-door"></i>
                Home
            </a>
          </li>
          <li>
            <a href="#" class="nav-link link-dark">
                <i class="bi bi-speedometer2"></i>
              Dashboard
            </a>
          </li>
          <li>
            <a href="#" class="nav-link link-dark">
                <i class="bi bi-building"></i>
              apartments
            </a>
          </li>
          <li>
            <a href="#" class="nav-link link-dark">
                <i class="bi bi-stickies"></i>
              Reservations  
            </a>
          </li>
          
          <li>
            <a href="#" class="nav-link link-dark">
              <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#people-circle"/></svg>
              booked
            </a>
          </li>
        </ul>
        <hr>
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>mdo</strong>
          </a>
          <ul class="dropdown-menu text-small shadow">
            <li><a class="dropdown-item" href="#">New project...</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
          </ul>
        </div>
      </div>
    
<Script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></Script>  
<script>
    $(document).ready(function () {
        $("ul.nav-pills > li").click(function (e) {
            $("ul.nav-pills > li").find(".nav-link").removeAttr("style");
            $(this).find(".nav-link").attr("style", "background-color: green;");
        });
    });

  </script>
        </div>
      </div>
    </nav>

<!-- </section> -->