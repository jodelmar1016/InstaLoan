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
	<title>Reports</title>
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
                <li class="nav-item">
					<a class="nav-link" href="dashboard.php">Dashboard <span class="sr-only">(current)</span></a>
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
                <li class="nav-item active">
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
        $sql="SELECT SUM(amount) AS value_sum FROM loan WHERE status='PAID'";
        $stmt=$conn->query($sql);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $sum = $row['value_sum'];

        $sql="SELECT * FROM loan WHERE status='PAID'";
        $stmt=$conn->query($sql);
        $total=0;
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            $total=$total+($row['duration']*$row['monthly']);
        }

        $loan_interest=$total-$sum;
    ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div class="card" style="border-left: 5px solid lightblue;">
                    <div class="card-body text-right">
                        <h1 class="card-title"><b><?php echo number_format($sum) ?></b></h1>
                        <p class="card-text">Total Loan Amount</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card" style="border-left: 5px solid lightblue;">
                <div class="card-body text-right">
                    <h1 class="card-title"><b><?php echo number_format($total) ?></b></h1>
                    <p class="card-text">Compound Interest</p>
                </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card" style="border-left: 5px solid lightblue;">
                <div class="card-body text-right">
                    <h1 class="card-title"><b><?php echo number_format($loan_interest) ?></b></h1>
                    <p class="card-text">Total Loan Interest</p>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php
            if(!isset($_GET['select_year'])){
                $current_year=date("Y");
            }
            else{
                $current_year=$_GET['select_year'];
            }
            for($a=1;$a<=12;$a+=1){
                $sql="SELECT * FROM loan WHERE MONTH(date(date_approve))='$a' AND YEAR(date_approve)='$current_year'";
                $stmt=$conn->query($sql);
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                $val[]  = $stmt->rowCount();
            }

            if(!isset($_GET['select_year'])){
                $selected_year="2021";
            }
            else{
                $selected_year = $_GET["select_year"];
            }
        ?>
        <div id="columnchart_material" style="width: 100%; height: 300px; margin-top: 20px; margin-bottom: 20px;"></div>
        <form action="" method="GET" id="select_form">
            <select name="select_year" id="select_year" class="form-control" onchange="change_year()">
                <option value="2019" <?php if (isset($selected_year) && $selected_year=="2019") echo "selected";?>>2019</option>
                <option value="2020" <?php if (isset($selected_year) && $selected_year=="2020") echo "selected";?>>2020</option>
                <option value="2021" <?php if (isset($selected_year) && $selected_year=="2021") echo "selected";?>>2021</option>
            </select>
        </form>
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

        <?php
            $sql="SELECT * FROM loan WHERE status='PAID'";
            $stmt=$conn->query($sql);
            if($stmt->rowCount()==0){
                $mess="No Record";
            }
            if(isset($_GET['val']) || isset($_GET['year'])){
                $month=$_GET['val'];
                $year=$_GET['year'];
                if($month=="All" && $year=="All"){
                    $sql="SELECT * FROM loan WHERE status='PAID' ORDER BY loan_id DESC";
                }
                else if($month=="All" && $year!="All"){
                    $sql="SELECT * FROM loan WHERE status='PAID' AND YEAR(date_approve)='$year' ORDER BY loan_id DESC";
                }
                else if($month!="All" && $year=="All"){
                    $sql="SELECT * FROM loan WHERE status='PAID' AND MONTH(date(date_approve))='$month' ORDER BY loan_id DESC";
                }
                else{
                    $sql="SELECT * FROM loan WHERE status='PAID' AND MONTH(date(date_approve))='$month' AND YEAR(date_approve)='$year' ORDER BY loan_id DESC";
                }
            }
            else{
                $sql="SELECT * FROM loan WHERE status='PAID' ORDER BY loan_id DESC";
            }
            $stmt=$conn->query($sql);
        ?>

        <div class="card card-loan text-white bg-dark mx-auto" style="margin-top: 20px; margin-bottom: 50px;">
            <div class="card-header">
                <h2 class="d-inline">Completed Loans: <?php echo $stmt->rowCount(); ?></h2>
                <?php
                    if(!isset($_GET['val']) && !isset($_GET['year'])){
                        $value="All";
                        $year="All";
                    }
                    else{
                        $value = $_GET["val"];
                        $year = $_GET["year"];
                    }
                ?>
                <form action="" method="GET" id="my-form" class="float-right">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Month</label>
                                <select name="val" id="val" onchange="myFunction()" class="custom-select" id="inputGroupSelect04">
                                    <option value="All" <?php if (isset($value) && $value=="All") echo "selected";?>>All</option>
                                    <option value="1" <?php if (isset($value) && $value=="1") echo "selected";?>>January</option>
                                    <option value="2" <?php if (isset($value) && $value=="2") echo "selected";?>>February</option>
                                    <option value="3" <?php if (isset($value) && $value=="3") echo "selected";?>>March</option>
                                    <option value="4" <?php if (isset($value) && $value=="4") echo "selected";?>>April</option>
                                    <option value="5" <?php if (isset($value) && $value=="5") echo "selected";?>>May</option>
                                    <option value="6" <?php if (isset($value) && $value=="6") echo "selected";?>>June</option>
                                    <option value="7" <?php if (isset($value) && $value=="7") echo "selected";?>>July</option>
                                    <option value="8" <?php if (isset($value) && $value=="8") echo "selected";?>>August</option>
                                    <option value="9" <?php if (isset($value) && $value=="9") echo "selected";?>>September</option>
                                    <option value="10" <?php if (isset($value) && $value=="10") echo "selected";?>>October</option>
                                    <option value="11" <?php if (isset($value) && $value=="11") echo "selected";?>>November</option>
                                    <option value="12" <?php if (isset($value) && $value=="12") echo "selected";?>>December</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="">Year</label>
                                <select name="year" id="year" onchange="myFunction()" class="custom-select">
                                    <option value="All" <?php if (isset($year) && $year=="All") echo "selected";?>>All</option>
                                    <option value="2019" <?php if (isset($year) && $year=="2019") echo "selected";?>>2019</option>
                                    <option value="2020" <?php if (isset($year) && $year=="2020") echo "selected";?>>2020</option>
                                    <option value="2021" <?php if (isset($year) && $year=="2021") echo "selected";?>>2021</option>
                                </select>
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
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <?php
                                // GET FIRST NAME AND LAST NAME
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
                            <td class="text-success font-weight-bold"><?php echo $row['status']; ?></td>
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
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <hr style="height: 1px; background-color: lightblue;">
                <?php
                
                    if(isset($_GET['val']) || isset($_GET['year'])){
                        $month=$_GET['val'];
                        $year=$_GET['year'];
                        if($month=="All" && $year=="All"){
                            $sql="SELECT SUM(amount) AS value_sum FROM loan WHERE status='PAID'";
                            $stmt=$conn->query($sql);
                            $row=$stmt->fetch(PDO::FETCH_ASSOC);
                            $_sum = $row['value_sum'];

                            $sql="SELECT * FROM loan WHERE status='PAID'";
                            $stmt=$conn->query($sql);
                            $_total=0;
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                $_total=$_total+($row['duration']*$row['monthly']);
                            }

                            $_loan_interest=$_total-$_sum;
                        }
                        else if($month=="All" && $year!="All"){
                            $sql="SELECT SUM(amount) AS value_sum FROM loan WHERE status='PAID' AND YEAR(date_approve)='$year'";
                            $stmt=$conn->query($sql);
                            $row=$stmt->fetch(PDO::FETCH_ASSOC);
                            $_sum = $row['value_sum'];
                            
                            $sql="SELECT * FROM loan WHERE status='PAID' AND YEAR(date_approve)='$year'";
                            $stmt=$conn->query($sql);
                            $_total=0;
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                $_total=$_total+($row['duration']*$row['monthly']);
                            }

                            $_loan_interest=$_total-$_sum;
                        }
                        else if($month!="All" && $year=="All"){
                            $sql="SELECT SUM(amount) AS value_sum FROM loan WHERE status='PAID' AND MONTH(date_approve)='$month'";
                            $stmt=$conn->query($sql);
                            $row=$stmt->fetch(PDO::FETCH_ASSOC);
                            $_sum = $row['value_sum'];

                            $sql="SELECT * FROM loan WHERE status='PAID' AND MONTH(date_approve)='$month'";
                            $stmt=$conn->query($sql);
                            $_total=0;
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                $_total=$_total+($row['duration']*$row['monthly']);
                            }

                            $_loan_interest=$_total-$_sum;
                        }
                        else{
                            $sql="SELECT SUM(amount) AS value_sum FROM loan WHERE status='PAID' AND MONTH(date(date_approve))='$month' AND YEAR(date_approve)='$year'";
                            $stmt=$conn->query($sql);
                            $row=$stmt->fetch(PDO::FETCH_ASSOC);
                            $_sum = $row['value_sum'];
                            
                            $sql="SELECT * FROM loan WHERE status='PAID' AND MONTH(date(date_approve))='$month' AND YEAR(date_approve)='$year'";
                            $stmt=$conn->query($sql);
                            $_total=0;
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                $_total=$_total+($row['duration']*$row['monthly']);
                            }

                            $_loan_interest=$_total-$_sum;
                        }
                    }
                    else{
                        $sql="SELECT SUM(amount) AS value_sum FROM loan WHERE status='PAID'";
                        $stmt=$conn->query($sql);
                        $row=$stmt->fetch(PDO::FETCH_ASSOC);
                        $_sum = $row['value_sum'];

                        $sql="SELECT * FROM loan WHERE status='PAID'";
                        $stmt=$conn->query($sql);
                        $_total=0;
                        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                            $_total=$_total+($row['duration']*$row['monthly']);
                        }

                        $_loan_interest=$_total-$_sum;
                    }
                ?>
                <div class="row text-center">
                    <div class="col font-weight-bold">Total Loan Amount: <?php echo number_format($_sum) ?></div>
                    <div class="col font-weight-bold">Compound Interest: <?php echo number_format($_total) ?></div>
                    <div class="col font-weight-bold">Total Loan Interest: <?php echo number_format($_loan_interest) ?></div>
                </div>
                <?php if(isset($mess)){ ?>
                <div class="alert alert-success text-center" role="alert">
                    <?php echo $mess ?>
                </div>
                <?php } ?>
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

    function myFunction(){
        document.getElementById("my-form").submit();
    }

    function change_year(){
        document.getElementById("select_form").submit();
    }
</script>