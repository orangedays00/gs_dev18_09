<?php
require_once('src/function.php');

session_start();
//ログイン済みの場合
if (isset($_SESSION['name'])) {
  echo 'ようこそ' .  h($_SESSION['name']) . "さん<br>";
  echo "<a href='/logout.php'>ログアウトはこちら。</a>";
  exit;
}


$email = $_POST['email'];
$name = $_POST['name'];
$pass = $_POST['pass'];

if(!$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  echo '入力された値が不正です。';
  return false;
}

// パスワードの正規化
if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{6,100}+\z/i', $pass)) {
  $password = password_hash($pass, PASSWORD_DEFAULT);
} else {
  echo 'パスワードは半角英数字をそれぞれ1文字以上含んだ6文字以上で登録してください。';
  return false;
}

try {
  $sql="INSERT INTO `gs_user`(`id`, `email`, `name`, `pass`, `account_type`, `create_time`, `update_time`) VALUES (null, :email, :name, :pass, '1', sysdate(), sysdate())";
  $stmt = $dbh->prepare($sql);

  $stmt->bindParam(':email', h($email), PDO::PARAM_STR);
  $stmt->bindParam(':name', h($name), PDO::PARAM_STR);
  $stmt->bindParam(':pass', h($pass), PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

} catch(\Exception $e) {
  echo '登録済みのメールアドレスです。';
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>gs_ats</title>
</head>
<body>
  <h1>ようこそ、ログインしてください。</h1>
  <form  action="login.php" method="post">
    <label for="email">email</label>
    <input type="email" name="email">
    <label for="pass">password</label>
    <input type="password" name="pass">
    <button type="submit">Sign In!</button>
  </form>
  <h1>初めての方はこちら</h1>
  <form action="index.php" method="post">
    <label for="email">email</label>
    <input type="email" name="email">email
    <label for="pass">password</label>
    <input type="password" name="pass">
    <button type="submit">Sign Up!</button>
    <p>※パスワードは半角英数字をそれぞれ１文字以上含んだ、８文字以上で設定してください。</p>
  </form>
</body>
</html>




