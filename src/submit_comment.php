<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: index.php');
    exit;
}

$meat_part_id = isset($_POST['meat_part_id']) ? $_POST['meat_part_id'] : null;
$username = trim($_POST['username'] ?? '');
$age_group = $_POST['age_group'] ?? '';
$comment = trim($_POST['comment'] ?? '');

if (empty($username) || empty($age_group) || empty($comment)) {
    header('Location: index.php');
    exit;
}

// データ挿入のSQL文
$sql = "INSERT INTO comments (meat_part_id, username, age_group, comment) 
        VALUES (:meat_part_id, :username, :age_group, :comment)";

try {
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':meat_part_id', $meat_part_id, $meat_part_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':age_group', $age_group, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

    $stmt->execute();

    if ($meat_part_id) {
        // 詳細ページからの投稿の場合、その詳細ページに戻る
        header("Location: detail.php?id=" . urlencode($meat_part_id) . "#comment-section");
    } else {
        // TOPページからの投稿の場合、TOPページに戻る
        header('Location: index.php');
    }
    exit;

} catch (PDOException $e) {
    echo "コメントの投稿に失敗しました。";
    exit;
}
?>