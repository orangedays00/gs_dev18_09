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

// function outputResult($result, $authority){
//   p('<tr class="account-list">' .'<td class="account-id">'. $result["id"] .'</td>'.'<td class="account-name">' .$result["name"] . '</td>' . '<td class="account-email">' . $result["email"] . '</td>');
//   if($authority == 0 ){
//     p('<td class="account-delete"><input type="button" id="btn'. $result["id"]. '" onclick="deleteAccount(this.id)" value="×"></td>');
//   } else {
//     p('<td class="account-delete"></td>');
//   }
//   p('</tr>');
// }

function outputResult($result, $authority){
  p('<tr class="account-list">' .'<td class="account-id">'. $result["id"] .'</td>'.'<td class="account-name">' .$result["name"] . '</td>' . '<td class="account-email">' . $result["email"] . '</td>');
  if($authority == 0 ){
    p('<td class="account-delete"><a href="../src/delete.php?id='.$result["id"] .'">×</a></td>');
  } else {
    p('<td class="account-delete"></td>');
  }
  p('</tr>');
}

?>