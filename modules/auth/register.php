<?php
session_start();
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$community_id = intval($data['community_id'] ?? 0);
$role = 'member'; // default role

if (!$username || !$email || !$password || !$community_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Please fill all required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email']);
    exit;
}

// Check if username or email exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->execute([$username, $email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['error' => 'Username or Email already exists']);
    exit;
}

// Hash password securely
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role, community_id) VALUES (?, ?, ?, ?, ?)");
if ($stmt->execute([$username, $email, $password_hash, $role, $community_id])) {
    echo json_encode(['success' => 'User registered successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Registration failed']);
}
?>
