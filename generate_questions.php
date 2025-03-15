<?php
session_start();
require_once('funcs.php');
require_once('db_connect.php');

loginCheck();
$user_id = $_SESSION['id'];

$api_key = getOpenAIKey();
$image_path = $_POST['image_path'];  // アップロード済みの画像パス

// 教科書の内容（OCR処理が必要なら別途追加）
$text_from_image = "画像から抽出したテキスト（OCR機能が必要）";

$prompt = "次の内容に基づいて、10個の問題を作成してください:\n" . $text_from_image;

$messages = [
    ["role" => "system", "content" => "あなたは優秀な教育アシスタントです。"],
    ["role" => "user", "content" => $prompt]
];

$response = call_gpt_3_5_turbo_api($messages, $api_key);
$response_decoded = json_decode($response, true);

if (isset($response_decoded["choices"][0]["message"]["content"])) {
    $questions = explode("\n", $response_decoded["choices"][0]["message"]["content"]);

    foreach ($questions as $question) {
        $stmt = $pdo->prepare("INSERT INTO chat_questions (user_id, image_path, question) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $image_path, $question]);
    }

    echo json_encode(["success" => true, "questions" => $questions]);
} else {
    echo json_encode(["success" => false, "message" => "AIの応答が不正です。"]);
}
?>
