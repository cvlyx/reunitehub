<?php
session_start();
require_once '../config.php';

header('Content-Type: application/json');
ob_start();

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token']);
    error_log("Auth failed: Invalid CSRF token");
    exit;
}

if ($action === 'login') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        error_log("Login failed: Missing required fields");
        exit;
    }

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
        error_log("Prepare failed: " . $conn->error);
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
        error_log("Login failed: Invalid credentials for email $email");
    }
} elseif ($action === 'register') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        error_log("Register failed: Missing required fields");
        exit;
    }

    if (strlen($password) < 8) {
        http_response_code(400);
        echo json_encode(['error' => 'Password must be at least 8 characters']);
        error_log("Register failed: Password too short");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
        error_log("Prepare failed: " . $conn->error);
        exit;
    }
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        echo json_encode(['success' => true, 'message' => 'Registration successful']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Registration failed: Email or username already exists']);
        error_log("Register failed: " . $stmt->error);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action']);
    error_log("Auth failed: Invalid action");
}
ob_end_flush();
?>