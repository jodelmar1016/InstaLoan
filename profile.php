<?php
    session_start();
    include("config.php");
    
    // query for session
    $user_check=$_SESSION['user'];
    $sql="SELECT * FROM users WHERE username='$user_check'";
    $stmt=$conn->query($sql);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);

	$uid=$row['user_id'];
    $fname=$row['first_name'];
    $lname=$row['last_name'];
    $sex=$row['sex'];
    $bdate=$row['birthdate'];
    $cnumber=$row['contact_number'];
    $email=$row['email'];
    $address=$row['address'];
    $uname=$row['username'];
    $pword=$row['password'];
	$acct_type=$row['acct_type'];

	if(isset($_POST['btnEdit'])){
		$sql="UPDATE users SET first_name=:fname, last_name=:lname, sex=:sex, birthdate=:bdate, contact_number=:cnumber, email=:email, address=:address, username=:uname, password=:pword WHERE user_id=:uid";
		$stmt=$conn->prepare($sql);
		$stmt->execute([':fname'=>$_POST['fname'],
						':lname'=>$_POST['lname'],
						':sex'=>$_POST['sex'],
						':bdate'=>date('Y-m-d',strtotime($_POST['bdate'])),
						':cnumber'=>$_POST['cnumber'],
						':email'=>$_POST['email'],
						':address'=>$_POST['address'],
						':uname'=>$_POST['uname'],
						':pword'=>$_POST['pword'],
						':uid'=>$uid]);
		header("location: profile.php");
		//echo '<script>alert("Profile Updated");</script>';
	}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Profile</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="styles.css">

	<script src="bootstrap/jquery/jquery.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
    <!--------------NAV------------->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="#">
			<img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
			<img src="img/logo1.png" width="180" height="30" class="d-inline-block align-top" alt="">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		
		<?php if($acct_type=="Admin"){ ?>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
                <li class="nav-item">
					<span class="navbar-text">Welcome, <?php echo $fname ?></span>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="Admin/dashboard.php">Dashboard <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="Admin/adminview.php">Accounts</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="Admin/request.php">Request</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="Admin/loans.php">Loans</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="Admin/payment.php">Payments</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="Admin/reports.php">Reports</a>
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
                    <a class="nav-link" href="notification.php">Notification <?php if($num>0) echo "<span class='badge badge-danger'>$num</span>" ?></a>
                </li>
                <li class="nav-item  active">
					<a class="nav-link" href="profile.php">
                        <img src="img/user.png" width="30" height="30" class="d-inline-block align-top" alt="">
                        My account
                    </a>
				</li>
				<li class="nav-item">
					<a class="nav-link btn btn-info btn-md" href="logout.php">Logout</a>
				</li>
			</ul>
		</div>
		<?php }else{ ?>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<span class="navbar-text">Welcome, <?php echo $fname ?></span>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="User/userview.php">My Loan</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="User/mypayment.php">My Payment</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="User/applyloan.php">Apply for a loan</a>
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
                    <a class="nav-link" href="notification.php">Notification <?php if($num>0) echo "<span class='badge badge-danger'>$num</span>" ?></a>
                </li>
                <li class="nav-item active">
					<a class="nav-link" href="profile.php">
                        <img src="img/user.png" width="30" height="30" class="d-inline-block align-top" alt="">
                        My account
                    </a>
				</li>
				<li class="nav-item">
					<a class="nav-link btn btn-info btn-md" href="logout.php">Logout</a>
				</li>
			</ul>
		</div>
		<?php } ?>
	</nav>

	<div class="container p-container">
		<div class="card bg-dark text-white">
			<div class="card-header">
				<div class="bg-primary p-profile-pic"><h1><?php echo strtoupper($fname[0].$lname[0]); ?></h1></div>
				<h4 class="d-inline name"><?php echo ucfirst($fname)." ".ucfirst($lname) ?></h4>
				<button class="btn btn-primary float-right" data-toggle="modal" data-target="#editModal">Edit</button>
			</div>
			<div class="card-body">
				<div class="row p-row">
					<div class="col-md-2"><p>First name:</p></div>
					<div class="col-md-4"><p class="p-val"><?php echo $fname ?></p></div>
					<div class="col-md-2"><p>Last name:</p></div>
					<div class="col-md-4"><p class="p-val"><?php echo $lname ?></p></div>
				</div>
				<div class="row p-row">
					<div class="col-md-2"><p>Sex:</p></div>
					<div class="col-md-4"><p class="p-val"><?php echo $sex ?></p></div>
					<div class="col-md-2"><p>Birthdate:</p></div>
					<div class="col-md-4"><p class="p-val"><?php echo $bdate ?></p></div>
				</div>
				<div class="row p-row">
					<div class="col-md-2"><p>Contact:</p></div>
					<div class="col-md-4"><p class="p-val"><?php echo $cnumber ?></p></div>
					<div class="col-md-2"><p>Email:</p></div>
					<div class="col-md-4"><p class="p-val"><?php echo $email ?></p></div>
				</div>
				<div class="row p-row">
					<div class="col-md-2"><p>Address:</p></div>
					<div class="col-md-10"><p class="p-val"><?php echo $address ?></p></div>
				</div>
				<div class="row p-row">
					<div class="col-md-2"><p>Username:</p></div>
					<div class="col-md-4"><p class="p-val"><?php echo $uname ?></p></div>
					<div class="col-md-2"><p>Password:</p></div>
					<div class="col-md-4"><p class="p-val"><?php echo str_repeat("*",strlen($pword)) ?></p></div>
				</div>
			</div>
		</div>
	</div>

	<!---------EDIT MODAL------->
	<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="exampleModalLabel">Edit Profile</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="" method="POST">
				<div class="modal-body">
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="">First name</label>
								<input type="text" class="form-control" value="<?php echo $fname ?>" name="fname" required>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="">Last name</label>
								<input type="text" class="form-control" value="<?php echo $lname ?>" name="lname" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-check">
								<input type="radio" class="form-check-input" value="Male" <?php echo $sex=="Male"?"checked":""; ?> name="sex" required>
								<label for="" class="form-check-label">Male</label><br>
								<input type="radio" class="form-check-input" value="Female" <?php echo $sex=="Female"?"checked":""; ?> name="sex" required>
								<label for="" class="form-check-label">Female</label>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="">Birthdate</label>
								<input type="date" class="form-control" value="<?php echo $bdate ?>" name="bdate">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="">Contact Number</label>
								<input type="number" class="form-control" max-length="11" value="<?php echo $cnumber ?>" name="cnumber" required>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="">Email</label>
								<input type="email" class="form-control" value="<?php echo $email ?>" name="email" >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="">Address</label>
								<input type="text" class="form-control" value="<?php echo $address ?>" name="address">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="">Username</label>
								<input type="text" class="form-control" value="<?php echo $uname ?>" name="uname" required>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="">Password</label>
								<input type="password" class="form-control" value="<?php echo $pword ?>" name="pword" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" name="btnEdit">Save changes</button>
				</div>
			</form>
		</div>
		</div>
	</div>


</body>
</html>
