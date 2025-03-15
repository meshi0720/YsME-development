<?php
session_start();
require_once('funcs.php');
loginCheck();

// DB接続
$pdo = db_conn();
$user_id = $_SESSION['user_id'];

// タスクの登録
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $homework_type = $_POST['homework_type'];
    $details = $_POST['details'];
    $due_date = $_POST['due_date'];

    // タイトルの修正
    $title = $subject . ' - ' . $homework_type;

    $stmt = $pdo->prepare("INSERT INTO tasks (title, details, due_date, category, status, user_id) VALUES (?, ?, ?, ?, '未完了', ?)");
    $stmt->execute([$title, $details, $due_date, $homework_type, $user_id]);

    header("Location: homework.php");
    exit;
}

// タスクの取得
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// タスクの削除
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);

    header("Location: homework.php");
    exit;
}

// ユーザーのポイント取得
$sql = "SELECT points FROM usertable WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$currentPoints = $user ? $user['points'] : 0;
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
        .button { padding: 5px 10px; margin: 0 2px; cursor: pointer; }
        .complete { background-color: #4CAF50; color: white; padding: 5px; border-radius: 5px; }
        .incomplete { background-color: #f44336; color: white; padding: 5px; border-radius: 5px; }
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

<script>
    function updateHomeworkTypes() {
        const homeworkOptions = {
            "国語": ["読解", "漢字練習", "作文"],
            "算数": ["計算", "文章問題", "図形"],
            "理科": ["実験レポート", "用語暗記", "観察記録"],
            "社会": ["地理", "歴史", "公民"]
        };

        const subject = document.getElementById("subject").value;
        const homeworkSelect = document.getElementById("homework_type");
        homeworkSelect.innerHTML = "";

        const defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "--宿題の種類を選択してください--";
        homeworkSelect.appendChild(defaultOption);

        if (subject in homeworkOptions) {
            homeworkOptions[subject].forEach(type => {
                const option = document.createElement("option");
                option.value = type;
                option.textContent = type;
                homeworkSelect.appendChild(option);
            });
        }
    }

   function toggleTaskStatus(taskId, currentStatus) {
        fetch(`update_task_status.php?task_id=${taskId}&current_status=${currentStatus}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // ステータスを更新
                const statusElement = document.getElementById(`status-${taskId}`);
                statusElement.innerHTML = data.new_status === '完了' 
                    ? '<span class="complete">完了</span>' 
                    : '<span class="incomplete">未完了</span>';

            // ポイントを更新
            document.getElementById("points").textContent = data.new_points + " pt";
            
            // 次回クリック用に新しいステータスを設定
            statusElement.setAttribute("onclick", `toggleTaskStatus(${taskId}, '${data.new_status}')`);
            } else {
                alert("エラー: " + data.message);
            }
        });
    }

    function updatePointsUI() {
        fetch('get_points.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("points").textContent = data.points + " pt";
            }
        });
    }
</script>

<form method="POST">
    <div>
        <label for="subject">教科:</label>
        <select id="subject" name="subject" required onchange="updateHomeworkTypes()">
            <option value="">--選択してください--</option>
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
            <option value="">--教科を選択してください--</option>
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
    <br>
    <button type="submit">登録</button>
</form>

<br><br>
<p>現在のポイント: <span id="points"><?= htmlspecialchars($currentPoints, ENT_QUOTES, 'UTF-8') ?> pt</span></p>
<br><br>

<h2>宿題一覧</h2>
<br>
<table>
    <thead>
        <tr>
            <th>タイトル</th>
            <th>期限</th>
            <th>カテゴリー</th>
            <th>ステータス</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($task['due_date'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($task['category'], ENT_QUOTES, 'UTF-8') ?></td>
                <td id="status-<?= $task['id'] ?>" onclick="toggleTaskStatus(<?= $task['id'] ?>, '<?= $task['status'] ?>')">
                    <span class="<?= $task['status'] === '完了' ? 'complete' : 'incomplete' ?>">
                        <?= htmlspecialchars($task['status'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
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
