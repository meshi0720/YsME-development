<?php
session_start();
require_once('funcs.php');
loginCheck();
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
            </ul>
        </div>
    </header>

    <!-- コンテンツ表示画面 -->

    <div>
        <h1>旅の相棒と出会おう！</h1>
        <br>
        <p>これからの長旅には楽しいことも辛いことも起こるはず。そんな時、仲間がいると楽しいことはもっと楽しく、辛いことはみんなで分け合って、、、
            <br>そういう仲間がいることであなたはもっとワクワクすることができるでしょう。まずは相棒と呼ぶべきメンバーを見つけましょう。
        </p>
        <br>
        <div><button id="meet">相棒に会う</button></div>
        <br>
        <div class="partner">
            <img src="./image/partner5.jpg" alt="partner image" />
        </div>
        <br><br>    
        <div>
            <div>相棒と会話しよう</div>
            <br>
            <!--コンテンツ表示画面-->
            <div>
                <input type="text" id="uname" placeholder="名前を入力"><br><br>
            </div>
                <textarea id="text" placeholder="文章を入力してください" cols="50" rows="10"></textarea>
            <br><br><br>
            <button id="send">送信</button>
        </div>
        <div id="output" style="overflow: auto; height: 300px;"></div>
    </div>

