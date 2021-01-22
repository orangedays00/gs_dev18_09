<?php

// 文字出力
function p($str) {
  print $str;
}

//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES);
}


/*
 * データベースハンドラーの取得
 */
function getDbh() {
  $dsn='mysql:dbname=gs_db_ats;charset=utf8;host=localhost';
  $user='root';
  $pass='root';
      try{
      $dbh = new PDO($dsn,$user,$pass);
      $dbh->query('SET NAMES utf8');
  }catch(PDOException $e){
      p('Error:'.$e->getMessage());
      p('データベースへの接続に失敗しました。時間をおいて再度お越し下さい。');
      die();
  }
  return $dbh;
}

// アカウント一覧を取得
function getResult(){
  /* 取得クエリ */
  $sql = "SELECT id, name, email, account_type
      FROM gs_user";

  /* 取得準備 */
  $stmt = getDbh()->prepare($sql);
  /* スレッド情報を取得 */
  $stmt->execute();
  /** 全件を$resultに代入 */
  $result = $stmt->fetchAll();
  /** 呼び出しもとに取得結果を返す */
  return $result;
}

// アカウント一覧を整形
function outputResult($result, $authority){
  p('<tr class="account-list">' .'<td class="account-id">'. $result["id"] .'</td>'.'<td class="account-name">' .$result["name"] . '</td>' . '<td class="account-email">' . $result["email"] . '</td>');
  if($authority == 0){ //ログインアカウントが管理者の場合
    if($result["account_type"] == 0){ //引数のデータが、管理者の場合
      p('<td class="account-delete"></td>');
    }else{
      p('<td class="account-delete"><input type="button" id="btn'. $result["id"]. '" onclick="deleteAccount(this.id)" value="×"></td>');
    }
  } else {
    p('<td class="account-delete"></td>');
  }
  p('</tr>');
}

// 応募者一覧を取得
function getApplicant(){
  /* 取得クエリ */
  $sql = "SELECT id, last_name, first_name, last_kana, first_kana, birth_day, sex
      FROM gs_applicant_user";

  /* 取得準備 */
  $stmt = getDbh()->prepare($sql);
  /* スレッド情報を取得 */
  $stmt->execute();
  /** 全件を$resultに代入 */
  $result = $stmt->fetchAll();
  /** 呼び出しもとに取得結果を返す */
  return $result;
}

// 応募者一覧
function outputApplicant($result){
  $now = date("Ymd");
  $birthday = str_replace("-", "", $result["birth_day"]);//ハイフンを除去しています。
  $age = floor(($now-$birthday)/10000).'歳';
  p('<tr class="applicant-list">' .'<td class="applicant-id">'. $result["id"] .'</td>'.'<td class="applicant-name"><a href="detail/'.$result["id"].'/">' .$result["last_name"]. " " . $result["first_name"] . '</a></td>' . '<td class="applicant-kana">' . $result["last_kana"]. " " . $result["first_kana"]  . '</td>'. '<td class="applicant-age">' . $age. '</td>' . '<td class="applicant-sex">' . $result["sex"] . '</td>' . '</rt>');
}

// 応募者登録：居住地
function prefecture(){
  $sql = "SELECT prefecture_name
      FROM gs_m_prefecture";

  /* 取得準備 */
  $stmt = getDbh()->prepare($sql);
  /* スレッド情報を取得 */
  $stmt->execute();
  /** 全件を$resultに代入 */
  $result = $stmt->fetchAll();
  /** 呼び出しもとに取得結果を返す */
  return $result;
}

// 応募者登録：居住地リスト
function prefectureResult($result) {
  p('<option value="'.$result["prefecture_name"].'">'. $result["prefecture_name"].'</option>');
}

// 応募者登録：現在年収
function nowIncome() {
  $sql = "SELECT *
      FROM gs_m_nowincome";

  /* 取得準備 */
  $stmt = getDbh()->prepare($sql);
  /* スレッド情報を取得 */
  $stmt->execute();
  /** 全件を$resultに代入 */
  $result = $stmt->fetchAll();
  /** 呼び出しもとに取得結果を返す */
  return $result;
}

// 応募者登録：現在年収リスト
function nowIncomeResult($result) {
  if($result["id"] == 1){
    p('<input type="radio" name="registration_nowIncome" id="nowIncome'.$result["id"].'" value="'.$result["nowincome_text"].'" checked><label class="form-control-radio" for="nowIncome'.$result["id"].'">'.$result["nowincome_text"].'</label>');
  } else {
    p('<input type="radio" name="registration_nowIncome" id="nowIncome'.$result["id"].'" value="'.$result["nowincome_text"].'"><label class="form-control-radio" for="nowIncome'.$result["id"].'">'.$result["nowincome_text"].'</label>');
  }
}

// 応募者登録：現在の雇用形態
function employment() {
  $sql = "SELECT *
      FROM gs_m_employment";

  /* 取得準備 */
  $stmt = getDbh()->prepare($sql);
  /* スレッド情報を取得 */
  $stmt->execute();
  /** 全件を$resultに代入 */
  $result = $stmt->fetchAll();
  /** 呼び出しもとに取得結果を返す */
  return $result;
}

// 応募者登録：現在の雇用形態リスト
function employmentResult($result) {
  if($result["id"] == 1){
    p('<input type="radio" name="registration_nowEmploymentStatus" id="nowEmploymentStatus'.$result["id"].'" value="'.$result["employment"].'" checked><label class="form-control-radio" for="nowEmploymentStatus'.$result["id"].'">'.$result["employment"].'</label>');
  } else {
    p('<input type="radio" name="registration_nowEmploymentStatus" id="nowEmploymentStatus'.$result["id"].'" value="'.$result["employment"].'"><label class="form-control-radio" for="nowEmploymentStatus'.$result["id"].'">'.$result["employment"].'</label>');
  }
}

function getApplicantDetail() {
  $urlArray = explode("/", $_SERVER["REQUEST_URI"]);
  $applicantId = $urlArray[4];
  
  $sql = 'SELECT * FROM gs_applicant_user WHERE id = :id';

  $stmt = getDbh()->prepare($sql);
  $stmt->bindParam(':id', $applicantId, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

// // 一発削除
// function outputResult($result, $authority){
//   p('<tr class="account-list">' .'<td class="account-id">'. $result["id"] .'</td>'.'<td class="account-name">' .$result["name"] . '</td>' . '<td class="account-email">' . $result["email"] . '</td>');
//   if($authority == 0 ){
//     p('<td class="account-delete"><a href="../src/delete.php?id='.$result["id"] .'">×</a></td>');
//   } else {
//     p('<td class="account-delete"></td>');
//   }
//   p('</tr>');
// }

?>