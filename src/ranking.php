<?php
require_once 'db.php';

// 投票がないメニューも0票として取得
$sql = "
    SELECT m.id, m.name, COUNT(v.id) AS vote_count 
    FROM menu m
    LEFT JOIN votes v ON m.id = v.menu_id
    GROUP BY m.id, m.name
    ORDER BY vote_count DESC, m.id ASC
";

try {
    $stmt = $pdo->query($sql);
    $ranking_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $all_items = $pdo->query("SELECT id, name FROM menu ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $ranking_data = []; $all_items = [];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>人気ランキング - 焼肉 ささや</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            background: rgba(255,255,255,0.95); 
            padding: 20px; 
            border-radius: 8px; 
            margin-bottom: 40px;
        }

        .rank-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #444;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.05);
        }
        .rank-num { color: #d4af37; font-weight: bold; margin-right: 15px; min-width: 40px; }
        .rank-votes { color: #d4af37; font-weight: bold; }


        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            white-space: nowrap;
            background: #2a2a2a; 
            padding: 10px 15px;
            border-radius: 6px;
            border: 1px solid #666;
            cursor: pointer;
            color: #ffffff !important;
            font-weight: bold;
            font-size: 0.95rem;
            transition: 0.2s;
        }

        .checkbox-group label:hover {
            border-color: #d4af37;
            background: #333;
        }

        .checkbox-group input[type="checkbox"] {
            margin: 0 10px 0 0;
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        @media (max-width: 600px) {
            .checkbox-group {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }
            .checkbox-group label {
                white-space: normal;
                font-size: 0.85rem;
                padding: 12px 8px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="nav-back-area">
        <a href="index.php" class="back-btn">← TOPページに戻る</a>
    </div>
    
    <h1>🏆 全メニュー人気ランキング</h1>
    <p>（投票は下記にあり）</p>
    <div class="chart-container">
        <canvas id="rankingChart"></canvas>
    </div>

    <div id="rank-list" class="rank-list">
        <?php $rank = 1; foreach ($ranking_data as $data): ?>
            <div class="rank-item">
                <div style="display: flex; align-items: center;">
                    <span class="rank-num">#<?php echo $rank++; ?></span>
                    <span><?php echo htmlspecialchars($data['name']); ?></span>
                </div>
                <strong class="rank-votes"><?php echo $data['vote_count']; ?> 票</strong>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="comment-form-box">
        <h2 style="text-align: center; color: #d4af37;">🗳️ お気に入りに投票（複数回答可）</h2>
        <form id="voteForm">
            <div class="checkbox-group">
                <?php foreach ($all_items as $item): ?>
                    <label>
                        <input type="checkbox" name="menu_ids[]" value="<?php echo $item['id']; ?>">
                        <?php echo htmlspecialchars($item['name']); ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <button type="submit" style="margin-top: 30px; width: 100%; padding: 18px; background: #8B0000; color: #fff; border: 1px solid #d4af37; cursor: pointer; font-size: 1.1rem; font-weight: bold; border-radius: 4px;">投票を送信する</button>
        </form>
    </div>
</div>

<script>
const ctx = document.getElementById('rankingChart').getContext('2d');
const menuCount = <?php echo count($ranking_data); ?>;
ctx.canvas.height = menuCount * 30; 

let chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($ranking_data, 'name')); ?>,
        datasets: [{
            label: '得票数',
            data: <?php echo json_encode(array_column($ranking_data, 'vote_count')); ?>,
            backgroundColor: 'rgba(139, 0, 0, 0.8)', 
            borderColor: '#d4af37',
            borderWidth: 1
        }]
    },
    options: { 
        indexAxis: 'y', 
        maintainAspectRatio: false, 
        responsive: true,
        scales: { 
            x: { beginAtZero: true, ticks: { stepSize: 1, color: '#333' } },
            y: { ticks: { color: '#333', autoSkip: false } }
        }
    }
});

document.getElementById('voteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    if (formData.getAll('menu_ids[]').length === 0) {
        alert('1つ以上のメニューを選んでください。');
        return;
    }
    fetch('submit_vote_ajax.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('投票ありがとうございます！');
            location.reload();
        }
    });
});
</script>
</body>
</html>