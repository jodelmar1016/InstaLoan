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

    //code to add account
    if(isset($_POST['add'])){
        /*
        $sql="SELECT * FROM users WHERE username=:un";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':un'=>$_POST['uname']]);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $count=$stmt->rowCount();
        */
        if($_POST['acct_type']=="Admin"){
            $sql="SELECT * FROM users WHERE acct_type='Admin'";
            $stmt=$conn->query($sql);
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
            $count=$stmt->rowCount();
            if($count<3){
                $sql="INSERT INTO users (first_name, last_name, sex, contact_number, username, password, acct_type) VALUES (:fname, :lname, :sex, :cnumber, :uname, :pword, :acct_type)";
                $stmt=$conn->prepare($sql);
                $stmt->execute([':fname'=>$_POST['fname'],
                                ':lname'=>$_POST['lname'],
                                ':sex'=>$_POST['sex'],
                                ':cnumber'=>$_POST['cnumber'],
                                ':uname'=>$_POST['uname'],
                                ':pword'=>$_POST['pword'],
                                ':acct_type'=>$_POST['acct_type']]);
                echo '<script>alert("1 record added");</script>';
            }
            else{
                $message="3 Admins Only";
            }
        }
        else{
            $sql="INSERT INTO users (first_name, last_name, sex, contact_number, username, password, acct_type) VALUES (:fname, :lname, :sex, :cnumber, :uname, :pword, :acct_type)";
            $stmt=$conn->prepare($sql);
            $stmt->execute([':fname'=>$_POST['fname'],
                            ':lname'=>$_POST['lname'],
                            ':sex'=>$_POST['sex'],
                            ':cnumber'=>$_POST['cnumber'],
                            ':uname'=>$_POST['uname'],
                            ':pword'=>$_POST['pword'],
                            ':acct_type'=>$_POST['acct_type']]);
            echo '<script>alert("1 record added");</script>';
        }
    }

    // DELETE RECORD
    if(isset($_POST['delete'])){
        $sql="DELETE FROM users WHERE user_id=:uid";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':uid'=>$_POST['uid']]);
    }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Users</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../Lending App/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="stylesheet" href="../styles.css">

	<script src="../bootstrap/jquery/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>

    <style>
        .con-tb{
            width: 100%;
        }
        @media all and (min-width: 1200px){
            .con-tb{
                width: 75%;
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
				<li class="nav-item active">
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
    

    <!--------------TABLE------------->
    <main class="container-fluid cont-tb pt-3">
        <?php if(isset($message)){ ?>
        <div class="alert alert-danger text-center" role="alert">
            <?php echo $message ?>
        </div>
        <?php } ?>
        <div class="card con-tb text-white bg-dark mx-auto">
            <div class="card-header">
                <h2 class="d-inline">List of Accounts
                <span><button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal"><i class="fas fa-user-plus"></i></button></span>
                </h2>
                <form action="" method="POST" class="float-right">
                    <div class="input-group">
                        <input type="text" name="val" value="<?php echo htmlentities(isset($_POST['val'])?$_POST['val']:''); ?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-light" type="submit" name="search">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Sex</th>
                            <th>Contact Number</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Account type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($_POST['search'])){
                                $val=$_POST['val'];
                                $sql="SELECT * FROM users
                                    WHERE CONCAT(first_name,' ',last_name) LIKE '%$val%' OR first_name LIKE '%$val%' OR last_name LIKE '%$val%'";
                            }
                            else{
                                $sql="SELECT * FROM users";
                            }
                            $stmt=$conn->query($sql);
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['first_name']; ?></td>
                            <td><?php echo $row['last_name']; ?></td>
                            <td><?php echo $row['sex']; ?></td>
                            <td><?php echo $row['contact_number']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo str_repeat("*",strlen($row['password'])); ?></td>
                            <td><?php echo $row['acct_type']; ?></td>
                            <td> <?php if($row['acct_type']=="User"){ ?>
                                <a href="../User/applyloan.php?id=<?php echo $row['user_id'] ?>" role="button" class="btn btn-secondary"><img src="../img/loan.png" alt="" width="20" height="20"></a>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" onClick="delId(<?php echo $row['user_id'] ?>)"><i class="fas fa-trash"></i></button>
                            </td> <?php } ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-----------Add----------->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <input type="text" name="fname" class="form-control" placeholder="First name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="lname" class="form-control" placeholder="Last name" required>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="sex" class="form-check-input" value="Male" required>
                            <label for="" class="form-check-label">Male</label><br>
                            <input type="radio" name="sex" class="form-check-input" value="Female" required>
                            <label for="" class="form-check-label">Female</label>
                        </div>
                        <div class="form-group">
                            <input type="text" name="cnumber" class="form-control" placeholder="Contact Number" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="uname" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="pword" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <select name="acct_type" id="" class="form-control">
                                <option value="Admin">-----SELECT-----</option>
                                <option value="Admin">Admin</option>
                                <option value="User">User</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="add">Save</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-----------Delete----------->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="uid" id="uid">
                        <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                    <button type="submit" class="btn btn-primary" name="delete">YES</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>
    function delId(x){
        document.getElementById("uid").value=x;
    }
</script>
