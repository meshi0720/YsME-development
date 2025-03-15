<?php
session_start();
require_once('funcs.php');
loginCheck();

// DB接続
$pdo = db_conn();
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT question FROM chat_questions WHERE user_id = ?");
$stmt->execute([$user_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>志望校選びアンケートV1</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
    
    <style>
        /* チャットの吹き出しスタイル */
        #output {
            overflow: auto;
            height: 300px;
            border: 1px solid #ccc;
            padding: 10px;
            background: #f9f9f9;
        }

        .message {
            display: flex;
            margin-bottom: 10px;
            align-items: flex-start;
        }

        .user-message, .bot-message {
            max-width: 70%;
            padding: 10px;
            border-radius: 10px;
            word-wrap: break-word;
        }

        .user-message {
            background: #d1e7dd;
            align-self: flex-end;
        }

        .bot-message {
            background: #fff;
            border: 1px solid #ccc;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <!-- コンテンツ表示画面 -->
    <div>
        <h1>旅の相棒と出会おう！</h1>
        <br>
        <p>これからの長旅には楽しいことも辛いことも起こるはず。そんな時、仲間がいると楽しいことはもっと楽しく、辛いことはみんなで分け合って、、、</p>
        <br>
        <div><button id="meet">相棒に会う</button></div>
        <br>
        <div class="partner">
            <img src="./image/partner5.jpg" alt="partner image" />
        </div>
        <br><br>    
        
        <div>
            <div>相棒と会話しよう</div>
            <br>
            
            <form action="chat_api.php" method="post">
                <p>何をしたい？</p>
                <label><input type="radio" name="q1" value="おはなし"> おはなし</label>
                <label><input type="radio" name="q1" value="テスト作成"> テスト出してもらう</label>
                <label><input type="radio" name="q1" value="相棒に会う"> 相棒に会う</label>
                <br><br>

                <div>
                    <input type="text" id="uname" placeholder="名前を入力"><br><br>
                </div>
                <textarea id="text" placeholder="文章を入力してください" cols="50" rows="10"></textarea>
                <br><br>
                <button type="button" id="send">送信</button>
            </form>

            <form id="upload-form" enctype="multipart/form-data">
                <input type="file" id="image-upload" accept="image/*">
                <button type="button" id="upload-btn">画像をアップロード</button>
            </form>
            <div id="upload-status"></div>

            <h2>生成された問題</h2>
            <ul>
                <?php foreach ($questions as $q) { ?>
                <li><?php echo htmlspecialchars($q['question']); ?></li>
                <?php } ?>
            </ul>

        </div>
        
        <div id="output"></div>
    </div>

    <script>
    $(document).ready(function(){
        $("#send").click(function(){
            var userText = $("#text").val();
            if(userText.trim() === "") {
                alert("入力してください");
                return;
            }

            // ユーザーの吹き出しを追加（右寄せ）
            $("#output").append('<div class="message" style="justify-content: flex-end;">' +
                                '<div class="user-message">' + userText + '</div></div>');
            $("#text").val("");

            $.ajax({
                type: "POST",
                url: "chat_api.php",
                data: { user_input: userText },
                dataType: "json",
                success: function(response) {
                    if(response.success) {
                        // AIの吹き出しを追加（左寄せ）
                        $("#output").append('<div class="message" style="justify-content: flex-start;">' +
                                            '<div class="bot-message">' + response.message + '</div></div>');
                    } else {
                        $("#output").append('<div class="message" style="justify-content: flex-start;">' +
                                            '<div class="bot-message">エラーが発生しました</div></div>');
                    }
                },
                error: function() {
                    $("#output").append('<div class="message" style="justify-content: flex-start;">' +
                                        '<div class="bot-message">サーバーエラー</div></div>');
                }
            });
        });
    });

// 画像アップロード時にDBへ保存
$("#upload-btn").click(function(){
    var formData = new FormData();
    formData.append("image", $("#image-upload")[0].files[0]);

    $.ajax({
        type: "POST",
        url: "upload.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            var res = JSON.parse(response);
            if (res.success) {
                $("#upload-status").text("画像アップロード成功");
                
                // OCR処理を開始
                $.post("ocr.php", { image_id: res.image_id }, function(ocrRes) {
                    var ocrData = JSON.parse(ocrRes);
                    if (ocrData.success) {
                        $("#output").append("<p>OCR抽出: " + ocrData.extracted_text + "</p>");

                        // 問題生成を開始
                        $.post("generate_questions.php", { image_id: res.image_id }, function(qRes) {
                            var qData = JSON.parse(qRes);
                            if (qData.success) {
                                $("#output").append("<p>生成された問題:</p>");
                                qData.questions.forEach(q => {
                                    $("#output").append("<p>" + q.question + "</p>");
                                });
                            }
                        });
                    }
                });
            }
        }
    });
});


    //chatで答えを表示
    $("#send").click(function(){
        var userText = $("#text").val();

        if (userText.trim() === "答えを教えて") {
            $.ajax({
                type: "POST",
                url: "get_answers.php",
                success: function(response) {
                    $("#output").append("<p><strong>AI:</strong> " + response.answer + "</p>");
                }
            });
        }
    });
    </script>

</body>
</html>
