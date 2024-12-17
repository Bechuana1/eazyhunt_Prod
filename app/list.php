<?php
require '../config/config.php';
if (empty($_SESSION['username']))
	header('Location: login.php');

//  
// print_r($data1);	
// echo "<br><br><br>";
// print_r($data2);
// echo "<br><br><br>";	
// print_r($data);	
?>

<!-- Header nav -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color:#212529;" id="mainNav">
	<div class="container">
		<a class="navbar-brand js-scroll-trigger" href="../index.php">HaoFinder</a>
		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
			Menu
			<i class="fa fa-bars"></i>
		</button>
		<div class="collapse navbar-collapse" id="navbarResponsive">
			<ul class="navbar-nav text-uppercase ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="#"><?php echo $_SESSION['fullname']; ?> <?php if ($_SESSION['role'] == 'admin') {
																							echo "(Admin)";
																						} ?></a>
				</li>
				<li class="nav-item">
					<a href="../auth/logout.php" class="nav-link">Logout</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
<!-- end header nav -->
<section class="wrapper" style="margin-left: 0%;margin-top: 23%;">


	<ul class="nav nav-pills nav-fill gap-2 p-1 small bg-primary rounded-5 shadow-sm" id="pillNav2" role="tablist" style="--bs-nav-link-color: var(--bs-white); --bs-nav-pills-link-active-color: var(--bs-primary); --bs-nav-pills-link-active-bg: var(--bs-white);">
		<li class="nav-item" role="presentation">
			<a href="../auth/dashboard.php"><button class="nav-link active rounded-5" id="home-tab2" data-bs-toggle="tab" type="button" role="tab" aria-selected="true">Home</button></a>
		</li>
	</ul>
</section>
<?php include '../include/footer.php'; ?>