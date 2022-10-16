<?php
    session_start();
    include("config.php");

    if(isset($_POST['signup'])){
        $sql="SELECT * FROM users WHERE contact_number=:cn";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':cn'=>$_POST['cnumber']]);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $count=$stmt->rowCount();

        if($count>0){
            $message="Contact number already exist";
        }
        else{
            $sql="INSERT INTO users (first_name, last_name, sex, contact_number, username, password) VALUES (:fname, :lname, :sex, :cnumber, :uname, :pword)";
            $stmt=$conn->prepare($sql);
            $stmt->execute([':fname'=>ucfirst($_POST['fname']),
                            ':lname'=>ucfirst($_POST['lname']),
                            ':sex'=>$_POST['sex'],
                            ':cnumber'=>$_POST['cnumber'],
                            ':uname'=>$_POST['uname'],
                            ':pword'=>$_POST['pword']]);
            $_SESSION['user']=$_POST['uname'];
            header("location: User/userview.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Sign up</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">

	<script src="bootstrap/jquery/jquery.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>

    <style>
        .con-form{
            margin: none;
        }
        @media all and (min-width: 1200px){
            .con-form{
                margin-top: 20px;
                margin-bottom: 30px;
                border-radius: 15px;
                box-shadow: 5px 10px 18px #888888;
            }
        }
        @media all and (min-width: 768px){
            .con-form{
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
			<img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
			<img src="img/logo1.png" width="180" height="30" class="d-inline-block align-top" alt="">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="index.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">About Us</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">FAQ</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Contact</a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link btn btn-info btn-md" href="login.php">Login</a>
				</li>
			</ul>
		</div>
	</nav>

	<div class="container-fluid con-form">
        <img src="img/logo2.png" class="logo2">
        <form action="" method="POST">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="First name" name="fname" id="fname" value="<?php echo htmlentities(isset($_POST['fname'])?$_POST['fname']:''); ?>" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Last name" name="lname" id="lname" value="<?php echo htmlentities(isset($_POST['lname'])?$_POST['lname']:''); ?>" required>
            </div>
            <div class="form-group form-check">
                <input type="radio" class="form-check-input" name="sex" id="sex" value="Male" required>
                <label for="" class="form-check-label">Male</label><br>
                <input type="radio" class="form-check-input" name="sex" id="sex" value="Female" required>
                <label for="" class="form-check-label">Female</label>
            </div>
            <div class="form-group">
                <input type="tel" class="form-control" pattern="[0-9]{11}" placeholder="Contact number" name="cnumber" id="cnumber" value="<?php echo htmlentities(isset($_POST['cnumber'])?$_POST['cnumber']:''); ?>" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Username" name="uname" id="uname" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" name="pword" id="pword" required>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" onclick="showPass()">
                <label for="" class="form-check-label">Show Password</label>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-signup" name="signup">Sign up</button>
            </div>
            <?php if(isset($message)){ ?>
            <div class="alert alert-danger" role="alert"><?php echo $message; ?></div>
            <?php } ?>
        </form>
    </div>
</body>
</html>

<script>
    function showPass(){
        var x=document.getElementById('pword');
        if(x.type === "password")
            x.type = "text";
        else
            x.type = "password";
    }
</script>