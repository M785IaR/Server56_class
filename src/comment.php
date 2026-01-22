<?php
require_once 'db.php';
$items = $pdo->query("SELECT id, name FROM menu ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>コメント投稿 - 焼肉 ささや</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="nav-back-area">
        <a href="index.php" class="back-btn">← TOPページに戻る</a>
    </div>

    <div class="section-header">
        <h1>コメントを投稿する</h1>
    </div>

    <div class="comment-form-box">
        <form action="submit_comment.php" method="POST">
            <label for="menu_id">メニュー選択:</label>
            <select id="menu_id" name="menu_id">
                <option value="">店舗全体へのコメント</option>
                <?php foreach ($items as $item): ?>
                    <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="username">名前:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="age_group">年齢層:</label>
            <select id="age_group" name="age_group" required>
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
            
            <label for="comment">コメント:</label>
            <textarea id="comment" name="comment" rows="4" required></textarea>
            
            <button type="submit">投稿する</button>
        </form>
    </div>
</div>
</body>
</html>