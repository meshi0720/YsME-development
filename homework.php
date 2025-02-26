
<?php
session_start();
require_once('funcs.php');
loginCheck();

$pdo = db_conn();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>宿題</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        .button { padding: 5px 10px; margin: 0 2px; }
        .complete { background-color: #4CAF50; color: white; }
        .incomplete { background-color: #f44336; color: white; }
    </style> 
</head>

<body>

    <header>
        <div class="header-list">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="homework.php">Homework</a></li>
                <li><a href="chat.php">Chat</a></li>
                <li><a href="school.php">School</a></li>
                <li><a href="post.php">Survey</a></li>
                <li><a href="scformresult.php">アンケート結果</a></li>
            </ul>
        </div>
    </header>

    <?php
    // タスクの登録
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $subject = $_POST['subject']; // 教科
        $homework_type = $_POST['homework_type']; // 宿題の種類
        $details = $_POST['details']; // 詳細
        $due_date = $_POST['due_date']; // 期限

        // データベースへ保存
        $stmt = $pdo->prepare("INSERT INTO tasks (subject, homework_type, details, due_date, status) VALUES (?, ?, ?, ?, '未完了')");
        $stmt->execute([$subject, $homework_type, $details, $due_date]);


        // リロードしてフォーム再送信を防止
        header("Location: homework.php");
        exit;
    }

    // タスクの取得
    $stmt = $pdo->query("SELECT * FROM tasks");
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // タスクの削除
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: homework.php");
        exit;
    }

    // ステータス更新
    if (isset($_GET['toggle_status'])) {
        $id = $_GET['toggle_status'];
        $current_status = $_GET['current_status'];
        $new_status = $current_status === '未完了' ? '完了' : '未完了';

        $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $id]);

        header("Location: homework.php");
        exit;
    }
    ?>

    <h1>宿題</h1>
    <br><br>
    <form method="POST" action="">
        <div>
            <label for="subject">教科:</label>
            <select id="subject" name="subject" required>
                <option value="国語">国語</option>
                <option value="算数">算数</option>
                <option value="理科">理科</option>
                <option value="社会">社会</option>
            </select>
        </div>
        <br>
        <div>
            <label for="homework_type">宿題の種類:</label>
            <select id="homework_type" name="homework_type" required>
                <option value="予習">予習シリーズ</option>
                <option value="復習">復習シリーズ</option>
                <option value="ドリル">ドリル</option>
                <option value="計算">計算</option>
            </select>
        </div>
        <br>
        <div>
            <label for="details">詳細:</label>
            <textarea id="details" name="details"></textarea>
        </div>
        <br>
        <div>
            <label for="due_date">期限:</label>
            <input type="date" id="due_date" name="due_date" required>
        </div>

        <br><br>
        <button type="submit">登録</button>
    </form>
    <br><br>
    <h2>全ての宿題</h2>
    <table>
        <thead>
            <tr>
                <th>教科</th>
                <th>期限</th>
                <th>宿題の種類</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['subject'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($task['due_date'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($task['homework_type'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a class="button <?= $task['status'] === '完了' ? 'complete' : 'incomplete' ?>"
                           href="?toggle_status=<?= $task['id'] ?>&current_status=<?= $task['status'] ?>">
                            <?= htmlspecialchars($task['status'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </td>
                    <td>
                        <a class="button" href="?delete=<?= $task['id'] ?>" onclick="return confirm('本当に削除しますか？')">削除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
