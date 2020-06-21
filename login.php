<?php
session_start();
require('dbconnect.php');
try {
  header('Expires:-1');
  header('Cache-Control:');
  header('Pragma:');

  if ($_COOKIE['email'] !== '') {
    $email = $_COOKIE['email'];
  }


  if (!empty($_POST)) {
    $email = $_POST['email'];
    if ($_POST["email"] !== '' && $_POST["password"] !== '') {
      $login = $db->prepare('SELECT * FROM users WHERE email=? AND password=?');
      $login->execute(array(
        $_POST["email"],
        sha1($_POST["password"])
      ));
      $user = $login->fetch();

      if ($user) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['time'] = time();

        if ($_POST['save'] === 'on') {
          setcookie('email', $_POST['email'], time() + 60 * 60 * 24 * 14);
        }
        header('Location:index.php');
        exit();
      } else {
        $error['login'] = 'failed';
      }
    } else {
      $error['login'] = 'blank';
    }
  }

}catch(Exception $e){
  echo $e->getMessage();
  echo $e->getTraceAsString();
  die();
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
  <link rel="stylesheet" href="style2.css" />
  <style type="text/css">
  .error {
  color: #BB1850;
}
</style>  
  <title>ログイン</title>
</head>
<body>
  <div class="container">
   <div class="card card-container">
   <img id="profile-img" class="profile-img img-circle" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png">
   <p id="profile-name" class="profile-name-card"></p>
   <form action="" class="form-signin mb10" method="post">
                <input type="text" name="email" class="form-control mb10" placeholder="Email" value="<?php print(htmlspecialchars($email,ENT_QUOTES)); ?>">
                <input type="password" name="password" class="form-control" placeholder="Password" value="<?php print(htmlspecialchars($_POST['password'],ENT_QUOTES)); ?>">
                <?php if($error["login"]==="blank"):?>
                <p class="error">＊メールアドレスとパスワードをご記入ください
                <?php endif;?>
                <?php if($error["login"]==="failed"):?>
                <p class="error">＊ログインに失敗しました。正しくご記入ください。
                <?php endif;?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me" name="save" value='on'> Remember me
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
            </form>
  <a href="#" class="forgot-password">Forgot the password?</a><br>
  <a href="join/index.php" class="forgot-password">Sign Up</a>                
   </div>
  </div>
</body>
</html>