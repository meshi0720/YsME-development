<?php
require_once('funcs.php');
$api_key = getOpenAIKey();

$messages = [
    ["role" => "system", "content" => "以下の問題に対する解答を生成してください。"],
    ["role" => "user", "content" => "問題のリスト"]
];

$response = call_gpt_3_5_turbo_api($messages, $api_key);
$response_decoded = json_decode($response, true);

if (isset($response_decoded["choices"][0]["message"]["content"])) {
    echo json_encode(["answer" => $response_decoded["choices"][0]["message"]["content"]]);
} else {
    echo json_encode(["error" => "答えを取得できませんでした"]);
}
?>
