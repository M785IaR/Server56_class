<?php
require_once 'db.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    echo "指定されたメニューは見つかりませんでした。";
    exit;
}
$comments_stmt = $pdo->prepare("SELECT * FROM comments WHERE menu_id = ? ORDER BY created_at DESC");
$comments_stmt->execute([$id]);
$comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($item['name']); ?>の詳細 - 焼肉 ささや</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .detail-box {
            padding: 20px;
            margin: 0 auto 20px;
            width: 95%; /* スマホで左右に少し余白を作る */
            box-sizing: border-box;
        }
        .detail-box h2 { font-size: 1.3rem; border-bottom: 1px solid #d4af37; padding-bottom: 5px; }
        .comment-form-box input, .comment-form-box textarea, .comment-form-box select {
            width: 100%;
            box-sizing: border-box;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .comment-list li {
            padding: 15px;
            margin-bottom: 10px;
            word-wrap: break-word; /* 長いコメントの改行対応 */
        }
    </style>
</head>
<body>
<div class="container">
    <div class="nav-back-area">
        <a href="index.php" class="back-btn">← TOPページに戻る</a>
    </div>

    <div class="section-header">
        <h1><?php echo htmlspecialchars($item['name']); ?></h1>
    </div>

    <div class="detail-box">
        <h2>部位の特徴</h2>
        <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
        <p><strong>味わい・食感：</strong> <?php echo htmlspecialchars($item['taste']); ?></p>
        
        <h2>おすすめの食べ方</h2>
        <p><?php echo nl2br(htmlspecialchars($item['recommended_method'])); ?></p>
    </div>

    <div class="comment-form-box">
        <h3>「<?php echo htmlspecialchars($item['name']); ?>」にコメントする</h3>
        <form action="submit_comment.php" method="POST">
            <input type="hidden" name="menu_id" value="<?php echo $id; ?>">
            <label for="username">名前：</label>
            <input type="text" id="username" name="username" required>
            <label for="age_group">年代：</label>
            <select id="age_group" name="age_group">
                <option value="回答しない">回答しない</option>
                <option value="10代">10代</option>
                <option value="20代">20代</option>
                <option value="30代">30代</option>
                <option value="40代">40代</option>
                <option value="50代以上">50代以上</option>
            </select>
            <label for="comment">コメント：</label>
            <textarea id="comment" name="comment" rows="4" required></textarea>
            <button type="submit" style="width:100%; padding:15px;">コメントを送信</button>
        </form>
    </div>

    <div class="section-header">
        <h2>「<?php echo htmlspecialchars($item['name']); ?>」の感想</h2>
    </div>
    
    <ul class="comment-list">
        <?php if ($comments): ?>
            <?php foreach ($comments as $comment): ?>
                <li>
                    <span class="part-label"><?php echo htmlspecialchars($item['name']); ?> について</span><br>
                    <strong><?php echo htmlspecialchars($comment['username']); ?></strong>（<?php echo htmlspecialchars($comment['age_group']); ?>）
                    <br><small style="color:#666;">投稿日: <?php echo $comment['created_at']; ?></small>
                    <p style="margin-top:10px; border-top:1px solid #444; padding-top:10px;">
                        <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                    </p>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; color:#d4af37;">まだコメントはありません。</p>
        <?php endif; ?>
    </ul>
</div>
</body>
</html>