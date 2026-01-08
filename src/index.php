<?php
require_once 'db.php'; 

$sql = "SELECT c.*, m.name AS part_name 
        FROM comments c 
        LEFT JOIN meat_parts m ON c.meat_part_id = m.id 
        ORDER BY c.created_at DESC";

try {
    $stmt = $pdo->query($sql);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $comments = [];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>焼肉 ささや</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <h1>焼肉 ささや</h1>

    <hr style="margin-top: 30px;">
    <h2>牛肉部位紹介（牛の部位を選択）</h2>

    <div class="image-map-container"> 
        <img src="../image/cuts of meat.jpg" usemap="#image-map" alt="牛の部位図">
        <map name="image-map">
            <area target="" alt="ツラミ" title="ツラミ" href="detail.php?id=1" coords="103,234,191,183" shape="rect">
            <area target="" alt="タン" title="タン" href="detail.php?id=2" coords="52,296,138,247" shape="rect">
            <area target="" alt="ミスジ" title="ミスジ" href="detail.php?id=3" coords="215,209,316,154" shape="rect">
            <area target="" alt="ウルテ" title="ウルテ" href="detail.php?id=4" coords="214,273,316,216" shape="rect">
            <area target="" alt="ハツ" title="ハツ" href="detail.php?id=5" coords="218,337,315,285" shape="rect">
            <area target="" alt="天使の羽" title="天使の羽" href="detail.php?id=6" coords="333,210,434,153" shape="rect">
            <area target="" alt="肩ロース" title="肩ロース" href="detail.php?id=7" coords="435,222,329,276" shape="rect">
            <area target="" alt="レバー" title="レバー" href="detail.php?id=8" coords="330,340,434,286" shape="rect">
            <area target="" alt="ミノ" title="ミノ" href="detail.php?id=9" coords="330,401,434,349" shape="rect">
            <area target="" alt="リブロース" title="リブロース" href="detail.php?id=10" coords="446,255,549,161" shape="rect">
            <area target="" alt="ハラミ" title="ハラミ" href="detail.php?id=11" coords="452,337,550,273" shape="rect">
            <area target="" alt="ハチノス" title="ハチノス" href="detail.php?id=12" coords="453,397,549,352" shape="rect">
            <area target="" alt="カルビ" title="カルビ" href="detail.php?id=13" coords="451,460,585,415" shape="rect">
            <area target="" alt="サーロイン" title="サーロイン" href="detail.php?id=14" coords="566,216,702,165" shape="rect">
            <area target="" alt="ヒレ" title="ヒレ" href="detail.php?id=15" coords="566,275,706,223" shape="rect">
            <area target="" alt="ホルモン" title="ホルモン" href="detail.php?id=16" coords="570,338,703,290" shape="rect">
            <area target="" alt="ゼンマイ" title="ゼンマイ" href="detail.php?id=17" coords="567,398,665,348" shape="rect">
            <area target="" alt="カイノミ" title="カイノミ" href="detail.php?id=18" coords="602,461,711,414" shape="rect">
            <area target="" alt="イチボ" title="イチボ" href="detail.php?id=19" coords="793,219,898,163" shape="rect">
            <area target="" alt="大腸" title="大腸" href="detail.php?id=20" coords="721,339,807,286" shape="rect">
            <area target="" alt="赤センマイ" title="赤センマイ" href="detail.php?id=21" coords="683,400,779,352" shape="rect">
            <area target="" alt="モモ" title="モモ" href="detail.php?id=22" coords="801,424,898,351" shape="rect">
            <area target="" alt="ヒウチ" title="ヒウチ" href="detail.php?id=23" coords="801,498,897,446" shape="rect">
            <area target="" alt="テール" title="テール" href="detail.php?id=24" coords="878,78,979,24" shape="rect">
        </map>
    </div>
    
    <div class="btn-group">
        <a href="ranking.php" class="action-link ranking-btn">🏆 全メニュー人気ランキング・アンケート</a>
    </div>

    <hr style="margin-top: 30px;">
    
    <h2>全コメント一覧</h2>
    <ul class="comment-list">
        <?php if (count($comments) > 0): ?>
            <?php foreach ($comments as $row): ?>
                <li>
                    <span class="part-label">
                        <?php echo $row['part_name'] ? htmlspecialchars($row['part_name']) . "について" : "焼肉 ささやについて"; ?>
                    </span><br>
                    <strong><?php echo htmlspecialchars($row['username']); ?></strong>（<?php echo htmlspecialchars($row['age_group']); ?>）
                    <small style="color: #777;">投稿日: <?php echo $row['created_at']; ?></small>
                    <p style="margin-top: 5px;"><?php echo nl2br(htmlspecialchars($row['comment'])); ?></p>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>まだコメントはありません。</p>
        <?php endif; ?>
    </ul>

    <div class="comment-form-box">
        <h3 style="margin-top:0;">店舗へのコメント</h3>
        <form action="submit_comment.php" method="POST">
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/image-map-resizer/1.0.10/js/imageMapResizer.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        imageMapResize();
    });
</script>

</body>
</html>