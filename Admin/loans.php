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

    // COMPUTATION
    if(isset($_POST['btnPayment'])){
        $bal=$_POST['bal'];
        $pay=$_POST['pay'];
        $bal=$bal-$pay;
        $lnumber=$_POST['lnumber'];
        $completed=$_POST['completed']+1;

        // UPDATE LOAN
        $sql="UPDATE loan SET balance=$bal, completed=$completed WHERE loan_number=$lnumber";
        $stmt=$conn->query($sql);

        // GET USER ID
        $sql="SELECT * FROM loan WHERE loan_number=$lnumber";
        $stmt=$conn->query($sql);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $uid=$row['user_id'];
        $payment_number=rand(10000000,99999999);

        // Notif
        $mess="Payment Received/Your new balance is ".number_format($bal);
        $sql="INSERT INTO notification (title, message, receiver, user_id) VALUES ('Payment', '$mess','User','$uid')";
        $stmt=$conn->query($sql);

        // UPDATE LOAN STATUS IF DURATION AND COMPLETED ARE EQUAL
        if($row['duration']==$row['completed']){
            $sql="UPDATE loan SET status='PAID' WHERE loan_number=$lnumber";
            $conn->query($sql);

            $mess="Your loan has been fully paid.";
            $sql="INSERT INTO notification (title, message, receiver, user_id) VALUES ('Payment', '$mess','User','$uid')";
            $stmt=$conn->query($sql);
        }

        // INSERT PAYMENT
        $sql="INSERT INTO payment (payment_number, user_id, amount) VALUES ($payment_number, $uid, $pay)";
        $conn->query($sql);

    }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Loans</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../Lending App/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">

	<script src="../bootstrap/jquery/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>

    <style>
        .container-fluid{
            width: 100%;
        }
        @media all and (min-width: 1200px){
            .container-fluid{
                width: 90%;
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
                <li class="nav-item active">
					<a class="nav-link" href="loans.php">Loans<span class="sr-only">(current)</span></a>
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

    <!--------------TABLE------------->
    <main class="container-fluid pt-3">
        <div class="card card-loan text-white bg-dark mx-auto">
            <div class="card-header">
                <h2 class="d-inline">Loan Table</h2>
                <form action="" method="GET" class="d-inline">
                    <button class="btn btn-primary" name="btnRefresh" onclick="document.getElementById('search').value=''">Refresh</button>
                    <div class="form-group float-right">
                        <div class="input-group">
                            <input type="number" class="lnumber" name="search" id="search" placeholder="Loan Number" value="<?php echo htmlentities(isset($_GET['search'])?$_GET['search']:''); ?>">
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
                            <th>Loan Number</th>
                            <th>User ID</th>
                            <th>Amount</th>
                            <th>Duration</th>
                            <th>Monthly</th>
                            <th>Completed</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="SELECT * FROM loan WHERE status='UNPAID'";
                            $stmt=$conn->query($sql);
                            if($stmt->rowCount()==0)
                                $message="No Record";
                            if(isset($_GET['btnRefresh'])){
                                $sql="SELECT * FROM loan WHERE status='UNPAID'";  // BUTTON REFRESH
                            }
                            if(isset($_GET['btnSearch'])&&$_GET['search']!=""){
                                $val=$_GET['search'];
                                $sql="SELECT * FROM loan WHERE loan_number=$val AND status='UNPAID'";    // BUTTON SEARCH
                            }else{
                                $sql="SELECT * FROM loan WHERE status='UNPAID'";
                            }
                            $stmt=$conn->query($sql);
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <?php
                                // GET FIRST NAME AND LAST NAME OF USER
                                $id=$row['user_id'];
                                $sql="SELECT * FROM users WHERE user_id=$id";
                                $stmt2=$conn->query($sql);
                                $temp=$stmt2->fetch(PDO::FETCH_ASSOC);

                                // GET FIRST NAME AND LAST NAME OF ADMIN
                                $id=$row['approve_by'];
                                $sql="SELECT * FROM users WHERE user_id=$id";
                                $stmt3=$conn->query($sql);
                                $temp1=$stmt3->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <td><?php echo $row['loan_number']; ?></td>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo number_format($row['amount']); ?></td>
                            <td><?php echo $row['duration']; ?></td>
                            <td><?php echo number_format($row['monthly']); ?></td>
                            <td><?php echo $row['completed']; ?></td>
                            <td><?php echo number_format($row['balance']); ?></td>
                            <td>
                                <button class="btn btn-info" data-toggle="modal" data-target="#viewModal" onclick="getDetails('<?php echo $temp['first_name']; ?>',
                                                                                                                            '<?php echo $temp['last_name']; ?>',
                                                                                                                            '<?php echo $temp1['first_name']; ?>',
                                                                                                                            '<?php echo $temp1['last_name']; ?>',
                                                                                                                            <?php echo $row['amount']; ?>,
                                                                                                                            <?php echo $row['duration']; ?>,
                                                                                                                            <?php echo $row['balance']; ?>,
                                                                                                                            <?php echo $row['monthly']; ?>,
                                                                                                                            <?php echo $row['loan_number']; ?>, 
                                                                                                                            <?php echo $row['completed']; ?>,
                                                                                                                            '<?php echo $row['date_approve']; ?>',
                                                                                                                            '<?php echo $row['status']; ?>')">View</button>
                                <button class="btn btn-success" data-toggle="modal" data-target="#paymentModal" onclick="getData('<?php echo $temp['first_name']; ?>',
                                                                                                                                '<?php echo $temp['last_name']; ?>',
                                                                                                                                <?php echo $row['balance']; ?>,
                                                                                                                                <?php echo $row['monthly']; ?>,
                                                                                                                                <?php echo $row['loan_number']; ?>, 
                                                                                                                                <?php echo $row['completed'] ?>)">Add payment</button>
                            </td>
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
    
    <!-----------ADD PAYMENT----------->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">First name</label>
                                    <input type="text" class="form-control" name="fname" id="fname" readonly>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Last name</label>
                                    <input type="text" class="form-control" name="lname" id="lname" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Balance</label>
                            <input type="number" class="form-control" name="bal" id="bal" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Amount to pay</label>
                            <input type="number" class="form-control" name="pay" id="pay" readonly>
                        </div>
                        <input type="hidden" name="lnumber" id="lnumber">
                        <input type="hidden" name="completed" id="completed">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="btnPayment">Submit</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-----------LOAN OVERVIEW----------->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Loan Details</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <h5>Account name:</h5>
                        </div>
                        <div class="col">
                            <h5 id="acct_name"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h5>Loan number:</h5>
                        </div>
                        <div class="col">
                            <h5 id="loan_number"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h5>Amount:</h5>
                        </div>
                        <div class="col">
                            <h5 id="amount"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h5>Duration:</h5>
                        </div>
                        <div class="col">
                            <h5 id="duration"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h5>Monthly:</h5>
                        </div>
                        <div class="col">
                            <h5 id='monthly'></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h5>Completed:</h5>
                        </div>
                        <div class="col">
                            <h5 id="comp"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h5>Balance:</h5>
                        </div>
                        <div class="col">
                            <h5 id="balance"></h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <h5>Approve By:</h5>
                        </div>
                        <div class="col">
                            <h5 id="approve_by"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h5>Date Approve:</h5>
                        </div>
                        <div class="col">
                            <h5 id="date_approve"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h5>Status:</h5>
                        </div>
                        <div class="col">
                            <h5 class="font-weight-bold" id="status"></h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    function getData(fname,lname,balance,monthly,lnumber,completed){
        document.getElementById("fname").value=fname;
        document.getElementById("lname").value=lname;
        document.getElementById("bal").value=balance;
        document.getElementById("pay").value=monthly;
        document.getElementById("lnumber").value=lnumber;
        document.getElementById("completed").value=completed;
    }

    function getDetails(fname,lname,admin_fname,admin_lname,amount,duration,balance,monthly,lnumber,completed,date_approve,status){
        document.getElementById("acct_name").innerHTML=fname+" "+lname;
        document.getElementById("loan_number").innerHTML=lnumber;
        document.getElementById("balance").innerHTML=balance;
        document.getElementById("monthly").innerHTML=monthly;
        document.getElementById("comp").innerHTML=completed;
        document.getElementById("amount").innerHTML=amount;
        document.getElementById("duration").innerHTML=duration;
        document.getElementById("approve_by").innerHTML=admin_fname+" "+admin_lname;
        document.getElementById("date_approve").innerHTML=date_approve;
        document.getElementById("status").innerHTML=status;
    }
</script>