<?php
$webRoot = $_SERVER['DOCUMENT_ROOT'];

require_once($webRoot . '/gs_ats/src/function.php');

$dbh = getDbh();

//セッションを使うことを宣言
session_start();

//ログインされていない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION["login"])) {
  header("Location: ../index.php");
  exit();
}

//ログインされている場合は表示用メッセージを編集
$message = $_SESSION['login']."さん";
$message = h($message);

?>

<!DOCTYPE html>
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
<h1>登録アカウント一覧</h1>

<section>
  <?php
  $results = getResult();

  if(!$results) {
    p('<p class="alert alert-danger" role="alert">登録されたアカウントはありません</p>');
  } else {
    p('<div class="table"><table>');
    p('<tr class="account-list">' .'<th class="account-id">ID</th>'.'<th class="account-name">アカウント名</th>' . '<th class="account-email">メールアドレス</th>' . '</tr>');

    foreach($results as $result){
      outputResult($result);
    }

    p('</table></div>');
  }
  ?>
</section>
</article>
</body>
</html>