<?php
session_start();
require_once('funcs.php');
loginCheck();

$pdo = db_conn();
$user_id = $_SESSION['user_id'];

$sql = "SELECT points FROM usertable WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user) {
    echo json_encode(["success" => true, "points" => $user['points']]);
} else {
    echo json_encode(["success" => false, "message" => "ユーザーが見つかりません"]);
}
?>
