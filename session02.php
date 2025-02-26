<?php
Session_start();
$name = $_SESSION['name'];
$age = $_SESSION['age'];

echo '02のファイルです';
echo $name . $age;
