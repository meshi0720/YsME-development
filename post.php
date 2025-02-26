<?php
session_start();
require_once('funcs.php');
loginCheck();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">

    <style>
        .menu {
            background-color: #2FA6E9;

        }
    </style>
    <title>POSTアンケート</title>
</head>

<header>
    <div class="header-list">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="homework.php">Homework</a></li>
            <li><a href="chat.php">Chat</a></li>
            <li><a href="school.php">School</a></li>
            <li><a href="post.php">Survey</a></li>
            <li><a href="scformresult.php">アンケート結果</a></li>
        </ul>
    </div>
</header>

<body>
    <div class="menu">
        <h3>学校選びのためのアンケート</h3>
        <ul>
            <li>以下の質問に答えてください</li>
            <li>いつ変えてもOK!何回出してもOK!</li>
        </ul>
    </div>

    <form action="insert.php" method="post">
        <br>
        <p>1.男子・女子校または共学どちらが良いですか？</p>
        <label><input type="radio" name="q1" value="共学"> 共学</label>
        <label><input type="radio" name="q1" value="男子校"> 男子校</label>
        <label><input type="radio" name="q1" value="女子校"> 女子校</label>
        <label><input type="radio" name="q1" value="こだわらない"> こだわらない</label><br>
        <br><br>

        <p>2. 制服はある方が良いですか？</p>
        <label><input type="radio" name="q2" value="ある"> ある</label>
        <label><input type="radio" name="q2" value="ない"> ない</label>
        <label><input type="radio" name="q2" value="こだわらない"> こだわらない</label><br>
        <br><br>

        <p>3. 通学時間はどこまで耐えられますか？</p>
        <label><input type="radio" name="q3" value="1時間以内"> 1時間以内</label>
        <label><input type="radio" name="q3" value="1時間半以内"> 1時間半以内</label>
        <label><input type="radio" name="q3" value="2時間以内"> 2時間以内</label><br>
        <br><br>

        <input type="submit" value="送信">
        <br>
    </form>
</body>

</html>
