<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header("location: ../login.php");
    }

    $u_id;
    if(isset($_GET['id'])){
        $u_id=$_GET['id'];
    }

    include("../config.php");

    // query for session
    $user_check=$_SESSION['user'];
    $sql="SELECT * FROM users WHERE username='$user_check'";
    $stmt=$conn->query($sql);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $get_id=$row['user_id'];
    $login_user=$row['first_name'];
    $last_n=$row['last_name'];
    $acct_type=$row['acct_type'];

    // INSERTING LOAN APPLICATION
    if(isset($_POST['apply'])){
        if($acct_type=="Admin"){
            if(isset($_GET['id'])){
                // existing loan
                $sql="SELECT * FROM loan WHERE user_id=$u_id AND status='UNPAID'";
                $stmt=$conn->query($sql);
                if($stmt->rowcount()>0){
                    $message="You have an existing loan, Please settle first";
                }
                else{
                    // Insert into loan table
                    $ctrl_number=rand(10000000,99999999);
                    $bal=($_POST['duration']*$_POST['monthly']);

                    $sql="INSERT INTO loan (user_id, amount, duration, monthly, balance, loan_number, approve_by) VALUES (:uid, :amount, :duration, :monthly, :balance, :loan_number, :approve_by)";
                    $stmt=$conn->prepare($sql);
                    $stmt->execute([':uid'=>$u_id,
                                    ':amount'=>$_POST['amount'],
                                    ':duration'=>$_POST['duration'],
                                    ':monthly'=>$_POST['monthly'],
                                    ':balance'=>$bal,
                                    ':loan_number'=>$ctrl_number,
                                    ':approve_by'=>$get_id]);
                    header("location: ../Admin/overview.php?id=$u_id");
                }
            }
        }
        else{
            // check if there is an existing loan application
            $id=$row['user_id'];
            $sql="SELECT * FROM loan WHERE user_id=$id AND status='UNPAID'";
            $stmt=$conn->query($sql);
            if($stmt->rowcount()>0){
                $message="You have an existing loan, Please settle first";
            }
            else{
                $sql="INSERT INTO request (user_id, amount, duration, monthly) VALUES (:uid, :amount, :duration, :monthly)";
                $stmt=$conn->prepare($sql);
                $stmt->execute([':uid'=>$row['user_id'],
                                ':amount'=>$_POST['amount'],
                                'monthly'=>$_POST['monthly'],
                                ':duration'=>$_POST['duration']]);

                // set notification for admin
                $sql="INSERT INTO notification (title, message, receiver, user_id) VALUES (:title, :message, :receiver, :usid)";
                $stmt=$conn->prepare($sql);
                $stmt->execute([':title'=>"Loan Application", ':message'=>"$login_user $last_n requested for a loan", ':receiver'=>"Admin", ':usid'=>$get_id]);
            }
        }
    }
    
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Apply loan</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">

	<script src="../bootstrap/jquery/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>

    <style>
        .container-apply{
            margin: none;
        }
        .btn-apply{
            width: 100%;
            background: #6cd9c3;
            border: 2px solid #044969;
            border-radius: 15px;
        }
        @media all and (min-width: 768px){
            .container-apply{
                width: 75%;
                margin-top: 20px;
                margin-bottom: 30px;
                border-radius: 15px;
                box-shadow: 5px 10px 18px #888888;
            }
        }
        @media all and (min-width: 1200px){
            .container-apply{
                width: 50%;
                margin-top: 20px;
                margin-bottom: 30px;
                border-radius: 15px;
                box-shadow: 5px 10px 18px #888888;
            }
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
		
        <?php if($acct_type=="Admin"){ ?>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
                <li class="nav-item">
					<span class="navbar-text">Welcome, <?php echo $login_user ?></span>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="../Admin/dashboard.php">Dashboard</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="../Admin/adminview.php">Accounts</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="../Admin/request.php">Request</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="../Admin/loans.php">Loans</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="../Admin/payment.php">Payments</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="../Admin/reports.php">Reports</a>
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
                <li class="nav-item ">
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
        <?php }else{ ?>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
                <li class="nav-item">
					<span class="navbar-text">Welcome, <?php echo $login_user ?></span>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="userview.php">My Loan</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="mypayment.php">My Payment</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="applyloan.php">Apply for a loan<span class="sr-only">(current)</span></a>
				</li>
			</ul>
            <?php
                $sql="SELECT * FROM notification WHERE user_id='$get_id' AND status='unread' AND receiver='User'";
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
        <?php } ?>
	</nav>

    <div class="container-apply">
        <?php
            $sql="SELECT * FROM users WHERE username='$user_check' AND first_name='$login_user'";
            $stmt=$conn->query($sql);
            $user=$stmt->fetch(PDO::FETCH_ASSOC);
            $id=$user['user_id'];

            $sql="SELECT * FROM request WHERE user_id=$id";
            $stmt=$conn->query($sql);
            $req=$stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount()==0){
        ?>
        <h3 class="font-weight-bold">Loan Application</h3>
        <form action="" method="POST">
            <div class="form-group">
                <input type="range" class="slider" min="1000" max="5000" step="500" value="3000" name="amount" id="amount">
                <label for="">Amount: <span id="val"></span></label>
            </div>
            <div class="form-group">
                <input type="range" class="slider" min="3" max="12" value="6" name="duration" id="duration">
                <label for="">Duration: <span id="months"></span> months</label>
            </div>
            <div class="form-group">
                <h4>Monthly Payment</h4>
                <h1 id="payment"></h1>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-apply font-weight-bold" name="apply">Apply now</button>
            </div>
            <input type="hidden" id="monthly" name="monthly">
            <?php if(isset($message)){ ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $message ?>
            </div>
            <?php } ?>
        </form>
        <?php }else{ ?>
            <div class="card text-white bg-dark mx-auto text-center">
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Amount</th>
                            <th>Duration</th>
                            <th>Monthly</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $user['first_name']; ?></td>
                            <td><?php echo $user['last_name']; ?></td>
                            <td><?php echo number_format($req['amount']); ?></td>
                            <td><?php echo $req['duration']; ?></td>
                            <td><?php echo number_format($req['monthly']); ?></td>
                            <td><?php echo $req['status']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</body>
</html>

<script>
    window.onload=function(){
        var a=document.getElementById("amount").value;
        var b=document.getElementById("duration").value;
        document.getElementById("val").innerHTML=a;
        document.getElementById("months").innerHTML=b;
        a=a*0.03+parseInt(a);
        document.getElementById("payment").innerHTML=Math.round(a/b);
        document.getElementById("monthly").value=Math.round(a/b);
    }

    var slider1=document.getElementById("amount");
    var output1=document.getElementById("val");
    output1.innerHTML=slider1.value;
    slider1.oninput=function(){
        output1.innerHTML=this.value;
        var d=document.getElementById("duration").value;
        var temp=this.value*0.03+parseInt(this.value);
        document.getElementById("payment").innerHTML=Math.round(temp/d);
        document.getElementById("monthly").value=Math.round(temp/d);
    }

    var slider2=document.getElementById("duration");
    var output2=document.getElementById("months");
    output2.innerHTML=slider2.value;
    slider2.oninput=function(){
        output2.innerHTML=this.value;
        var a=document.getElementById("amount").value;
        var temp=a*0.03+parseInt(a);
        document.getElementById("payment").innerHTML=Math.round(temp/this.value);
        document.getElementById("monthly").value=Math.round(temp/this.value);
    }
</script>
