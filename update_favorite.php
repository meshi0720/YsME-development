<?php
session_start();
require_once('funcs.php');
loginCheck();
$pdo = db_conn();

// JSON 受け取り
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id'], $data['favorite'])) {
    echo json_encode(["success" => false]);
    exit;
}

// データ更新
$stmt = $pdo->prepare("UPDATE schools SET favorite = :favorite WHERE id = :id");
$stmt->bindValue(':favorite', $data['favorite'], PDO::PARAM_INT);
$stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
$success = $stmt->execute();

echo json_encode(["success" => $success]);
