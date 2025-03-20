<?php
session_start();
require_once('funcs.php');
loginCheck();
$pdo = db_conn(); // DB接続

// 学校データ取得
$stmt = $pdo->prepare("SELECT * FROM schools");
$stmt->execute();
$schools = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>志望校選びアンケートV1</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
 
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

    <div class="container">
        <h1>君が行きたいと思える学校を探してみよう！</h1>
        <br><br>
    </div>
    <br><br>

    <div>
        <h1>通学できる範囲にある学校一覧</h1>
        <br><br>
        <table id="schoolTable" border="1">
            <thead>
                <tr>
                    <th>選択</th>
                    <th>学校名</th>
                    <th>住所</th>
                    <th>種別</th>
                    <th>お気に入り</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schools as $school): ?>
                    <tr data-id="<?= $school['id'] ?>">
                        <td><input type="checkbox"></td>
                        <td><?= htmlspecialchars($school['name']) ?></td>
                        <td><?= htmlspecialchars($school['location']) ?></td>
                        <td><?= htmlspecialchars($school['school_type']) ?></td>
                        <td>
                            <button class="favorite-btn" data-id="<?= $school['id'] ?>" data-fav="<?= $school['favorite'] ?>">
                                <?= $school['favorite'] ? "★" : "☆" ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
    </div>

    <script>
        document.querySelectorAll(".favorite-btn").forEach(button => {
            button.addEventListener("click", async () => {
                const schoolId = button.dataset.id;
                const currentFav = button.dataset.fav === "1" ? 0 : 1;

                const response = await fetch("update_favorite.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: schoolId, favorite: currentFav })
                });

                const result = await response.json();
                if (result.success) {
                    button.innerText = currentFav ? "★" : "☆";
                    button.dataset.fav = currentFav;
                } else {
                    alert("更新に失敗しました");
                }
            });
        });
    </script>

</body>

</html>
