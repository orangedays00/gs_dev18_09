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

  // メールアドレス
  $email = $_POST["registration_email"];
  // $title = h($title);

  // 名前
  $name = $_POST["registration_last_name"];
  // $name = h($name);

  // パスワード
  $password = $_POST["registration_password"];
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
              <label>姓</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_last_name"]; ?></p>
            </div>
            <label>名</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_first_name"]; ?></p>
            </div>
            <div class="form-group">
              <label>セイ</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_last_kana"]; ?></p>
            </div>
            <label>メイ</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_first_kana"]; ?></p>
            </div>
            <div class="form-group">
              <label>メールアドレス</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_email"]; ?></p>
            </div>
            <div class="form-group">
              <label>電話番号</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_tel"]; ?></p>
            </div>
            <div class="form-group">
              <label>誕生日</label>
              <p class="form-control-plaintext"><?= $_CLEAN["registration_birthday"]; ?></p>
            </div>
            <div class="form-group">
              <label>性別</label>
              <p class="form-control-plaintext"><?= $_CLEAN["sex"]; ?></p>
            </div>
            <div class="form-group">
              <label>現在の就業状況</label>
              <p class="form-control-plaintext"><?= $_CLEAN["nowEmploymentStatus"]; ?></p>
            </div>
            <div class="form-group">
              <label>現在年収</label>
              <p class="form-control-plaintext"><?= $_CLEAN["nowIncome"]; ?></p>
            </div>
            <div class="form-group">
              <label>居住地</label>
              <p class="form-control-plaintext"><?= $_CLEAN["prefecture"]; ?></p>
            </div>
            <div class="text-center">
              <input type="submit" name="btn_back" class="btn btn-primary btn-margin" value="戻る">
              <input type="submit" name="btn_submit" class="btn btn-primary btn-margin" value="完了する">
            </div>
            <input type="hidden" name="registration_last_name" value="<?= $_CLEAN["registration_last_name"] ?>">
            <input type="hidden" name="registration_email" value="<?= $_CLEAN["registration_email"] ?>">
            <input type="hidden" name="registration_password" value="<?= $_CLEAN["registration_password"] ?>">
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
                <input type="radio" name="sex" id="registration_sex_man" checked value="男性"><label for="registration_sex_man" class="form-control-radio">男性</label>
                <input type="radio" name="sex" id="registration_sex_female" value="女性"><label for="registration_sex_female" class="form-control-radio">女性</label>
                <input type="radio" name="sex" id="registration_sex_other" value="その他"><label for="registration_sex_other" class="form-control-radio">その他</label>
              </div>

              <div class="form-group radio">
              <p for="registration_nowEmployment">現在の就業状況</p>

                <?php
                $results = employment();

                foreach($results as $result){
                  employmentResult($result);
                }
                ?>
                <!-- <input type="radio" name="nowEmploymentStatus" id="nowEmploymentStatus1" value="正社員" checked><label class="form-control-radio" for="nowEmploymentStatus1">正社員</label>
                <input type="radio" name="nowEmploymentStatus" id="nowEmploymentStatus2" value="契約社員"><label class="form-control-radio" for="nowEmploymentStatus2">契約社員</label>
                <input type="radio" name="nowEmploymentStatus" id="nowEmploymentStatus3" value="派遣社員"><label class="form-control-radio" for="nowEmploymentStatus3">派遣社員</label>
                <input type="radio" name="nowEmploymentStatus" id="nowEmploymentStatus4" value="パート・アルバイト"><label class="form-control-radio" for="nowEmploymentStatus4">パート・アルバイト</label>
                <input type="radio" name="nowEmploymentStatus" id="nowEmploymentStatus5" value="業務委託"><label class="form-control-radio" for="nowEmploymentStatus5">業務委託</label>
                <input type="radio" name="nowEmploymentStatus" id="nowEmploymentStatus6" value="その他"><label class="form-control-radio" for="nowEmploymentStatus6">その他</label>
                <input type="radio" name="nowEmploymentStatus" id="nowEmploymentStatus7" value="離職中"><label class="form-control-radio" for="nowEmploymentStatus7">離職中</label> -->
              </div>

              <div class="form-group radio">
                <p for="registration_nowIncome">現在年収</p>
                <?php
                $results = nowIncome();
                foreach($results as $result) {
                  nowIncomeResult($result);
                }
                ?>

                <!-- <input type="radio" name="nowIncome" id="nowIncome1" value="200万円以下" checked><label class="form-control-radio" for="nowIncome1">200万円以下</label>
                <input type="radio" name="nowIncome" id="nowIncome2" value="200万円以上300万円未満"><label class="form-control-radio" for="nowIncome2">200万円以上300万円未満</label>
                <input type="radio" name="nowIncome" id="nowIncome3" value="300万円以上400万円未満"><label class="form-control-radio" for="nowIncome3">300万円以上400万円未満</label>
                <input type="radio" name="nowIncome" id="nowIncome4" value="400万円以上500万円未満"><label class="form-control-radio" for="nowIncome4">400万円以上500万円未満</label>
                <input type="radio" name="nowIncome" id="nowIncome5" value="500万円以上600万円未満"><label class="form-control-radio" for="nowIncome5">500万円以上600万円未満</label>
                <input type="radio" name="nowIncome" id="nowIncome6" value="600万円以上700万円未満"><label class="form-control-radio" for="nowIncome6">600万円以上700万円未満</label>
                <input type="radio" name="nowIncome" id="nowIncome7" value="700万円以上800万円未満"><label class="form-control-radio" for="nowIncome7">700万円以上800万円未満</label>
                <input type="radio" name="nowIncome" id="nowIncome8" value="800万円以上900万円未満"><label class="form-control-radio" for="nowIncome8">800万円以上900万円未満</label>
                <input type="radio" name="nowIncome" id="nowIncome9" value="900万円以上1000万円未満"><label class="form-control-radio" for="nowIncome9">900万円以上1000万円未満</label>
                <input type="radio" name="nowIncome" id="nowIncome10" value="1000万円以上"><label class="form-control-radio" for="nowIncome10">1000万円以上</label> -->
              </div>



              <div class="form-group">
              <p for="registration_prefecture">居住地</p>
                <select name="prefecture" id="registration_prefecture">
                  <?php
                  $results = prefecture();
                  var_dump($results);

                  foreach($results as $result){
                    prefectureResult($result);
                  }
                  ?>
                <!-- <option value="P01">北海道</option>,
                <option value="P02">青森県</option>,
                <option value="P03">岩手県</option>,
                <option value="P04">宮城県</option>,
                <option value="P05">秋田県</option>,
                <option value="P06">山形県</option>,
                <option value="P07">福島県</option>,
                <option value="P08">茨城県</option>,
                <option value="P09">栃木県</option>,
                <option value="P10">群馬県</option>,
                <option value="P11">埼玉県</option>,
                <option value="P12">千葉県</option>,
                <option value="P13">東京都</option>,
                <option value="P14">神奈川県</option>,
                <option value="P15">新潟県</option>,
                <option value="P16">富山県</option>,
                <option value="P17">石川県</option>,
                <option value="P18">福井県</option>,
                <option value="P19">山梨県</option>,
                <option value="P20">長野県</option>,
                <option value="P21">岐阜県</option>,
                <option value="P22">静岡県</option>,
                <option value="P23">愛知県</option>,
                <option value="P24">三重県</option>,
                <option value="P25">滋賀県</option>,
                <option value="P26">京都府</option>,
                <option value="P27">大阪府</option>,
                <option value="P28">兵庫県</option>,
                <option value="P29">奈良県</option>,
                <option value="P30">和歌山県</option>,
                <option value="P31">鳥取県</option>,
                <option value="P32">島根県</option>,
                <option value="P33">岡山県</option>,
                <option value="P34">広島県</option>,
                <option value="P35">山口県</option>,
                <option value="P36">徳島県</option>,
                <option value="P37">香川県</option>,
                <option value="P38">愛媛県</option>,
                <option value="P39">高知県</option>,
                <option value="P40">福岡県</option>,
                <option value="P41">佐賀県</option>,
                <option value="P42">長崎県</option>,
                <option value="P43">熊本県</option>,
                <option value="P44">大分県</option>,
                <option value="P45">宮崎県</option>,
                <option value="P46">鹿児島県</option>,
                <option value="P47">沖縄県</option>,
                <option value="P48">海外</option> -->
              </select>
              </div>

              <div class="text-center">
                <input type="submit" name="btn_confirm" class="btn btn-primary" value="確認する">
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