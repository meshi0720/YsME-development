<?php

//エラーを確認(一度エラーが出て表示されなかったため追加)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//1. POSTデータ取得
$id = $_POST['id'];
$q1   = $_POST['q1'];
$q2   = $_POST['q2'];
$q3   = $_POST['q3'];

//2. DB接続します
require_once('funcs.php');
$pdo = db_conn();

//３．データ登録SQL作成
$stmt = $pdo->prepare('UPDATE answer1 SET    
                        q1 = :q1,
                        q2 = :q2,
                        q3 = :q3,
                        date = sysdate()
                    WHERE
                        id = :id;
                    ');

// 数値の場合 PDO::PARAM_INT
// 文字の場合 PDO::PARAM_STR
$stmt->bindValue(':q1', $q1, PDO::PARAM_STR);
$stmt->bindValue(':q2', $q2, PDO::PARAM_STR);
$stmt->bindValue(':q3', $q3, PDO::PARAM_STR); 
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute(); //実行

//４．データ登録処理後
if ($status === false) {
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
} else {
    header('Location: scformresult.php');
    exit();
}
?>