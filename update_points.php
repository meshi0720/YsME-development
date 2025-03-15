<?php
session_start();

require_once('funcs.php');
loginCheck();

//DB接続
require_once('funcs.php');
    $pdo = db_conn();

// セッションからユーザーIDを取得
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" =>"ユーザーが未ログインです"]);
    exit;
    }
    $user_id = $_SESSION['user_id'];

// クエリパラメータからポイントの増減値を取得
if (!isset($_GET['points']) || $_GET['points'] === "" || !ctype_digit($_GET['points'])) {
    echo json_encode(["success" => false, "message" => "無効なポイント値"]);
    exit;
}

$pointChange = (int)$_GET['points'];  // 安全にキャスト

// `points` の値が0ならエラー
if ($pointChange === 0) {
    echo json_encode(["success" => false, "message" => "不正なポイント更新リクエスト"]);
    exit;
}



// 現在のポイントを取得
    $sql = "SELECT points FROM usertable WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

if ($user) {
    // ポイントを増減
    $new_points = $user['points'] + $pointChange;

    // ポイント更新処理
    $update_sql = "UPDATE usertable SET points = ? WHERE id = ?";
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->execute([$new_points, $user_id]);

    echo json_encode(["success" => true, "points" => $new_points]);
} else {
    echo json_encode(["success" => false, "message" => "ユーザーが見つかりません"]);
}
?>
