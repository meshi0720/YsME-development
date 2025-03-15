<?php
session_start();
header("Content-Type: application/json");

// セッションデータをログ出力して確認
error_log("セッションデータ: " . print_r($_SESSION, true));

header("Content-Type: application/json"); // JSONレスポンスを明示

require_once('funcs.php');
loginCheck();

// セッションに id が設定されているか確認
if (!isset($_SESSION["id"])) {
    echo json_encode(["success" => false, "error" => "ログインが必要です"]);
    exit;
}

$user_id = $_SESSION["id"];  // 修正: 'user_id' ではなく 'id' を使用

// デバッグログ
error_log("セッションID: " . $user_id);

// DB接続
$pdo = db_conn();

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("無効なリクエスト");
    }

    if (!isset($_FILES["image"])) {
        throw new Exception("画像ファイルが送信されていません");
    }

    $target_dir = "uploads/";

    // アップロードディレクトリが存在しない場合は作成
    if (!is_dir($target_dir) && !mkdir($target_dir, 0777, true)) {
        throw new Exception("アップロードディレクトリの作成に失敗しました");
    }

    $file_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;  // 重複防止

    // ファイルのアップロード処理
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        throw new Exception("画像アップロードに失敗しました");
    }

    // DB接続の確認
    if (!$pdo) {
        throw new Exception("データベース接続に失敗しました");
    }

    // データを保存
    $stmt = $pdo->prepare("INSERT INTO uploaded_data (user_id, image_path) VALUES (:user_id, :image_path)");
    $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->bindValue(":image_path", $target_file, PDO::PARAM_STR);

    if (!$stmt->execute()) {
        throw new Exception("データベースへの保存に失敗しました");
    }

    $last_id = $pdo->lastInsertId();
    
    // 正常に処理が完了した場合、JSON形式でレスポンスを返す
    echo json_encode([
        "success" => true,
        "image_id" => $last_id,
        "image_path" => $target_file
    ]);

} catch (Exception $e) {
    // エラーが発生した場合、エラーログに記録し、JSON形式でエラーメッセージを返す
    error_log("エラー: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
    exit;
}
