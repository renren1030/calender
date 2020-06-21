<?php
require('../dbconnect.php');
session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

if(!empty($_POST)){//データが送られてきた時のみ（フォームボタンを押した時のみ）チェック
  if($_POST['name']===''){
    $error['name'] = 'blank';
  }

  if($_POST['email']===''){
    $error['email'] = 'blank';
  }

  if(strlen($_POST['password']) < 4){
    $error['password'] = 'length';
  }

  if($_POST['password']===''){
    $error['password'] = 'blank';
  }

  if(empty($error)){
    $user = $db->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=?');
    $user->execute(array($_POST["email"]));
    $record = $user->fetch();
    if($record["cnt"]>0){
      $error["email"]="duplicate";
    }
  }


  if(empty($error)){
    $_SESSION['join'] = $_POST;
    header('Location: confirm.php');
    exit();
  }
}

if ($_REQUEST["action"]==="rewrite"&&isset($_SESSION["join"])){
	$_POST = $_SESSION["join"];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="../style3.css" />
  <style type="text/css">
  body {
      color: #fff;
      background: #344a71;
      font-family: 'Roboto', sans-serif;
  }
  .error {
  color: #BB1850;
}
</style>  
  <title>ログイン</title>
</head>
<body>
<div class="signup-form">
    <form action="" method="post" autocomplete="off">
    <input type="hidden" name="action" value="submit" />
		<h2>Sign Up</h2>
		<p>It's free and only takes a minute.</p>
		<hr>
       <div class="form-group">
			  <label>Username</label>
          <input type="text" class="form-control" name="name" value="<?php print(htmlspecialchars($_POST["name"],ENT_QUOTES));?>">
          <?if ($error["name"]==="blank"):?>
					<p class="error">＊ユーザー名を入力してください</p>
					<?php endif;?>
        </div>
      <div class="form-group">
			  <label>Email Address</label>
          <input type="email" class="form-control" name="email" value=<?php print(htmlspecialchars($_POST["email"],ENT_QUOTES));?>>
          <?if ($error["email"]==="blank"):?>
					<p class="error">＊メールアドレスを入力してください</p>
          <?php endif;?>
          <?if ($error["email"]==="duplicate"):?>
					<p class="error">＊指定されたメールアドレスはすでに登録されています</p>
					<?php endif;?>
       </div>
		  <div class="form-group">
			  <label>Password</label>
          <input type="password" class="form-control" name="password" value="<?php print(htmlspecialchars($_POST["password"],ENT_QUOTES));?>">
          <?if ($error["password"]==="length"):?>
					<p class="error">＊パスワードは４文字以上で入力してください</p>
          <?php endif;?>
          <?if ($error["password"]==="blank"):?>
					<p class="error">＊パスワードを入力してください</p>
					<?php endif;?>
      </div>
		  <!-- <div class="form-group">
			  <label>Confirm Password</label>
          <input type="password" class="form-control" name="confirm_password" required="required">
       </div> -->
		  <div class="form-group">
       <button type="submit" class="btn btn-primary btn-block btn-lg">Confirm</a></button>
      </div>
		  <p class="small text-center">By clicking the Sign Up button, you agree to our <br><a href="#">Terms &amp; Conditions</a>, and <a href="#">Privacy Policy</a></p>
    </form>
	<div class="text-center">Already have an account? <a href="../login.php">Login here</a></div>
</div>
</body>
</html>