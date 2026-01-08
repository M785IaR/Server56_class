<?php
// DB接続
$servername = "db";
$username = "root";
$password = "root";
$dbname = "yakiniku_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続チェック
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

// コメントの投稿処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $age_group = $_POST['age_group'];
    $comment = $_POST['comment'];
    $meat_part_id = $_POST['meat_part_id'];
    $created_at = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO comments (username, age_group, comment, meat_part_id, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $age_group, $comment, $meat_part_id, $created_at);
    $stmt->execute();

    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>焼肉 ささや</title>
</head>
<body>

<div style="text-align: center; padding: 20px;">
    <h1>コメントを投稿する</h1>

    <form action="comment.php" method="POST" style="max-width: 400px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        
        <!-- 部位選択 -->
        <label for="meat_part_id">部位:</label><br>
        <select id="meat_part_id" name="meat_part_id" style="width: 100%; padding: 8px; margin-top: 8px;" required>
            <option value="1">カルビ</option>
            <option value="2">ハラミ</option>
            <option value="3">ヒレ</option>
            <option value="4">ミスジ</option>
            <option value="5">タン</option>
            <option value="6">ホルモン</option>
            <option value="7">モモ</option>
        </select><br><br>

        <!-- 名前 -->
        <label for="username">名前:</label><br>
        <input type="text" id="username" name="username" style="width: 100%; padding: 8px; margin-top: 8px;" required><br><br>

        <!-- 年齢グループ -->
        <label for="age_group">年齢グループ:</label><br>
        <select id="age_group" name="age_group" style="width: 100%; padding: 8px; margin-top: 8px;" required>
            <option value="10歳未満">10歳未満</option>
            <option value="10代">10代</option>
            <option value="20代">20代</option>
            <option value="30代">30代</option>
            <option value="40代">40代</option>
            <option value="50代">50代</option>
            <option value="60代">60代以上</option>
        </select><br><br>

        <!-- コメント -->
        <label for="comment">コメント:</label><br>
        <textarea id="comment" name="comment" style="width: 100%; padding: 8px; margin-top: 8px;" required></textarea><br><br>

        <!-- 投稿ボタン -->
        <button type="submit" style="width: 100%; padding: 12px; background-color: #66c2a5; color: white; border: none; border-radius: 25px; font-size: 1rem;">投稿する</button>
    </form>

</div>

</body>
</html>

<?php
$conn->close();
?>
