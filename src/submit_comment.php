<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: index.php');
    exit;
}

$menu_id = (!empty($_POST['menu_id'])) ? $_POST['menu_id'] : null;
$username = trim($_POST['username'] ?? '');
$age_group = $_POST['age_group'] ?? '';
$comment = trim($_POST['comment'] ?? '');

if (empty($username) || empty($comment)) {
    header('Location: index.php');
    exit;
}

$sql = "INSERT INTO comments (menu_id, username, age_group, comment) 
        VALUES (:menu_id, :username, :age_group, :comment)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':menu_id', $menu_id, $menu_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':age_group', $age_group, PDO::PARAM_STR);
    $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
    $stmt->execute();

    if ($menu_id) {
        header("Location: detail.php?id=" . $menu_id . "#comment-section");
    } else {
        header('Location: index.php');
    }
    exit;
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}