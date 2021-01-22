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
    if($key == "registration_password"){
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

  $last_name = h($_POST["registration_last_name"]);

  $first_name = h($_POST["registration_first_name"]);

  $last_kana = h($_POST["registration_last_kana"]);

  $first_kana = h($_POST["registration_first_kana"]);

  $email = h($_POST["registration_email"]);
  // var_dump($email);

  $tel = h($_POST["registration_tel"]);

  $birth_day = $_POST["registration_birthday"];
  $birth_day = date('Y-m-d', strtotime($birth_day));
  // $birth_day = date($birth_day);
  // var_dump($birth_day);
  // $birth_day = h($_POST["registration_birthday"]);
  // $birth_day = str_replace("/","-",$birth_day);

  $sex = $_POST["registration_sex"];
  // var_dump($sex);

  $now_work = $_POST["registration_nowEmploymentStatus"];

  $now_income = $_POST["registration_nowIncome"];

  $now_prefecture = $_POST["registration_prefecture"];


  $dbh->beginTransaction();

  $sql = "INSERT INTO gs_applicant_user(id,last_name,first_name,last_kana,first_kana,email,tel,birth_day,sex,now_work,now_income,now_prefecture,create_time,update_time)VALUES(null,:last_name,:first_name,:last_kana,:first_kana,:email,:tel,:birth_day,:sex,:now_work,:now_income,:now_prefecture,sysdate(),sysdate())";

  // DB登録
  $stmt = $dbh->prepare($sql);


  $stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
  $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
  $stmt->bindValue(':last_kana', $last_kana, PDO::PARAM_STR);
  $stmt->bindValue(':first_kana', $first_kana, PDO::PARAM_STR);
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $stmt->bindValue(':tel', $tel, PDO::PARAM_STR);
  $stmt->bindValue(':birth_day', $birth_day, PDO::PARAM_STR);
  $stmt->bindValue(':sex', $sex, PDO::PARAM_STR);
  $stmt->bindValue(':now_work', $now_work, PDO::PARAM_STR);
  $stmt->bindValue(':now_income', $now_income, PDO::PARAM_STR);
  $stmt->bindValue(':now_prefecture', $now_prefecture, PDO::PARAM_STR);

  $status = $stmt->execute();

  $dbh->commit();

  if($status == false) {
    $error_sql = $stmt->errorInfo();
    exit("ErrorMessage:".$error_sql[2]);
  }

  // exit();

} else {
  $page_flag = 0;
}

function validation($_DATA){
  $_ERROR = array();

  $webRoot = $_SERVER['DOCUMENT_ROOT'];
  require_once($webRoot . '/gs_ats/src/function.php');

  $dbh = getDbh();

  $sql = "SELECT * FROM gs_applicant_user WHERE email = :email";
  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':email', h($_DATA["registration_email"]), PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  // 姓のバリデーション
  if( empty($_DATA["registration_last_name"]) ) {
      $_ERROR[] = "姓を入力してください。";
  } elseif (10 < mb_strlen($_DATA["registration_last_name"])) {
      $_ERROR[] = "姓は10文字以内で入力してください";
  }

  // 名のバリデーション
  if( empty($_DATA["registration_first_name"]) ) {
    $_ERROR[] = "名を入力してください。";
} elseif (10 < mb_strlen($_DATA["registration_first_name"])) {
    $_ERROR[] = "名は10文字以内で入力してください";
}

// セイのバリデーション
if( empty($_DATA["registration_last_kana"]) ) {
  $_ERROR[] = "セイを入力してください。";
} elseif (10 < mb_strlen($_DATA["registration_last_kana"])) {
  $_ERROR[] = "セイは10文字以内で入力してください";
}

// メイのバリデーション
if( empty($_DATA["registration_last_name"]) ) {
$_ERROR[] = "メイを入力してください。";
} elseif (10 < mb_strlen($_DATA["registration_last_name"])) {
$_ERROR[] = "メイは10文字以内で入力してください";
}

// メールアドレスのバリデーション
if( empty($_DATA["registration_email"])) {
  $_ERROR[] = "メールアドレスを入力してください。";
} elseif($_DATA["registration_email"] == $result["email"]) {
  $_ERROR[] = "このメールアドレスは登録されています。";
}

// 電話番号のバリデーション
if( empty($_DATA["registration_tel"]) ) {
  $_ERROR[] = "電話番号を入力してください。";
  } elseif (20 < mb_strlen($_DATA["registration_tel"])) {
  $_ERROR[] = "電話番号は20文字以内で入力してください";
  }

// 誕生日のバリデーション
if( empty($_DATA["registration_birthday"]) ) {
  $_ERROR[] = "誕生日を入力してください。";
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
    <link rel="stylesheet" href="../../gs_ats/assets/css/reset.css">
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
    <p><a href="../account/index.php">アカウント一覧</a></p>
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
              <label class="title-text">姓</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_last_name"]; ?></p>
            </div>
            <div class="form-group">
              <label class="title-text">名</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_first_name"]; ?></p>
            </div>
            <div class="form-group">
              <label class="title-text">セイ</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_last_kana"]; ?></p>
            </div>
            <div class="form-group">
              <label class="title-text">メイ</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_first_kana"]; ?></p>
            </div>
            <div class="form-group">
              <label class="title-text">メールアドレス</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_email"]; ?></p>
            </div>
            <div class="form-group">
              <label class="title-text">電話番号</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_tel"]; ?></p>
            </div>
            <div class="form-group">
              <label class="title-text">誕生日</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_birthday"]; ?></p>
            </div>
            <div class="form-group">
              <label class="title-text">性別</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_sex"]; ?></p>
            </div>
            <div class="form-group">
              <label class="title-text">現在の就業状況</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_nowEmploymentStatus"]; ?></p>
            </div>
            <div class="form-group">
              <label class="title-text">現在年収</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_nowIncome"]; ?></p>
            </div>
            <div class="form-group">
              <label class="title-text">居住地</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_prefecture"]; ?></p>
            </div>
            <div class="text-center">
              <input type="submit" name="btn_back" class="btn btn-primary btn-margin" value="戻る">
              <input type="submit" name="btn_submit" class="btn btn-primary btn-margin" value="完了する">
            </div>
            <input type="hidden" name="registration_last_name" value="<?= $_CLEAN["registration_last_name"] ?>">
            <input type="hidden" name="registration_first_name" value="<?= $_CLEAN["registration_first_name"] ?>">
            <input type="hidden" name="registration_last_kana" value="<?= $_CLEAN["registration_last_kana"] ?>">
            <input type="hidden" name="registration_first_kana" value="<?= $_CLEAN["registration_first_kana"] ?>">
            <input type="hidden" name="registration_email" value="<?= $_CLEAN["registration_email"] ?>">
            <input type="hidden" name="registration_tel" value="<?= $_CLEAN["registration_tel"] ?>">
            <input type="hidden" name="registration_birthday" value="<?= $_CLEAN["registration_birthday"] ?>">
            <input type="hidden" name="registration_sex" value="<?= $_CLEAN["registration_sex"] ?>">
            <input type="hidden" name="registration_nowEmploymentStatus" value="<?= $_CLEAN["registration_nowEmploymentStatus"] ?>">
            <input type="hidden" name="registration_nowIncome" value="<?= $_CLEAN["registration_nowIncome"] ?>">
            <input type="hidden" name="registration_prefecture" value="<?= $_CLEAN["registration_prefecture"] ?>">
            
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
            <h1>データの登録が完了しました</h1>
          </div>
          <div class="back-home text-center">
            <a href="/gs_ats/top/">応募者一覧に戻る</a>
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
              <h1>応募者登録</h1>
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
                <label for="registration_last_name">姓</label>
                <input type="text" id="registration_last_name" class="form-control" name="registration_last_name" value="<?php if ( !empty($_CLEAN["registration_last_name"])){ echo $_CLEAN["registration_last_name"]; } ?>">
                <label id="lastNameSupplement" class="supplement">10文字以内</label>
              </div>
              <div class="form-group">
                <label for="registration_first_name">名</label>
                <input type="text" id="registration_first_name" class="form-control" name="registration_first_name" value="<?php if ( !empty($_CLEAN["registration_first_name"])){ echo $_CLEAN["registration_first_name"]; } ?>">
                <label id="firstNameSupplement" class="supplement">10文字以内</label>
              </div>
              <div class="form-group">
                <label for="registration_last_kana">セイ</label>
                <input type="text" id="registration_last_kana" class="form-control" name="registration_last_kana" value="<?php if ( !empty($_CLEAN["registration_last_kana"])){ echo $_CLEAN["registration_last_kana"]; } ?>">
                <label id="lastKanaSupplement" class="supplement">10文字以内</label>
              </div>
              <div class="form-group">
                <label for="registration_first_kana">メイ</label>
                <input type="text" id="registration_first_kana" class="form-control" name="registration_first_kana" value="<?php if ( !empty($_CLEAN["registration_first_kana"])){ echo $_CLEAN["registration_first_kana"]; } ?>">
                <label id="firstKanaSupplement" class="supplement">10文字以内</label>
              </div>
              <div class="form-group">
                <label for="registration_email">メールアドレス</label>
                <input type="text" id="registration_email" class="form-control" name="registration_email" value="<?php if ( !empty($_CLEAN["registration_email"])){ echo $_CLEAN["registration_email"]; } ?>">
                <label id="emailSupplement" class="supplement">255文字以内</label>
              </div>
              <div class="form-group">
                <label for="registration_tel">電話番号</label>
                <input type="tel" id="registration_tel" class="form-control" name="registration_tel" value="<?php if ( !empty($_CLEAN["registration_tel"])){ echo $_CLEAN["registration_tel"]; } ?>" pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}" placeholder="090-1234-5678">
                <label id="telSupplement" class="supplement"><p>-(半角ハイフン)ありで入力してください。</p></label>
              </div>

              <div class="form-group">
                <label for="registration_birthday">誕生日</label>
                <input type="date" id="registration_birthday" class="form-control"name="registration_birthday" value="<?php if ( !empty($_CLEAN["registration_birthday"])){ echo $_CLEAN["registration_birthday"]; } ?>">
                <label id="birthdaySupplement" class="supplement"><p></p></label>
              </div>

              <div class="form-group radio">
                <p for="registration_sex">性別</p>
                <input type="radio" name="registration_sex" id="registration_sex_man" checked value="男性"><label for="registration_sex_man" class="form-control-radio">男性</label>
                <input type="radio" name="registration_sex" id="registration_sex_female" value="女性"><label for="registration_sex_female" class="form-control-radio">女性</label>
                <input type="radio" name="registration_sex" id="registration_sex_other" value="その他"><label for="registration_sex_other" class="form-control-radio">その他</label>
              </div>

              <div class="form-group radio">
              <p for="registration_nowEmployment">現在の就業状況</p>

                <?php
                $results = employment();

                foreach($results as $result){
                  employmentResult($result);
                }
                ?>
              </div>

              <div class="form-group radio">
                <p for="registration_nowIncome">現在年収</p>
                <?php
                $results = nowIncome();
                foreach($results as $result) {
                  nowIncomeResult($result);
                }
                ?>
              </div>



              <div class="form-group">
              <p for="registration_prefecture">居住地</p>
                <select name="registration_prefecture" id="registration_prefecture">
                  <?php
                  $results = prefecture();
                  var_dump($results);

                  foreach($results as $result){
                    prefectureResult($result);
                  }
                  ?>
              </select>
              </div>

              <div class="text-center">
                <input type="submit" name="btn_confirm" class="btn btn-primary btn-margin" value="確認する">
              </div>
            </form>
          </section>
            <div class="back-home text-center">
              <a href="/gs_ats/top/">応募者一覧に戻る</a>
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