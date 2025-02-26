<?php
//SESSIONスタート
session_start();

//関数を呼び出す
require_once('funcs.php');

//ログインチェック
loginCheck();
//以下ログインユーザーのみ

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>志望校選びアンケートV1</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

<header>
    <div class="header-list">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="homework.php">Homework</a></li>
            <li><a href="chat.php">Chat</a></li>
            <li><a href="school.php">School</a></li>
            <li><a href="post.php">Survey</a></li>
            <li><a href="scformresult.php">アンケート結果</a></li>>
    </div>
</header>


     <div class="container">
        <img src="./image/boy start.png" alt="explore image" />
        <b>ジブンを変える旅に出よう！</b>
        <p>未来を作る冒険譚<br>「Y's ME」</p>
    </div>
    
     <br>
     <br>    
        <div>
            <div>冒険を始める前に未来の自分に一言！！</div>
            <textarea id="text" cols="50" rows="2"></textarea>
    <br>
            <button id="send">送信</button>
    <br>
    <br>
            <button id="logout"><a class="button" href="logout.php">ログアウト</a></button>

    </div>
    <br><br>
</body>


