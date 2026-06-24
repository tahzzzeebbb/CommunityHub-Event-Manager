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
$password = $data['password'] ?? '';

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Please provide username and password']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
    exit;
}

// Set session variables
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];
$_SESSION['community_id'] = $user['community_id'];

echo json_encode([
    'success' => 'Login successful',
    'user' => [
        'id' => $user['id'],
        'username' => $user['username'],
        'role' => $user['role'],
        'community_id' => $user['community_id']
    ]
]);
?>
