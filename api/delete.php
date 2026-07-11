<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$uid = $_SESSION['user_id'];
$id = (int)($_POST['id'] ?? 0);

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing id']);
    exit;
}

$stmt = $pdo->prepare('DELETE FROM trips WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $uid]);

echo json_encode(['success' => true, 'deleted' => $stmt->rowCount()]);
