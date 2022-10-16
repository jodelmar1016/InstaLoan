<?php
    session_start();
	if(!isset($_SESSION['user'])){
        header("location: ../login.php");
    }

    include("../config.php");
    // QUERY FOR SESSION
    $user_check=$_SESSION['user'];
    $sql="SELECT * FROM users WHERE username='$user_check'";
    $stmt=$conn->query($sql);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
	$uid=$row['user_id'];
	$fname=$row['first_name'];
	$lname=$row['last_name'];
    $login_user=$row['first_name'];
	$sex=$row['sex'];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>My loan</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

	<script src="../bootstrap/jquery/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>

	<style>
		.card-header h2{
			
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="#">
			<img src="../img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
			<img src="../img/logo1.png" width="180" height="30" class="d-inline-block align-top" alt="">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<span class="navbar-text">Welcome, <?php echo $login_user ?></span>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="userview.php">My Loan<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="mypayment.php">My Payment</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="applyloan.php">Apply for a loan</a>
				</li>
			</ul>
			<?php
                $sql="SELECT * FROM notification WHERE user_id='$uid' AND status='unread'  AND receiver='User'";
                $stmt=$conn->query($sql);
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                $num=$stmt->rowCount();
            ?>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
                    <a class="nav-link" href="../notification.php">Notification <?php if($num>0) echo "<span class='badge badge-danger'>$num</span>" ?></a>
                </li>
                <li class="nav-item">
					<a class="nav-link" href="../profile.php">
                        <img src="../img/user.png" width="30" height="30" class="d-inline-block align-top" alt="">
                        My account
                    </a>
				</li>
				<li class="nav-item">
					<a class="nav-link btn btn-info btn-md" href="../logout.php">Logout</a>
				</li>
			</ul>
		</div>
	</nav>

	<?php
		$id=$uid;
		/*
		$sql="SELECT * FROM users WHERE user_id='$id'";
		$stmt=$conn->query($sql);
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		$fname
		*/

		$sql="SELECT * FROM loan WHERE user_id=$id AND status='UNPAID'";
		$stmt=$conn->query($sql);
		$temp=$stmt->fetch(PDO::FETCH_ASSOC);

		if($stmt->rowCount()>0){
	?>
	<main class="container pt-3">
        <div class="card text-white bg-dark mx-auto">
            <div class="card-header">
				<?php if($sex=="Male"){ ?>
					<img src="../img/profile-male.png" width="150" height="150" style="margin-right: 10px;" alt="">
				<?php }else{ ?>
					<img src="../img/profile-female.png" width="150" height="150" style="margin-right: 10px;" alt="">
				<?php } ?>
                <h2 class="d-inline"><?php echo ucfirst($fname)." "; echo ucfirst($lname) ?></h2>
            </div>
            <div class="card-body">
				<div class="progress">
					<div class="progress-bar bg-info" role="progressbar" style="width: <?php echo ($temp['completed']/$temp['duration'])*100; ?>%" aria-valuenow="1" aria-valuemin="0" aria-valuemax="12"></div>
				</div>
				<h5>Completed: <?php echo $temp['completed']; ?> month(s)</h5>
				<h5>Balance: <?php echo number_format($temp['balance']); ?></h5>
				<hr style="background: white">
				<h4>Loan Overview</h4>
				<h5>Loan Number: <?php echo $temp['loan_number']; ?></h5>
				<h5>Amount: <?php echo $temp['amount']; ?></h5>
				<h5>Duration: <?php echo $temp['duration']; ?> months</h5>
				<h5>Monthly Payment: <?php echo $temp['monthly']; ?></h5>
            </div>
        </div>
    </main>
	<?php }else{ ?>
	<main class="container pt-3">
        <div class="card text-white bg-dark mx-auto">
            <div class="card-header">
				<?php if($sex=="Male"){ ?>
					<img src="../img/profile-male.png" width="150" height="150" style="margin-right: 10px;" alt="">
				<?php }else{ ?>
					<img src="../img/profile-female.png" width="150" height="150" style="margin-right: 10px;" alt="">
				<?php } ?>
				<h2 class="d-inline"><?php echo ucfirst($fname)." "; echo ucfirst($lname) ?></h2>
            </div>
            <div class="card-body">
				<div class="alert alert-info text-center" role="alert">
					<h4>You have no existing loan</h4>
					<a class="btn btn-success btn-md" href="applyloan.php" role="button">Apply Now</a>
				</div>
            </div>
        </div>
    </main>
	<?php } ?>
</body>
</html>
