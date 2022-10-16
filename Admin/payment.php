<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header("location: ../login.php");
    }

    include("../config.php");
    
    // query for session
    $user_check=$_SESSION['user'];
    $sql="SELECT * FROM users WHERE username='$user_check'";
    $stmt=$conn->query($sql);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $login_user=$row['first_name'];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Payment</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../Lending App/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">

	<script src="../bootstrap/jquery/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
    
    <style>
        .card-loan{
            width: 100%;
        }
        @media all and (min-width: 1200px){
            .card-loan{
                width: 65%;
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
					<a class="nav-link" href="adminview.php">Accounts</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="request.php">Request</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="loans.php">Loans</a>
				</li>
                <li class="nav-item active">
					<a class="nav-link" href="payment.php">Payments<span class="sr-only">(current)</span></a>
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

    <!--------------TABLE------------->
    <main class="container pt-3">
        <div class="card card-loan text-white bg-dark mx-auto">
            <div class="card-header">
                <h2 class="d-inline">Payment Table</h2>
                <form action="" method="GET" class="d-inline">
                    <button class="btn btn-primary" name="btnRefresh" onclick="document.getElementById('search').value=''">Refresh</button>
                    <div class="form-group float-right">
                        <div class="input-group">
                            <input type="number" class="lnumber" name="search" id="search" placeholder="Payment Number" value="<?php echo htmlentities(isset($_GET['search'])?$_GET['search']:''); ?>">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" name="btnSearch">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>Payment Number</th>
                            <th>User ID</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="SELECT * FROM payment ORDER BY payment_id DESC";
                            $stmt=$conn->query($sql);
                            if($stmt->rowCount()==0)
                                $message="No Record";
                            if(isset($_GET['btnRefresh'])){
                                $sql="SELECT * FROM payment ORDER BY payment_id DESC";  // BUTTON REFRESH
                            }
                            if(isset($_GET['btnSearch'])&&$_GET['search']!=""){
                                $val=$_GET['search'];
                                $sql="SELECT * FROM payment WHERE payment_number=$val";    // BUTTON SEARCH
                            }else{
                                $sql="SELECT * FROM payment ORDER BY payment_id DESC";
                            }
                            $stmt=$conn->query($sql);
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <?php
                                // GET FIRST NAME AND LAST NAME
                                $id=$row['user_id'];
                                $sql="SELECT * FROM users WHERE user_id=$id";
                                $stmt2=$conn->query($sql);
                                $temp=$stmt2->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <td><?php echo $row['payment_number']; ?></td>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $temp['first_name']; ?></td>
                            <td><?php echo $temp['last_name']; ?></td>
                            <td><?php echo number_format($row['amount']); ?></td>
                            <td><?php echo $row['payment_date']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php if(isset($message)){ ?>
                <div class="alert alert-success text-center" role="alert">
                    <?php echo $message ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>
    
</body>
</html>