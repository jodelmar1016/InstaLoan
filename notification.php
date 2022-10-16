<?php
    session_start();
    include("config.php");
    include("ago.php");

    // query for session
    $user_check=$_SESSION['user'];
    $sql="SELECT * FROM users WHERE username='$user_check'";
    $stmt=$conn->query($sql);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $uid=$row['user_id'];
    $fname=$row['first_name'];
    $lname=$row['last_name'];
    $acct_type=$row['acct_type'];

    // update as read notif
    if(isset($_POST['update'])){
        $id=$_POST['update_id'];
        $sql="UPDATE notification SET status='read' WHERE id='$id'";
        $stmt=$conn->query($sql);
    }

    if(isset($_POST['markRead'])){
        $sql="UPDATE notification SET status='read'";
        $stmt=$conn->query($sql);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Notification</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="notification.css">

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
					<a class="nav-link" href="dashboard.php">Dashboard</a>
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
                <li class="nav-item active">
                    <a class="nav-link" href="notification.php">Notification <?php if($num>0) echo "<span class='badge badge-danger'>$num</span>" ?></a>
                </li>
                <li class="nav-item ">
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
                $sql="SELECT * FROM notification WHERE user_id='$uid' AND status='unread'  AND receiver='User'";
                $stmt=$conn->query($sql);
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                $num=$stmt->rowCount();
            ?>
			<ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="notification.php">Notification <?php if($num>0) echo "<span class='badge badge-danger'>$num</span>" ?></a>
                </li>
                <li class="nav-item">
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
    
    <div class="container">
        <h3>Notification</h3>
        <hr>
        <form action="" method="POST">
            <button type="submit" name="markRead" style="border: none; outline: none; backgroung-color: none;"><u>Mark all as read</u></button>
        </form>
        <?php
            if($acct_type=="Admin"){
                $sql="SELECT * FROM notification WHERE receiver='Admin' ORDER BY id DESC";
            }
            else{
                $sql="SELECT * FROM notification WHERE receiver='User' AND user_id=$uid ORDER BY id DESC";
            }
            $stmt=$conn->query($sql);
            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        ?>
        <button class="text-left notif" data-toggle="modal" data-target="#read" style="background-color: <?php if($row['status']=="unread") echo '#d1dde3' ?>;" onclick="getData(<?php echo $row['user_id'] ?>,<?php echo $row['id'] ?>,'<?php echo $row['message'] ?>','<?php echo $row['title'] ?>','<?php echo $row['date'] ?>')">
            <div class="content">
                <h5 class="d-inline"><?php echo $row['title'] ?></h5>
                <div class="ago d-inline float-right"><?php echo time_elapsed_string($row['date']) ?></div>
                <p><?php echo substr($row['message'],0,15)."..."; ?></p>
            </div>
        </button>
        <?php } ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="read" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p id="showMessage"></p>
            <br>
            <p id="date"></p>
        </div>
        <div class="modal-footer">
            <form action="" method="POST">
                <input type="hidden" id="update_id" name="update_id">
                <?php if($acct_type=="User"){ ?>
                <button type="submit" class="btn btn-secondary" name="update">Close</button>
                <?php } ?>
            </form>
            <form action="Admin/request.php" method="GET">
                <input type="hidden" id="up_id" name="up_id">
                <input type="hidden" id="u_id" name="u_id">
                <?php if($acct_type=="Admin"){ ?> 
                <button type="submit" class="btn btn-primary" name="update">View</button>
                <?php } ?>
            </form>
        </div>
        </div>
    </div>
    </div>
</body>
</html>

<script>
    function getData(user_id,id,message,title,date){
        document.getElementById("update_id").value=id;
        document.getElementById("up_id").value=id;
        document.getElementById("u_id").value=user_id;
        document.getElementById("showMessage").innerHTML=message.replaceAll("/","<br>");
        document.getElementById("exampleModalLabel").innerHTML=title;
        document.getElementById("date").innerHTML="Date: "+date;
    }
</script>