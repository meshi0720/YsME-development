<?php
session_start();
require_once('funcs.php');
loginCheck();

// DB接続
$pdo = db_conn();
$user_id = $_SESSION['user_id'];

if (!isset($_GET['task_id']) || !isset($_GET['current_status'])) {
    echo json_encode(['success' => false, 'message' => '不正なリクエスト']);
    exit;
}

$task_id = $_GET['task_id'];
$current_status = $_GET['current_status'];

// ステータスの切り替え
$new_status = ($current_status === '未完了') ? '完了' : '未完了';

// ステータスを更新
$stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
$update_success = $stmt->execute([$new_status, $task_id, $user_id]);

if (!$update_success) {
    echo json_encode(['success' => false, 'message' => 'ステータス更新失敗']);
    exit;
}

// ポイントの加減算
$point_change = ($new_status === '完了') ? 1 : -1;
$stmt = $pdo->prepare("UPDATE usertable SET points = points + ? WHERE id = ?");
$stmt->execute([$point_change, $user_id]);

// 更新後のポイントを取得
$stmt = $pdo->prepare("SELECT points FROM usertable WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$new_points = $user ? $user['points'] : 0;

// 成功レスポンス
echo json_encode([
    'success' => true,
    'new_status' => $new_status,
    'new_points' => $new_points
]);
?>
