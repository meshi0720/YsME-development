<?php
session_start();
require_once('funcs.php');
loginCheck();

// DB接続
$pdo = db_conn();
$user_id = $_SESSION['user_id'];

$api_key = getOpenAIKey();  // APIキーを取得
$user_input = isset($_POST['user_input']) ? $_POST['user_input'] : '';

// 入力がない場合のエラーチェック
if (empty($user_input)) {
    echo json_encode([
        'success' => false,
        'message' => 'メッセージがありません'
    ]);
    exit;
}

// OpenAI API 呼び出し
function call_gpt_3_5_turbo_api($messages, $api_key) {
    $url = "https://api.openai.com/v1/chat/completions";
    $headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $api_key
    );
    $data = array(
        "model" => "gpt-3.5-turbo",
        "messages" => $messages,
        "max_tokens" => 500
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    // レスポンスが取得できなかった場合のエラーチェック
    if (curl_errno($ch)) {
        error_log("cURLエラー: " . curl_error($ch));
    }

    curl_close($ch);

    return $response;
}

// ユーザーからのメッセージを含むリクエストデータ
$messages = [
    ["role" => "system", "content" => "You are a helpful assistant."],
    ["role" => "user", "content" => $user_input]
];

// API 呼び出し
$response = call_gpt_3_5_turbo_api($messages, $api_key);

// レスポンスをデコード
$response_decoded = json_decode($response, true);

// レスポンスの内容をログ出力（デバッグ用）
error_log("API レスポンス: " . print_r($response_decoded, true));

// 応答が存在するかをチェック
if (isset($response_decoded["choices"][0]["message"]["content"])) {
    $message = $response_decoded["choices"][0]["message"]["content"];
    echo json_encode([
        'success' => true,
        'message' => $message
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'APIからの応答が不正です'
    ]);
}
?>
