<?php
$webRoot = $_SERVER['DOCUMENT_ROOT'];

require_once($webRoot . '/gs_ats/src/function.php');

$detail = getApplicantDetail();

$now = date("Ymd");
$birthday = str_replace("-", "", $detail["birth_day"]);//ハイフンを除去しています。
$age = floor(($now-$birthday)/10000).'歳';

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
  <link rel="stylesheet" href="../../../assets/css/reset.css">
  <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../../assets/css/styles.css">
  <title>gs_ats</title>
</head>
<body>
<header class="left-nav">
  <div class="account-message"><?php echo $message;?><br>ようこそ</div>
  <p><a href="../../../top/index.php">TOP</a></p>
  <p><a href="../../../account/index.php">アカウント一覧</a></p>
  <p><a href="../../../src/logout.php">ログアウト</a></p>
</header>

<article class="applicant-article">
  <h1>応募者詳細</h1>

  <section class="contents">
    <table class="table-form applicant-detail">
      <tbody>
        <tr>
            <th class="first-child applicant-detail">名前（フリガナ）</th>
            <td class="first-child applicant-detail"><?= $detail["last_name"]." " . $detail["first_name"] ."（" . $detail["last_kana"]." " . $detail["first_kana"] . "）"?></td>
        </tr>
        <tr>
            <th class="applicant-detail">Email</th>
            <td class="applicant-detail"><?= $detail["email"]; ?></td>
        </tr>
        <tr>
            <th class="applicant-detail">電話番号</th>
            <td class="applicant-detail"><?= $detail["tel"]; ?></td>
        </tr>
        <tr>
            <th class="applicant-detail">生年月日（年齢）</th>
            <td class="applicant-detail"><?= $detail["birth_day"] . "（".$age."）" ?></td>
        </tr>
        <tr>
            <th class="applicant-detail">性別</th>
            <td class="applicant-detail"><?= $detail["sex"]; ?></td>
        </tr>
        <tr>
            <th class="applicant-detail">現在の雇用形態</th>
            <td class="applicant-detail"><?= $detail["now_work"]; ?></td>
        </tr>
        <tr>
            <th class="applicant-detail">現在の年収</th>
            <td class="applicant-detail"><?= $detail["now_income"]; ?></td>
        </tr>
        <tr>
            <th class="applicant-detail">現在の居住地</th>
            <td class="applicant-detail"><?= $detail["now_prefecture"]; ?></td>
        </tr>
      </tbody>
    </table>
    <div class="back-home text-center">
          <a href="/gs_ats/top/">応募者一覧に戻る</a>
    </div>
  </section>
</article>

<script src="../../../assets/js/main.js"></script>
</body>
</html>