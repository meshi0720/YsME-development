<?php


$id = $_GET['id'];
require_once('funcs.php');
$pdo = db_conn();

$stmt = $pdo->prepare('SELECT * FROM answer1 WHERE id = :id;');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

$result = '';
if ($status === false) {
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
} else {
    $result = $stmt->fetch();
}

//画面で確認（表示）
//var_dump($result);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        .menu {
            background-color: #2FA6E9;

        }
    </style>
    <title>POSTアンケート</title>
</head>

<body>
    <div class="menu">
        <h3>学校選びのためのアンケート</h3>
        <ul>
            <li>以下の質問に答えてください</li>
            <li>いつ変えてもOK!何回出してもOK!</li>
        </ul>
    </div>

    <form action="update.php" method="post">
        <p>1.男子・女子校または共学どちらが良いですか？</p>
        <label><input type="radio" name="q1" value="共学" <?= $result['q1'] === '共学' ? 'checked' : '' ?>> 共学</label>
        <label><input type="radio" name="q1" value="男子校" <?= $result['q1'] === '男子校' ? 'checked' : '' ?>> 男子校</label>
        <label><input type="radio" name="q1" value="女子校" <?= $result['q1'] === '女子校' ? 'checked' : '' ?>> 女子校</label>
        <label><input type="radio" name="q1" value="こだわらない" <?= $result['q1'] === 'こだらない' ? 'checked' : '' ?>> こだわらない</label><br>

        <p>2. 制服はある方が良いですか？</p>
        <label><input type="radio" name="q2" value="ある"<?= $result['q2'] === 'ある' ? 'checked' : '' ?>> ある</label>
        <label><input type="radio" name="q2" value="ない"<?= $result['q2'] === 'ない' ? 'checked' : '' ?>> ない</label>
        <label><input type="radio" name="q2" value="こだわらない"<?= $result['q2'] === 'こだわらない' ? 'checked' : '' ?>> こだわらない</label><br>

        <p>3. 通学時間はどこまで耐えられますか？</p>
        <label><input type="radio" name="q3" value="1時間以内"<?= $result['q3'] === '1時間以内' ? 'checked' : '' ?>> 1時間以内</label>
        <label><input type="radio" name="q3" value="1時間半以内"<?= $result['q3'] === '1時間半以内' ? 'checked' : '' ?>> 1時間半以内</label>
        <label><input type="radio" name="q3" value="2時間以内"<?= $result['q3'] === '2時間以内' ? 'checked' : '' ?>> 2時間以内</label><br>
        
        <input type="hidden" name="id" value="<?= $result['id'] ?>"></label><br>
        <input type="submit" value="更新">
    </form>


</body>

</html>
