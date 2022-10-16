<?php
    session_start();
    include("../config.php");

    // query for session
    $user_check=$_SESSION['user'];
    $sql="SELECT * FROM users WHERE username='$user_check'";
    $stmt=$conn->query($sql);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $login_user=$row['first_name'];
    
    if(!isset($_GET['id']) || !isset($_SESSION['user'])){
        header("location: adminview.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Overview</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../Lending App/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/all.css">

    <script src="../bootstrap/jquery/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>

    <style>
        .container{
            width: 100%
        }
        @media all and (min-width: 768px){
            .container{
                width: 60%
            }
        }
    </style>
</head>
<body>
    <!--------------NAV------------->
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
                <li class="nav-item">
					<a class="nav-link" href="dashboard.php">Dashboard</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="adminview.php">Accounts <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="request.php">Request</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="loans.php">Loans</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="payment.php">Payments</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="reports.php">Reports</a>
				</li>
			</ul>
            <?php
                $sql="SELECT * FROM notification WHERE receiver='Admin' AND status='unread'";
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
        if(isset($_GET['id'])){
            $id=$_GET['id'];
            $sql="SELECT * FROM loan WHERE user_id='$id' AND status='UNPAID'";
            $stmt=$conn->query($sql);

            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
    ?>
    <div class="container">
            <?php
                $u_id=$row['user_id'];
                $sql="SELECT * FROM users WHERE user_id='$u_id'";
                $stmt=$conn->query($sql);
                $temp=$stmt->fetch(PDO::FETCH_ASSOC);
                $fname=$temp['first_name'];
                $lname=$temp['last_name'];
            ?>
        <h3 class="text-center">Please take note of the loan details</h3>
        <hr>
        <h5>Account name: <?php echo $fname." ".$lname ?></h5>
        <h5>Loan number: <?php echo $row['loan_number'] ?></h5>
        <h5>Amount: <?php echo number_format($row['amount']) ?></h5>
        <h5>Duration: <?php echo $row['duration'] ?></h5>
        <h5>Monthly: <?php echo number_format($row['monthly']) ?></h5>
        <h5>Due date: <?php echo $row['date_approve'] ?></h5>
    </div>
    <?php }} ?>
</body>
</html>l