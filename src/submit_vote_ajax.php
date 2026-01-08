<?php
require_once 'db.php';
header('Content-Type: application/json');

if (isset($_POST['part_ids']) && is_array($_POST['part_ids'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO votes (meat_part_id) VALUES (?)");
        foreach ($_POST['part_ids'] as $id) {
            $stmt->execute([$id]);
        }
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false]);
}