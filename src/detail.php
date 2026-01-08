<?php
header('Content-Type: text/html; charset=UTF-8'); 
require_once 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM meat_parts WHERE id = ?");
$stmt->execute([$id]);
$part = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$part) {
    echo "指定された部位は見つかりませんでした。";
    exit;
}

$comments_stmt = $pdo->prepare("SELECT * FROM comments WHERE meat_part_id = ? ORDER BY created_at DESC");
$comments_stmt->execute([$id]);
$comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>焼肉 ささや - <?php echo htmlspecialchars($part['name']); ?>の詳細</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <a href="index.php" class="back-link">← TOPページに戻る</a>

    <h1><?php echo htmlspecialchars($part['name']); ?></h1>

    <div class="detail-box">
        <h2>部位の特徴</h2>
        <p><?php echo nl2br(htmlspecialchars($part['description'])); ?></p>
        <p><strong>食感：</strong> <?php echo htmlspecialchars($part['taste']); ?></p>
        
        <h2>おすすめの食べ方</h2>
        <p><?php echo nl2br(htmlspecialchars($part['recommended_method'])); ?></p>
    </div>

    <h3 id="comment-section">「<?php echo htmlspecialchars($part['name']); ?>」についてコメント投稿しましょう！</h3>
    <div class="comment-form">
        <form action="submit_comment.php" method="POST">
            <input type="hidden" name="meat_part_id" value="<?php echo $id; ?>">
            
            <label>名前：</label>
            <input type="text" name="username" required>
            
            <label>年代：</label>
            <select name="age_group">
                <option value="回答しない">回答しない</option>
                <option value="10代未満">10代未満</option>
                <option value="10代">10代</option>
                <option value="20代">20代</option>
                <option value="30代">30代</option>
                <option value="40代">40代</option>
                <option value="50代">50代</option>
                <option value="60代">60代</option>
                <option value="70代以上">70代以上</option>
            </select>
            
            <label>コメント：</label>
            <textarea name="comment" rows="4" required></textarea>
            
            <button type="submit">コメントを送信</button>
        </form>
    </div>

    <h3>「<?php echo htmlspecialchars($part['name']); ?>」のコメント一覧</h3>
    <div class="comment-list">
        <?php if ($comments): ?>
            <?php foreach ($comments as $comment): ?>
                <p>
                    <strong><?php echo htmlspecialchars($comment['username']); ?>（<?php echo htmlspecialchars($comment['age_group']); ?>）:</strong>
                    <small>（投稿日: <?php echo $comment['created_at']; ?>）</small><br>
                    <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                </p>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center;">まだこの部位へのコメントはありません。</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>