<?php
require('dbconnect.php');
session_start();
date_default_timezone_set('Asia/Tokyo');//タイムゾーンを日本に設定

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
    $_SESSION['time'] = time();

    $users = $db->prepare('SELECT * FROM users WHERE id=?');
    $users->execute(array($_SESSION['id']));
    $user = $users->fetch();
}else{
    header('Location:login.php');
    exit();
}




if (isset($_GET['ym'])) {//URLパラメーター入力された時はgetで取得
    $ym = $_GET['ym'];
} else {
    $ym = date('Y-m');//UNIX TIMESTAMPを日付に変更
}

$timestamp = strtotime($ym . '-01');//第一引数に取得したい日時を指定(UNIX TIMESTAMP)
if ($timestamp === false) {//空の場合は今日の年月
    $ym = date('Y-m');
    $timestamp = strtotime($ym . '-01');
}



$today = date('Y-m-j');

$html_title = date('Y年n月', $timestamp);

$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)-1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)+1, 1, date('Y', $timestamp)));

$day_count = date('t', $timestamp);//指定した月の日数を取得

$youbi = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));//曜日番号（0[日曜]から6[土曜]の値）

// カレンダー作成の準備
$weeks = [];
$week = '';

// 第１週目：空のセルを追加
// 例）１日が水曜日だった場合、日曜日から火曜日の３つ分の空セルを追加する
$week .= str_repeat('<td></td>', $youbi);

for ( $day = 1; $day <= $day_count; $day++, $youbi++) {

    $date = $ym . '-' . $day;

    if ($today == $date) {
        // 今日の日付の場合は、class="today"をつける
        $week .= '<td class="today"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-example">' . $day;
    } else {
        $week .= '<td><button class="btn btn-primary" data-toggle="modal" data-target="#modal-example">' . $day;
    }
    $week .= '</button></td>';

    // 週終わり、または、月終わりの場合
    if ($youbi % 7 == 6 || $day == $day_count) {

        if ($day == $day_count) {
            // 月の最終日の場合、空セルを追加
            // 例）最終日が木曜日の場合、金・土曜日の空セルを追加
            $week .= str_repeat('<td></td>', 6 - ($youbi % 7));
        }

        // weeks配列にtrと$weekを追加する
        $weeks[] = '<tr>' . $week . '</tr>';

        // weekをリセット
        $week = '';
	}
}



?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>PHPカレンダー</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <style type="text/css">
        .table tr td:hover {
            outline: 2px black solid;
            outline-offset: -2px;
            }

        td a {
            color:  black;
        }

        td a:hover {
            text-decoration: none;
            color: #FFFF00;
        }

        td:nth-of-type(1) a {
            color:  red;
            }

        td:nth-of-type(1) a:hover {
            color:  #FFFF00;
            }
        td:nth-of-type(7) a {
            color:  blue;
            }

        td:nth-of-type(7) a:hover {
            color:  #FFFF00;
            }

        .logout {
                font-size: 12px;
                
                padding: 10px;
            }

        .btn-primary{
            background: #99CCFF;
        }

        .month {
            font-size: 24px;
            display:inline-block;
            margin: 10px 0;
        }
    
        td:nth-of-type(n+2):nth-of-type(-n+6) .btn-primary{
            color: black;
        }

        td:nth-of-type(n+2):nth-of-type(-n+6) .btn-primary:hover{
            color: white;
        }

        td:nth-of-type(1) .btn-primary{
            color: red;
        }

        td:nth-of-type(1) .btn-primary:hover{
            color:  white;
        }

        td:nth-of-type(7) .btn-primary{
            color: blue;
        }
        
        td:nth-of-type(7) .btn-primary:hover{
            color:  white;
        }

        .profile-img {
            width: 40px;
            height: auto;
            position: relative;
            float: right;
            top: 10px;
        }

        .profile-img:hover{
            opacity: 0.5 ;
        }

        .modal-title {
            text-align: center;
        }
    </style>  
</head>
<body>
    <div class="container">
        <span class='month'><a href="?ym=<?php echo $prev; ?>">&lt;</a><?php echo $html_title; ?><a href="?ym=<?php echo $next; ?>">&gt;</a></span>
        <a href="#" class="profile" data-toggle="modal" data-target="#modal-example2"><img id="profile-img" class="profile-img img-circle" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png"></a>
        <table class="table table-bordered">
            <tr>
                <th>日</th>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th>土</th>
            </tr>
            <?php
                foreach ($weeks as $week) {
                    echo $week;
                }
            ?>
        </table>
        <?php $posts = $db -> query('SELECT * FROM posts');
        $post = $posts -> fetch();?>
        <?php foreach($posts as $post): ?>
        <?php print ($post['content']);?>
        <?php endforeach; ?>
   
    </div>
    
 <!-- -------------------------------------------------------------------------------- -->
 <!-- <?php
 require('dbconnect.php');
 session_start();
 try {
    if (!empty($_POST)) {
      if ($_POST['message'] !== '') {
        $content = $db->prepare('INSERT INTO posts(user_id,title,content) values(?,?,?)');
        $content->execute(array(
          $_SESSION['id'],
          $_POST['title'],
          $_POST['message']
        ));
       
      }
    }
  }catch(Exception $e){
    echo $e->getMessage();
    echo $e->getTraceAsString();
    die();
  }
 ?> -->
  <!-- 2.モーダルの配置 -->
  <div class="modal" id="modal-example" tabindex="-1">
    <a href="#modalFade" id="modalTrigger" role="button" class="btn" data-toggle="modal" style="display: none;"></a>
    <div class="modal-dialog">
 
      <!-- 3.モーダルのコンテンツ -->
        <div class="modal-content">
 
        <!-- 4.モーダルのヘッダ -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="modal-label">登録</h4>
        </div>
 
        <!-- 5.モーダルのボディ -->
        <form action="" method="post"> 
            <div class="modal-body">
             <p>タイトル</p>
                <input type='text' id='modalName' name='title'>
             <p>予定の内容<br>
                <textarea name='message'cols='70' rows='5' ></textarea>
             </p>
            </div>
    
            <!-- 6.モーダルのフッタ -->
            <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
             <button id='set' type="submit" class="btn btn-primary">保存</button>
            </div>
        </form>
      </div>
    </div>
</div>

<?php
 require('dbconnect.php');
 session_start();
 try {
    if (!empty($_POST)) {
      if ($_POST['profile'] !== '') {
        $content = $db->prepare('INSERT INTO users (profile) values(?)');
        $content->execute(array(
          $_POST['profile']
        ));
       
      }
    }
  }catch(Exception $e){
    echo $e->getMessage();
    echo $e->getTraceAsString();
    die();
  }
 ?> 

 <!-- 2.モーダルの配置 -->
 <div class="modal" id="modal-example2" tabindex="-1">
    <a href="#modalFade" id="modalTrigger" role="button" class="btn" data-toggle="modal" style="display: none;"></a>
    <div class="modal-dialog">
 
      <!-- 3.モーダルのコンテンツ -->
        <div class="modal-content">
 
        <!-- 4.モーダルのヘッダ -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="modal-label">プロフィール</h4>
        </div>
 
        <!-- 5.モーダルのボディ -->
        <form action="" method="post"> 
        <div class="modal-body">
          <p>ユーザー名<br><?php print(htmlspecialchars($_SESSION['name'],ENT_QUOTES));?></p>
          <p>メールアドレス<br><?php print(htmlspecialchars($_SESSION['email'],ENT_QUOTES));?></p>
          <p>自己紹介<br>
            <textarea name='profile'cols='70' rows='5' placeholder="自由に書いてみましょう！" value="<?php print(htmlspecialchars($_POST['profile'],ENT_QUOTES));?>"></textarea>
          </p>
          <p><a href="login.php" id="delete">ログアウト</p>
          <script>
            $('#delete').click(function(){
                if(!confirm('本当にログアウトしますか？')){
                /* キャンセルの時の処理 */
                 return false;
            }else{
                /* OKの時の処理 */
                session_destroy();
                location.href = 'login.php';
                }
            });
          </script>
        </div>
 
        <!-- 6.モーダルのフッタ -->
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
          <button id='set' type="submit" class="btn btn-primary" value="<?php $_POST['profile']?>">保存</button>
        </div>
        </form>
      </div>
    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>