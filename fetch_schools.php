<?php
require_once('config.php'); // APIキーなどの設定

// APIから学校データを取得
$api_url = "https://school.teraren.com"; // ここに実際のAPI URLを入れる
$response = file_get_contents($api_url);
$schools = json_decode($response, true);

if (!$schools) {
    die("APIデータの取得に失敗しました。");
}

// CSVファイルを作成・保存
$csv_file = 'schools.csv';
$fp = fopen($csv_file, 'w');

// CSVのヘッダーを書き込む
fputcsv($fp, ['学校名', '住所', '種別']);

// データを書き込む
foreach ($schools as $school) {
    fputcsv($fp, [$school['name'], $school['address'], $school['type']]);
}

fclose($fp);

// 成功メッセージをJSONで返す
echo json_encode(["message" => "CSVファイルを保存しました。", "file" => $csv_file]);
?>
