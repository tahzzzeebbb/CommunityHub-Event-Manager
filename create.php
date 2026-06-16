<?php
session_start();
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Check user logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once '../../config/db.php';

// Get JSON body data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$title = trim($data['title'] ?? '');
$description = trim($data['description'] ?? '');
$event_datetime = $data['event_datetime'] ?? '';
$location = trim($data['location'] ?? '');
$capacity = intval($data['capacity'] ?? 0);
$community_id = intval($_SESSION['community_id']);  // from session

if (!$title || !$description || !$event_datetime || !$location || $capacity <= 0 || !$community_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Please fill all required fields correctly.']);
    exit;
}

// Insert into database
try {
    $stmt = $pdo->prepare("INSERT INTO events (title, description, event_datetime, location, capacity, community_id, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $event_datetime, $location, $capacity, $community_id, $_SESSION['user_id']]);
    echo json_encode(['success' => 'Event created successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
