<?php
session_start();
require_once('funcs.php');
loginCheck();

require_once('funcs.php');
$pdo = db_conn();

//２．データ取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM answer1;");
  //executeで実行
$status = $stmt->execute();

//３．データ表示
$view="";
if ($status === false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる(中身を１行ずつ持ってくるから)
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){

    $view .='<p>';
    $view .='<a href="detail.php?id=' . $result['id'] . '">';
    $view .= $result['date'] .'/' . h($result['q1']) . '/' . h($result['q2']) . '/' . h($result['q3']);
    $view .='</a>';

    $view .='<a href="delete.php?id=' . $result['id'] . '">';
    $view .= '【アンケート結果の削除(click)】';
    $view .='</a>';

    $view .='</p>';
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>アンケート表示</title>
<link rel="stylesheet" href="css/range.css">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>div{font-size:14px;}</style>
</head>
<body id="main">
<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
      <a class="navbar-brand" href="post.php">アンケート画面に戻る</a>
      </div>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<div>
    <div class="container jumbotron"><?= $view ?></div>
</div>
<!-- Main[End] -->

</body>
</html>

