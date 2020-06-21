<?php
require('../dbconnect.php');
session_start();

if(!empty($_POST)){
$statement = $db->prepare('INSERT INTO users(name,email,password) values(?,?,?)');
$statement->execute(array(
  $_SESSION['join']['name'],
  $_SESSION['join']['email'],
  sha1($_SESSION['join']['password'])
));
  // unset($_SESSION['join']);//session削除
  header('Location: ../index.php');
  exit();
}

if(!isset($_SESSION['join'])){//入力画面に戻す
  header('Location:index.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="../style4.css" />
  <style type="text/css">
  body {
      color: #fff;
      background: #344a71;
      font-family: 'Roboto', sans-serif;
  }
  .display{
  color: #000000;
}


</style>  
  <title>Document</title>
</head>
<body>
<div class="signup-form">
    <form action="" method="post" autocomplete="off">
    <input type="hidden" name="action" value="submit" />
		<h2>Sign Up</h2>
		<p>Please confirm the following contents.</p>
		<hr>
       <div class="form-group">
			  <label>Username</label>
        <p class="display"><?php print(htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES));?></p>
        </div>
      <div class="form-group">
			  <label>Email Address</label>
        <p class="display"><?php print(htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES));?></p>
       </div>
		  <div class="form-group">
			  <label>Password</label>
         <p class="display">表示しません</p>
      </div>
		  <!-- <div class="form-group">
			  <label>Confirm Password</label>
          <input type="password" class="form-control" name="confirm_password" required="required">
       </div> -->
		  <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block btn-lg">Sign Up</button>
      </div>
		  <p class="small text-center">By clicking the Sign Up button, you agree to our <br><a href="#">Terms &amp; Conditions</a>, and <a href="#">Privacy Policy</a></p>
    </form>
    <div class="text-center">Would you like to rewrite your entries? <a href="index.php?action=rewrite">Rewite</a></div>
</div>
</body>
</html>