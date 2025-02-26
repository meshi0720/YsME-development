<?php

//1. POSTデータ取得
$q1 = $_POST['q1'];
$q2 = $_POST['q2'];
$q3 = $_POST['q3'];

//2. DB接続
require_once('funcs.php');
$pdo = db_conn();



//３．データ登録SQL作成

// -1. SQL文を用意
$stmt = $pdo->prepare("INSERT INTO answer1(id, q1, q2, q3, date)
                       VALUES(NULL, :q1, :q2, :q3, now())"
                       );

// -2.バインド変数を用意
// Integer 数値の場合　PDO：：PARAM_INT
// String 文字列の場合　PDO：：PARAM_STR
$stmt->bindValue(':q1', $q1, PDO::PARAM_STR);
$stmt->bindValue(':q2', $q2, PDO::PARAM_STR);
$stmt->bindValue(':q3', $q3, PDO::PARAM_STR);

//  -3. 実行
$status = $stmt->execute();

//４．データ登録処理後
if($status === false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit('ErrorMessage:'.$error[2]);
}else{
//５．post.phpへリダイレクト
    header('Location: post.php');

}
?>
