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
    $login_user=$row['first_name'];
	$sex=$row['sex'];
    $uid=$row['user_id'];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>My payment</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

	<script src="../bootstrap/jquery/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
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
				<li class="nav-item">
					<a class="nav-link" href="userview.php">My Loan</a>
				</li>
                <li class="nav-item active">
					<a class="nav-link" href="mypayment.php">My Payment<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="applyloan.php">Apply for a loan</a>
				</li>
			</ul>
            <?php
                $sql="SELECT * FROM notification WHERE user_id='$uid' AND status='unread' AND receiver='User'";
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

	<main class="container pt-3">
        <div class="card text-white bg-dark mx-auto">
            <div class="card-header">
                <h2>My Payment</h2>
            </div>
            <div class="card-body">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th>Payment Number</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="SELECT * FROM payment WHERE user_id=$uid ORDER BY payment_id DESC";
                            $stmt=$conn->query($sql);
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <td><?php echo $row['payment_number']; ?></td>
                            <td><?php echo number_format($row['amount']); ?></td>
                            <td><?php echo $row['payment_date']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>