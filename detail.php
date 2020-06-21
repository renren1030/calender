<?php
require('dbconnect.php');
session_start();

try {
  if (!empty($_POST)) {
    if ($_POST['message'] !== '') {
      $content = $db->prepare('INSERT INTO posts(user_id,content) values(?,?)'); //ここがデータベースに反映されません
      $content->execute(array(
        $_SESSION['id'],
        $_POST['message']
      ));
      header('Location: index.php');
      exit();
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
  <title>message</title>
</head>
<body>
<form action="" method="post"> 
      <textarea name='message' cols="50" rows="5"></textarea>
      <input type="hidden" name="reply_post_id" value="" />
      <div>
        <p>
          <input type="submit" value="投稿する" />
        </p>
      </div>
</form>
</body>
</html>