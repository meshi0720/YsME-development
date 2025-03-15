<?php

//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

//DB接続
function db_conn()
{
    $host = '127.0.0.1'; // `localhost` ではなく `127.0.0.1` に変更
    $dbname = 'schoolchoice_db_test';
    $username = 'root';
    $password = '';
    $port = '3306'; // ポートを明示的に指定

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo; // 接続成功時にPDOオブジェクトを返す
    } catch (PDOException $e) {
        // **エラーメッセージをブラウザに表示**
        echo "データベース接続エラー: " . $e->getMessage();
        exit;
    }
}

//SQLエラー
function sql_error($stmt)
{
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit('SQLError:' . $error[2]);
}

//リダイレクト
function redirect($file_name)
{
    header('Location: ' . $file_name);
    exit();
}


// ログインチェク処理 loginCheck()
function loginCheck(){

    if(!isset($_SESSION['chk_ssid']) || $_SESSION['chk_ssid'] != session_id()){
    //ログインを経由できていない場合、ここで止めたい→よってエラーメッセージで終了させる
    exit('LOGIN ERROR');
    }

session_regenerate_id(true);
$_SESSION['chk_ssid'] = session_id();

}

//API Keyの呼び出し
require_once('config.php'); // config.php を呼び出し

function getOpenAIKey() {
    return OPENAI_API_KEY; // config.php からAPIキーを取得
}
