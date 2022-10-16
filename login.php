<?php
    session_start();
    include("config.php");

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $sql="SELECT * FROM users WHERE username=:un AND password=:pw";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':un'=>$_POST['uname'], ':pw'=>$_POST['pword']]);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $count=$stmt->rowCount();

        if($count==1){
            $_SESSION['user']=$_POST['uname'];
            if($row['acct_type']=="User"){
                header("location: User/userview.php");
            }
            else{
                header("location: Admin/dashboard.php");
            }
        }
        else{
            $error="Invalid username and/or password";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">

	<script src="bootstrap/jquery/jquery.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
    
    <style>
        .form-container{
            margin: none;
        }
        @media all and (min-width: 768px){
            .form-container{
                width: 70%;
                margin-top: 20px;
                margin-bottom: 30px;
                border-radius: 15px;
                box-shadow: 5px 10px 18px #888888;
            }
            .logo2{
                height: 65px;
                width: 400px;
                margin-top: 15px;
            }
        }
        @media all and (min-width: 1200px){
            .form-container{
                width: 50%;
                margin-top: 20px;
                margin-bottom: 30px;
                border-radius: 15px;
                box-shadow: 5px 10px 18px #888888;
            }
            .logo2{
                height: 90px;
                width: 500px;
                margin-top: 15px;
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

	<div class="container-fluid form-container">
        <img src="img/logo2.png" class="logo2">
        <img src="img/user.png" alt="" width="200" height="200" style="margin-top: 15px;">
        <h3>Welcome Back!</h3>
        <form action="" method="POST">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Username" name="uname" id="uname">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" name="pword" id="pword">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" onclick="showPass()">
                <label for="" class="form-check-label">Show Password</label>
            </div>
            <button type="submit" class="btn btn-primary btn-login">Login</button>
        </form>
        <p>
            <a href="signup.php" class="l-c-f">Create an account</a><br>
            <a href="#" class="l-c-f">Forgot Password?</a>
        </p>
        <?php if(isset($error)){ ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error ?>
        </div>
        <?php } ?>
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
