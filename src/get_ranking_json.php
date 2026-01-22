<?php
require_once 'db.php';
header('Content-Type: application/json');

$sql = "SELECT m.name, COUNT(v.id) AS vote_count 
        FROM menu m
        LEFT JOIN votes v ON m.id = v.menu_id
        GROUP BY m.id, m.name
        ORDER BY vote_count DESC, m.id ASC";

$stmt = $pdo->query($sql);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));