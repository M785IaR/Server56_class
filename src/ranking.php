<?php
require_once 'db.php';

// ランキングデータの取得（初期表示用）
$sql = "
    SELECT m.id, m.name, COUNT(v.id) AS vote_count 
    FROM meat_parts m
    LEFT JOIN votes v ON m.id = v.meat_part_id
    GROUP BY m.id, m.name
    ORDER BY vote_count DESC, m.id ASC
";

try {
    $stmt = $pdo->query($sql);
    $ranking_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $parts_stmt = $pdo->query("SELECT id, name FROM meat_parts ORDER BY id ASC");
    $all_parts = $parts_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $ranking_data = []; $all_parts = [];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>全メニュー人気ランキング & アンケート</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    <a href="index.php" class="back-link">← TOPページに戻る</a>
    <h1>🏆 全メニュー人気ランキング</h1>

    <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <canvas id="rankingChart"></canvas>
    </div>

    <div id="rank-list" class="rank-list">
        <?php $rank = 1; foreach ($ranking_data as $data): ?>
            <div class="rank-item" data-id="<?php echo $data['id']; ?>">
                <span>#<?php echo $rank++; ?> <span class="part-name"><?php echo htmlspecialchars($data['name']); ?></span></span>
                <strong><span class="count"><?php echo $data['vote_count']; ?></span> 票</strong>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="survey-section">
        <h1>アンケート投票<h1>
        <h2>🗳️ あなたの好きなメニューは？</h2>
        <p>（投票すると上のグラフが即時更新されます！）</p>
        
        <form id="voteForm">
            <div class="checkbox-group">
                <?php foreach ($all_parts as $part): ?>
                    <label>
                        <input type="checkbox" name="part_ids[]" value="<?php echo $part['id']; ?>">
                        <?php echo htmlspecialchars($part['name']); ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="vote-btn">投票を送信する</button>
        </form>
    </div>
</div>

<script>
// --- 1. グラフの初期設定 ---
const ctx = document.getElementById('rankingChart').getContext('2d');
let rankingData = <?php echo json_encode($ranking_data); ?>;

const chart = new Chart(ctx, {
    type: 'bar', // 横棒グラフにするなら 'bar' で indexAxis: 'y'
    data: {
        labels: rankingData.map(d => d.name),
        datasets: [{
            label: '得票数',
            data: rankingData.map(d => d.vote_count),
            backgroundColor: 'rgba(233, 150, 122, 0.7)',
            borderColor: 'rgba(139, 0, 0, 1)',
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y', // 横棒グラフ
        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// --- 2. Ajax投票処理 ---
document.getElementById('voteForm').addEventListener('submit', function(e) {
    e.preventDefault(); // 画面リロードを阻止

    const formData = new FormData(this);

    // fetchでサーバーにデータを送る (Ajax)
    fetch('submit_vote_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('投票ありがとうございます！');
            updateRanking(); // グラフとリストを更新
            this.reset();    // フォームを空にする
        } else {
            alert('投票に失敗しました。');
        }
    })
    .catch(error => console.error('Error:', error));
});

// --- 3. 最新データを取得して画面を更新する関数 ---
function updateRanking() {
    fetch('get_ranking_json.php')
    .then(response => response.json())
    .then(newData => {
        // グラフの更新
        chart.data.labels = newData.map(d => d.name);
        chart.data.datasets[0].data = newData.map(d => d.vote_count);
        chart.update();

        // リストの更新
        const listContainer = document.getElementById('rank-list');
        listContainer.innerHTML = '';
        newData.forEach((item, index) => {
            listContainer.innerHTML += `
                <div class="rank-item">
                    <span>#${index + 1} ${item.name}</span>
                    <strong>${item.vote_count} 票</strong>
                </div>
            `;
        });
    });
}
</script>

</body>
</html>