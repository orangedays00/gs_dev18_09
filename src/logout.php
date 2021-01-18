<?php
$webRoot = $_SERVER['DOCUMENT_ROOT'];

require_once($webRoot . '/gs_ats/src/function.php');
//セッションを使うことを宣言
session_start();

//ログインされていない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION["login"])) {
  header("Location: ../index.php");
  exit();
}

//セッション変数をクリア
$_SESSION = array();

//クッキーに登録されているセッションidの情報を削除
if (ini_get("session.use_cookies")) {
  setcookie(session_name(), '', time() - 42000, '/');
}

//セッションを破棄
session_destroy();
?>

<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <title>gs_ats</title>
</head>
<body>
<header class="left-nav">
  <div class="account-message"><?php echo $message;?><br>ようこそ</div>
  <p><a href="../top/index.php">TOP</a></p>
  <p><a href="../registration/index.php">アカウント作成</a></p>
  <p><a href="../src/logout.php">ログアウト</a></p>
</header>
<article class="login-article">
<h1>ログアウトページ</h1>
<div class="message">ログアウトしました</div>
<a href="/gs_ats/">ログインページへ</a>
</article>
</body>
</html>