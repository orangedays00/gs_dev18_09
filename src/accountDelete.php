<?php

// $id = $_GET['id'];

$webRoot = $_SERVER['DOCUMENT_ROOT'];

require_once($webRoot . '/gs_ats/src/function.php');

$id = $_POST['deleteId'];
var_dump($id);

$dbh = getDbh();

// $dbh->beginTransaction();

  $sql = "DELETE FROM gs_user WHERE id = :id;";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $status = $stmt->execute();

  if($status == false) {
    $error_sql = $stmt->errorInfo();
    exit("ErrorMessage:".$error_sql[2]);
  } else {
    header("Location: ../account/index.php");
    exit();
  }


?>