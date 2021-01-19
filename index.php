<?php
$webRoot = $_SERVER['DOCUMENT_ROOT'];

require_once($webRoot . '/gs_ats/src/function.php');

$dbh = getDbh();

//セッションを使うことを宣言
session_start();

$email = $_POST['email'];
// $name = $_POST['name'];
$pass = $_POST['pass'];
$pass = hash("sha256",$pass);

//ログイン状態の場合ログイン後のページにリダイレクト
if (isset($_SESSION["login"])) {
  session_regenerate_id(TRUE);
  header("Location: top/index.php");
  exit();
}

//postされて来なかったとき
if (count($_POST) === 0) {
  $message = "";
} else { //postされて来た場合
  //ユーザー名またはパスワードが送信されて来なかった場合
  if(empty($email) || empty($pass)) {
    $message = "メールアドレスまたパスワードが未入力です。";
  } else { //ユーザー名とパスワードが送信されて来た場合
    //post送信されてきたユーザー名がデータベースにあるか検索
    try {
    $sql="SELECT * FROM gs_user WHERE email = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':email', h($email), PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // var_dump($result);
    }
    catch (PDOExeption $e) {
      exit('データベースエラー');
    }

    //検索したユーザー名に対してパスワードが正しいかを検証
    //正しくないとき
    if ($email != $result['email']){
      $message="メールアドレスが違います。";
    } elseif ($pass != $result['pass']) {
      $message="パスワードが違います。";
      // var_dump($pass);
      // var_dump($result['pass']);
    } else {
      session_regenerate_id(TRUE); //セッションidを再発行
      $_SESSION["login"] = $result['name']; //セッションにログイン情報を登録
      $_SESSION["authority"] = $result['account_type'];
      header("Location: top/index.php"); //ログイン後のページにリダイレクト
      exit();
    }
  }
}


$message = h($message);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/styles.css">
  <title>gs_ats ログイン画面</title>
</head>
<body class="login">
  <div class="login-main">
    <h1 class="login-h1">G’s採用管理ツール</h1>
    <div class="login-form">
      <div class="message alert alert-danger" role="alert" id="message"><?php echo $message;?></div>
      <form action="index.php" method="post">
        <div class="login-form-parts">
          <div class="login-form-email"><label for="email">メールアドレス：</label><input name="email" id="email" type="text"></div>
          <div class="login-form-pass"><label for="pass">パスワード：</label><input name="pass" id="pass" type="password"></div>
          <div class="login-form-submit"><input name="ログイン" type="submit" value="ログイン" class="login-btn"></div>
        </div>
      </form>
    </div>
  </div>
  <!-- <script>
    window.onload = function() {

    const ref = document.referrer;
    const result = ref.match(/logout/);
    const message = document.getElementById('message');

    if(result) {
      message.textContent = 'ログアウトしました';
    }
};
  </script> -->
</body>
</html>