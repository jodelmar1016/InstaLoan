<!DOCTYPE html>
<html lang="en">
<head>
	<title>InstaLoan</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="fontawesome/css/all.css">
	<link rel="stylesheet" href="styles.css">

	<script src="bootstrap/jquery/jquery.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="#">
			<img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
			<img src="img/logo1.png" width="180" height="30" class="d-inline-block align-top" alt="">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#why">Why InstaLoan</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#how">How it works</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#aboutUs">About Us</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#contact">Contact</a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link btn btn-info btn-md" href="login.php">Login</a>
				</li>
			</ul>
		</div>
	</nav>

	<div class="banner text-center text-white">
		<h4>Welcome to InstaLoan</h4>
		<p><i>The fastest and easy way to get loan.</i></p>
		<a class="btn btn-success" role="button" href="login.php">Get Started</a>
	</div>

	<div class="container" id="why">
		<h2 class="text-center">Why InstaLoan?</h2>
		<div class="row text-center">
			<div class="col-sm">
				<img src="img/low.png" class="main-logo" alt="">
				<h4>Lowest Interest Rate</h4>
				<p>Enjoy the benefits without paying upfront.<br>
				We charge the lowest interest rate at only 3.49% monthly.</p>
			</div>
			<div class="col-sm">
				<img src="img/approve.png" class="main-logo" alt="">
				<h4 id="list-item-3">Approve within 24 hours</h4>
				<p>With instant approval.<br>
				InstaLoan gives you the freedom to fulfill<br>
				all your need from e-Wallet to ups, mobile loads all in the app!</p>
			</div>
			<div class="col-sm">
				<img src="img/fast.png" class="main-logo" alt="">
				<h4>Fast and Easy</h4>
				<p>With instant approval.<br>
				InstaLoan gives you the freedom to fulfill <br>
				all your need from e-Wallet to ups, mobile loads all in the app!</p>
			</div>
		</div>
	</div>

	<div class="container main-con" style="margin-top=10px;" id="how">
		<h2 class="text-center">HOW IT WORKS</h2>
		<ul class="main-list">
			<li><span><img src="img/1.png" class="num" alt=""></span>Select desired amount
				<ul class="main-list">
					<li><span><img src="img/check.png" class="in-img" alt=""></span>Submit requirements online</li>
					<li><span><img src="img/check.png" class="in-img" alt=""></span>Valid ID</li>
					<li><span><img src="img/check.png" class="in-img" alt=""></span>Proof of Income</li>
					<li><span><img src="img/check.png" class="in-img" alt=""></span>Proof of Billing</li>
				</ul>
			</li>
			<li><span><img src="img/2.png" class="num" alt=""></span>Log in to your account to see your credit</li>
			<li><span><img src="img/3.png" class="num" alt=""></span>Use credit limit to lazada and etc</li>
			<li><span><img src="img/4.png" class="num" alt=""></span>Pay installments via direct bank/e-wallet transfer, 7-11 and all remittance nationwide!</li>
		</ul>
	</div>

	<div class="container text-center" id="aboutUs">
		<h4 class='text-weight-bold'>About Us</h4>
		<p>Instaloan is the flagship poduct of FDFC a financial technology company building disruptive and innovative
			products in retail credit for Southeast Asia.
		</p>
		<p>Today, we have 100+ employees accross five cities accross two countries, working towards a cheaper
			and fairer way for Filipino to access financial services.
		</p>
	</div>

	<footer class="footer text-center bg-dark" id="contact">
		<div class="row container f-con">
			<div class="col-md-4"><h5 class="text-primary text-weight-bold">INSTALOAN</h5></div>
			<div class="col-md">
				<h5 class="text-primary text-weight-bold">LEGAL</h5>
				<p><a href="#" class="a">Terms and Conditions</a></p>
				<p><a href="#" class="a">Privacy Policy</a></p>
				<p><a href="#" class="a">Terms of Use</a></p>
			</div>

			<div class="col-md">
				<h5 class="text-primary text-weight-bold">OTHER INFORMATION</h5>
				<p><a href="#" class="a">FAQ</a></p>
				<p><a href="#" class="a">Career</a></p>
				<p><a href="#" class="a">Help</a></p>
			</div>
			<div class="col-md">
				<h5 class="text-primary text-weight-bold">CONTACT</h5>
				<p class="text-white"><i class="fas fa-home me-3"></i> New York, NY 10012, US</p>
          		<p class="text-white"><i class="fas fa-envelope me-3"></i>instaloan@gmail.com</p>
          		<p class="text-white"><i class="fas fa-phone me-3"></i> +6391234567</p>
          		<p class="text-white"><i class="fas fa-print me-3"></i> +6391234567</p>
			</div>
		</div>
		<h6 class="text-white">Get connected with us on social networks:</h6>
		<div>
			<a class="spac" href="" class="me-4 text-reset">
				<img src="img/fb.png">
			</a>
			<a class="spac" href="" class="me-4 text-reset">
				<img src="img/twitter.png">
			</a>
			<a class="spac" href="" class="me-4 text-reset">
				<img src="img/tg.png">
			</a>
			<a class="spac" href="" class="me-4 text-reset">
				<img src="img/line.png">
			</a>
			<a class="spac" href="" class="me-4 text-reset">
				<img src="img/tg.png">
			</a>
			<a class="spac" href="" class="me-4 text-reset">
				<img src="img/reddit.png">
			</a>
		</div>
		<hr style="border: 1px solid white;">
		<div class="text-white">2021 Copyright:
    	<a class="text-weight-bold site" href="#">Instaloan.com</a>
  </div>
	</footer>
	
</body>
</html>
