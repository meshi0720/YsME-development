<?php
Session_start();

//$name = 'hayato';  
//$age = 23;

//慣れないうちはこまめにechoでデバック
//echo $name . $age;

//$_SESSION['name'] = $name;
//$_SESSION['age']  = $age;

//sessionのID情報を見る方法
$sid = session_id();
echo $sid;

