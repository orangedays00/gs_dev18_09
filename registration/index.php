<?php
$webRoot = $_SERVER['DOCUMENT_ROOT'];

require_once($webRoot . '/gs_ats/src/function.php');

$dbh = getDbh();

// TIMEゾーンを東京に変更
date_default_timezone_set('Asia/Tokyo');

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

// 変数の初期化
$page_flag = 0;
$_CLEAN = array();
$_ERROR = array();

// サニタイズ
if ( !empty($_POST) ) {
  $_ERROR = validation($_POST);
  // var_dump($_POST);
  foreach( $_POST as $key => $value) {
    if($key == "post_password"){
      $once = h( $value, ENT_QUOTES);
      $_CLEAN[$key] = hash("sha256",$once);
    }else{
      $_CLEAN[$key] = h( $value, ENT_QUOTES);
      // var_dump($key);
    }
  }
  // var_dump($_CLEAN);
}

if (!empty($_CLEAN["btn_confirm"])){

  if( empty($_ERROR) ) {
    $page_flag = 1;
    session_start();

    // 二重送信防止用トークンの発行
    $token = uniqid('', true);

    // トークンをセッション変数にセット
    $_SESSION['token'] = $token;
  }
} else if ( !empty($_CLEAN["btn_submit"])) {
  $page_flag = 2;
  session_start();

  // POSTされたトークンを取得
  $token = isset($_CLEAN['token']) ? $_CLEAN['token'] : "" ;

  // セッション変数のトークンを取得
  $session_token = isset($_SESSION['token']) ? $_SESSION['token'] : "";

  // セッション変数のトークンを削除
  unset($_SESSION['token']);


  // ここからデータベース登録

  // メールアドレス
  $email = $_POST["post_email"];
  // $title = h($title);

  // 名前
  $name = $_POST["post_name"];
  // $name = h($name);

  // パスワード
  $password = $_POST["post_password"];
  // $pass = h($password);
  // $pass = hash("sha256",$pass);


  $dbh->beginTransaction();

  $sql = "INSERT INTO gs_user(id,email,name,pass,account_type,create_time,update_time)VALUES(null,:email,:name,:pass,'1',sysdate(), sysdate())";

  // DB登録
  $stmt = $dbh->prepare($sql);


  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':pass', $password, PDO::PARAM_STR);

  $status = $stmt->execute();

  $dbh->commit();

  if($status == false) {
    $error_sql = $stmt->errorInfo();
    exit("ErrorMessage:".$error_sql[2]);
  }

} else {
  $page_flag = 0;
}

function validation($_DATA){
  $_ERROR = array();

  $webRoot = $_SERVER['DOCUMENT_ROOT'];
  require_once($webRoot . '/gs_ats/src/function.php');

  $dbh = getDbh();

  $sql = "SELECT * FROM gs_user WHERE email = :email";
  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':email', h($_DATA["post_email"]), PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  // 名前のバリデーション
  if( empty($_DATA["post_name"]) ) {
      $_ERROR[] = "名前を入力してください。";
  } elseif (20 < mb_strlen($_DATA["post_name"])) {
      $_ERROR[] = "名前は20文字以内で入力してください";
  }

  // メールアドレスのバリデーション
  if( empty($_DATA["post_email"])) {
      $_ERROR[] = "メールアドレスを入力してください。";
  } elseif($_DATA["post_email"] == $result["email"]) {
      $_ERROR[] = "このメールアドレスは登録されています。";
  }

  // パスワードのバリデーション
  if( empty($_DATA["post_password"])) {
      $_ERROR[] = "パスワードを入力してください。";
  } elseif(!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{6,100}+\z/i', $_DATA["post_password"])) {
      $_ERROR[] = "パスワードは半角英数字をそれぞれ1文字以上含んだ6文字以上で登録してください。";
  }

  return $_ERROR;
}

$title = "新規アカウント登録";

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php print $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../gs_ats/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../gs_ats/assets/css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="../../gs_ats/assets/js/autosize.min.js"></script>
    <script>
    $(function(){
      autosize($('textarea'));
    });
    </script>
  </head>
<body>
  <header class="left-nav">
    <div class="account-message"><?php echo $message;?><br>ようこそ</div>
    <p><a href="../top/index.php">TOP</a></p>
    <p><a href="../registration/index.php">アカウント作成</a></p>
    <p><a href="../src/logout.php">ログアウト</a></p>
  </header>


  <?php if( $page_flag === 1 ): ?>

  <article class="registration-article">
  <div class="container-fluid">
    <section class="container">
      <div class="row">
        <main class="col-md-9">
          <div class="new-form">
            <h1>確認画面</h1>
            <div></div>
          </div>
          <form method="POST" action="">
            <div class="form-group">
              <label>名前</label>
              <p class="form-control-plaintext"><?= $_CLEAN["post_name"]; ?></p>
            </div>
            <div class="form-group">
              <label>メールアドレス</label>
              <p class="form-control-plaintext"><?= $_CLEAN["post_email"]; ?></p>
            </div>
            <div class="form-group">
              <label>パスワード</label>
              <p class="form-control-plaintext">******</p>
            </div>
            <div class="text-center">
              <input type="submit" name="btn_back" class="btn btn-primary btn-margin" value="戻る">
              <input type="submit" name="btn_submit" class="btn btn-primary btn-margin" value="完了する">
            </div>
            <input type="hidden" name="post_name" value="<?= $_CLEAN["post_name"] ?>">
            <input type="hidden" name="post_email" value="<?= $_CLEAN["post_email"] ?>">
            <input type="hidden" name="post_password" value="<?= $_CLEAN["post_password"] ?>">
          </form>
        </main>
      </div>
    </section>
  </div>
  </article>
  <?php elseif( $page_flag === 2 ): ?>
  <article class="registration-article">
  <div class="container-fluid">
    <section class="container">
      <div class="row">
        <main class="col-md-9">
          <div class="new-form">
            <h1>アカウントの発行が完了しました</h1>
          </div>
          <div class="back-home text-center">
            <a href="/gs_ats/top/">TOPに戻る</a>
          </div>
        </main>
      </div>
    </section>
  </div>
  </article>

  <?php else: ?>
  <article class="registration-article">
    <div class="container-fluid">
      <section class="container">
        <div class="row">
          <main class="col-md-9">
            <div class="new-form">
              <h1>新規アカウント発行</h1>
              <?php if ( !empty($_ERROR)): ?>
                <div class="error-area alert alert-danger" role="alert">
                <div class="error-heading">入力内容にエラーがあります。</div>
                <ul class="error-list">
                <?php foreach( $_ERROR as $value): ?>
                  <li><?= $value; ?></li>
                <?php endforeach; ?>
                </ul>
                </div>
              <?php endif; ?>
              <div>※全て入力してください</div>
            </div>
            <section id="inputForm">
            <form method="POST" action="">
              <div class="form-group">
                <label for="post_name">名前</label>
                <input type="text" id="post_name" class="form-control" name="post_name" value="<?php if ( !empty($_CLEAN["post_name"])){ echo $_CLEAN["post_name"]; } ?>">
                <label id="nameSupplement" class="supplement">10文字以内</label>
              </div>
              <div class="form-group">
                <label for="post_email">メールアドレス</label>
                <input type="text" id="post_email" class="form-control" name="post_email" value="<?php if ( !empty($_CLEAN["post_email"])){ echo $_CLEAN["post_email"]; } ?>">
                <label id="titleSupplement" class="supplement">255文字以内</label>
              </div>
              <div class="form-group">
                <label for="post_password">パスワード</label>
                <input type="password" id="post_password" class="form-control"name="post_password" value="" minlength="6">
                <label id="passLabel" class="supplement"><p>半角英数字6文字以上（英字・数字をそれぞれ1文字以上含んでください。）</p></label>
              </div>
              <div class="text-center">
                <input type="submit" name="btn_confirm" class="btn btn-primary" value="確認する">
              </div>
            </form>
            </section>
            <div class="back-home text-center">
              <a href="/gs_ats/top/">TOPに戻る</a>
            </div>
          </main>
        </div>
      </section>
    </div>
  <article class="registration-article">
  <?php endif; ?>
  <footer id="footer">
  </footer>
</body>
</html>