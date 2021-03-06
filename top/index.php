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
  <p><a href="../account/index.php">アカウント一覧</a></p>
  <p><a href="../src/logout.php">ログアウト</a></p>
</header>

<article class="applicant-article">
<h1>応募者一覧</h1>
<p class="btn btn-primary"><a class="btn-primary-text" href="../registration/index.php">応募者登録</a></p>
<section>
  <?php
  $results = getApplicant();

  if(!$results) {
    p('<p class="alert alert-danger" role="alert">応募者はいません</p>');
  } else {
    p('<div class="applicant-table table"><table>');
    p('<tr class="applicant-list">' .'<th class="applicant-id">ID</th>'.'<th class="applicant-name">姓名</th>' .'<th class="applicant-kana">セイメイ</th>' . '<th class="applicant-age">年齢</th>' . '<th class="applicant-sex">性別</th>' . '</tr>');

    foreach($results as $result){
      outputApplicant($result);
    }

    p('</table></div>');
  }
  ?>
</section>
</article>

<script src="../assets/js/main.js"></script>
</body>
</html>