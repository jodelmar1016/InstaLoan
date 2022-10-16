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
	<title>Dashboard</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../Lending App/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="stylesheet" href="../styles.css">

	<script src="../bootstrap/jquery/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>

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
                <li class="nav-item active">
					<a class="nav-link" href="dashboard.php">Dashboard <span class="sr-only">(current)</span></a>
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
        $sql="SELECT * FROM users WHERE acct_type='User'";
        $stmt=$conn->query($sql);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $total_user=$stmt->rowCount();

        $sql="SELECT * FROM loan WHERE status='UNPAID'";
        $stmt=$conn->query($sql);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $unpaid=$stmt->rowCount();

        $sql="SELECT * FROM loan WHERE status='PAID'";
        $stmt=$conn->query($sql);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $paid=$stmt->rowCount();
    ?>
    <div class="container" style="margin-top: 20px;">
        <div class="row">
            <div class="col-sm-4">
                <div class="card" style="border-left: 5px solid lightblue;">
                    <div class="card-body text-right">
                        <h1 class="card-title"><b><?php echo number_format($total_user) ?></b></h1>
                        <p class="card-text">Total Users</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card" style="border-left: 5px solid lightblue;">
                <div class="card-body text-right">
                    <h1 class="card-title"><b><?php echo number_format($unpaid) ?></b></h1>
                    <p class="card-text">Current Loans</p>
                </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card" style="border-left: 5px solid lightblue;">
                <div class="card-body text-right">
                    <h1 class="card-title"><b><?php echo number_format($paid) ?></b></h1>
                    <p class="card-text">Total Completed Loans</p>
                </div>
                </div>
            </div>
        </div>

        <?php
            $current_year=date("Y");
            for($a=1;$a<=12;$a+=1){
                $sql="SELECT * FROM loan WHERE MONTH(date(date_approve))='$a' AND YEAR(date_approve)='$current_year'";
                $stmt=$conn->query($sql);
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                $val[]  = $stmt->rowCount();
            }
        ?>

        <div id="columnchart_material" style="width: 100%; height: 400px; margin-top: 20px; margin-bottom: 20px;"></div>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                ['Months', 'Loans'],
                ['January', <?php echo $val[0] ?>],
                ['February', <?php echo $val[1] ?>],
                ['March', <?php echo $val[2] ?>],
                ['April', <?php echo $val[3] ?>],
                ['May', <?php echo $val[4] ?>],
                ['June', <?php echo $val[5] ?>],
                ['July', <?php echo $val[6] ?>],
                ['August', <?php echo $val[7] ?>],
                ['September', <?php echo $val[8] ?>],
                ['October', <?php echo $val[9] ?>],
                ['November', <?php echo $val[10] ?>],
                ['December', <?php echo $val[11] ?>]
                ]);

                var options = {
                    chart: {
                        title: 'Monthly Loans',
                        subtitle: 'Year : <?php echo $current_year ?>',
                    }
                };

                var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                chart.draw(data, google.charts.Bar.convertOptions(options));
            }
        </script>
        
    </div>

</body>
</html>

<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>