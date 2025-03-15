<?php
session_start();
require_once('funcs.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["image_id"])) {
    $image_id = $_POST["image_id"];

    // 画像パス取得
    $pdo = db_conn();
    $stmt = $pdo->prepare("SELECT image_path FROM uploaded_data WHERE id = :image_id");
    $stmt->bindValue(":image_id", $image_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $image_path = $row["image_path"];

        // OCR処理（ここではダミー）
        $extracted_text = "OCRで抽出されたテキスト";

        // DBに保存
        $stmt = $pdo->prepare("UPDATE uploaded_data SET extracted_text = :extracted_text WHERE id = :image_id");
        $stmt->bindValue(":extracted_text", $extracted_text, PDO::PARAM_STR);
        $stmt->bindValue(":image_id", $image_id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(["success" => true, "extracted_text" => $extracted_text]);
    } else {
        echo json_encode(["success" => false, "error" => "画像が見つかりません"]);
    }
}
?>
