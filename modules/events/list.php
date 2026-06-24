<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once '../../config/db.php';

$community_id = intval($_SESSION['community_id']);

try {
    $stmt = $pdo->prepare("SELECT id, title, description, event_datetime, location, capacity FROM events WHERE community_id = ? AND event_datetime >= NOW() ORDER BY event_datetime ASC");
    $stmt->execute([$community_id]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['events' => $events]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
