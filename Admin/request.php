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
    $admin_id=$row['user_id'];

    // update notif into read
    if(isset($_GET['update'])){
        $up_id=$_GET['up_id'];
        $sql="UPDATE notification SET status='read' WHERE id='$up_id'";
        $stmt=$conn->query($sql);
    }

    // APPROVE LOAN
    if(isset($_POST['btnApprove'])){
        $req_id=$_POST['req_id'];
        $sql="SELECT * FROM request WHERE request_id=$req_id";
        $stmt=$conn->query($sql);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        //$count=$stmt->rowCount();
        $ctrl_number=rand(10000000,99999999);
        $bal=($row['duration']*$row['monthly']);

        $sql="INSERT INTO loan (user_id, amount, duration, monthly, balance, loan_number, approve_by) VALUES (:uid, :amount, :duration, :monthly, :balance, :loan_number, :approve_by)";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':uid'=>$row['user_id'],
                        ':amount'=>$row['amount'],
                        ':duration'=>$row['duration'],
                        ':monthly'=>$row['monthly'],
                        ':balance'=>$bal,
                        ':loan_number'=>$ctrl_number,
                        ':approve_by'=>$admin_id]);
        
        $sql="DELETE FROM request WHERE request_id=$req_id";
        $stmt=$conn->query($sql);

        $a=$row['user_id'];
        $sql="INSERT INTO notification (title, message, receiver, user_id) VALUES ('Loan Aplication', 'Your loan application has been approved.','User','$a')";
        $stmt=$conn->query($sql);

        $_GET['u_id']="";
        header("location: request.php");
    }

    // REJECT LOAN
    if(isset($_POST['btnReject'])){
        $req_id=$_POST['delId'];
        $sql="SELECT * FROM request WHERE request_id=$req_id";
        $stmt=$conn->query($sql);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $b=$row['user_id'];

        $mess="Your application has been rejected./Reason:/".$_POST['reason'].'/'.$_POST['reason2'];
        $sql="INSERT INTO notification (title, message, receiver, user_id) VALUES ('Loan Aplication', '$mess','User','$b')";
        $stmt=$conn->query($sql);

        $req_id=$_POST['delId'];
        $sql="DELETE FROM request WHERE request_id='$req_id'";
        $stmt=$conn->query($sql);
    }

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Request</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
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
					<a class="nav-link" href="adminview.php">Accounts</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="request.php">Request <span class="sr-only">(current)</span></a>
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

    <!--------------TABLE------------->
    <main class="container pt-3">
        <div class="card card-req text-white bg-dark mx-auto">
            <div class="card-header">
                <?php if(isset($_GET['refresh'])){ $_GET['u_id']=""; } ?>
                <h2>Request Table
                    <form action="" method="GEt" class="d-inline">
                        <span><button class="btn btn-secondary" name="refresh">Refresh</button></span>
                    </form>
                </h2>
            </div>
            <div class="card-body">
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Amount</th>
                            <th>Duration</th>
                            <th>Monthly</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($_GET['u_id']) && $_GET['u_id']!=""){
                                $t=$_GET['u_id'];
                                $sql="SELECT * FROM request WHERE user_id='$t'";
                            }
                            else{
                                $sql="SELECT * FROM request";
                            }
                            $stmt=$conn->query($sql);
                            if($stmt->rowCount()==0)
                                $message="No Record";
                            while($temp=$stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <?php 
                                $id=$temp['user_id'];
                                $sql="SELECT * FROM users WHERE user_id=$id";
                                $stmt2=$conn->query($sql);
                                $row=$stmt2->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <td><?php echo $row['first_name']; ?></td>
                            <td><?php echo $row['last_name']; ?></td>
                            <td><?php echo number_format($temp['amount']); ?></td>
                            <td><?php echo $temp['duration']; ?></td>
                            <td><?php echo number_format($temp['monthly']); ?></td>
                            <td>
                                <button type="button" class="btn btn-primary" name="approve" onclick="getId(<?php echo $temp['request_id']; ?>)" data-toggle="modal" data-target="#approveModal">Approve</button>
                                <button type="button" class="btn btn-danger" name="reject" onclick="delId(<?php echo $temp['request_id']; ?>)" data-toggle="modal" data-target="#rejectModal">Reject</button>
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

    <!-----------APPROVE----------->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Approve Loan Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="req_id" id="req_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="btnApprove">YES</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-----------Reject----------->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reject Loan Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="delId" id="delId">
                        <div class="form-group">
                            <select name="reason" id="" class="form-control">
                                <option value="" readonly>----------SELECT----------</option>
                                <option value="Poor credit history" >Poor credit history</option>
                                <option value="Not employed" >Not employed</option>
                                <option value="Don’t have regular income" >Don’t have regular income</option>
                                <option value="Inaccurate Details in Application" >Inaccurate Details in Application</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea name="reason2" id="" cols="30" rows="4" class="form-control"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="btnReject">YES</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>
    function getId(x){
        document.getElementById('req_id').value=x;
    }
    function delId(x){
        document.getElementById('delId').value=x;
    }
</script>