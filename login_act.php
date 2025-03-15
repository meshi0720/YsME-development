<?php
//最初にSESSIONを開始！！ココ大事！！
session_start();

//POST値を受け取る
$lid = $_POST['lid'];
$lpw = $_POST['lpw'];

//1.  DB接続します
require_once('funcs.php');
$pdo = db_conn();

//2. データ登録SQL作成
// gs_user_tableに、IDとWPがあるか確認する。
$stmt = $pdo->prepare('SELECT * FROM usertable where lid = :lid AND lpw = :lpw;');
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR);
$status = $stmt->execute();

// ハッシュ化対応のコード
//$stmt = $pdo->prepare('SELECT * FROM gs_user_table where lid = :lid AND lpw = :lpw;')←これを削除
//$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
//$stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR);
//$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if($status === false){
    sql_error($stmt);
}

//4. 抽出データ数を取得(1行分のデータを取得)
$val = $stmt->fetch();

//if(password_verify($lpw, $val['lpw'])){ //* PasswordがHash化の場合はこっちのIFを使う
//if( $val['id'] != '' && password_verify($lpw, $val['lpw']) ){
if( $val['id'] != ''){
    //Login成功時 該当レコードがあればSESSIONに値を代入
    $_SESSION['chk_ssid'] = session_id();
    // ユーザーIDをセッションに保存
    $_SESSION['user_id'] = $val['id']; 
    //管理者フラグによる権限設定
    $_SESSION['kanriflag'] = $val['kanriflag'];
    header('Location: index.php');
}else{
    //Login失敗時(Logout経由)
    header('Location: loginv2.php');
}

exit();
