<?php
//session_start();
require_once '../config.php';

header('Content-Type: application/json');
ob_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token']);
    exit;
}

$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
$date_time = filter_input(INPUT_POST, 'date_time', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
$location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
$contact_email = filter_input(INPUT_POST, 'contact_email', FILTER_SANITIZE_EMAIL);
$contact_phone = filter_input(INPUT_POST, 'contact_phone', FILTER_SANITIZE_STRING);
$anonymous = isset($_POST['anonymous']) && $_POST['anonymous'] === 'on';
$user_id = $anonymous ? null : ($_SESSION['user_id'] ?? null);

if (empty($category) || empty($type) || empty($date_time) || empty($description) || empty($location) || ($type === 'found' && (empty($contact_email) && empty($contact_phone)))) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    error_log("Report item failed: Missing required fields");
    exit;
}

if (!in_array($type, ['lost', 'found']) || !in_array($category, ['Phone', 'Wallet/Purse', 'Keys', 'Bag/Backpack', 'ID Card', 'Jewelry', 'Documents', 'Other'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid category or type']);
    error_log("Report item failed: Invalid category or type");
    exit;
}

$image_path = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png'];
    $max_size = 5 * 1024 * 1024; // 5MB
    if (!in_array($_FILES['image']['type'], $allowed_types) || $_FILES['image']['size'] > $max_size) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid image type or size']);
        error_log("Report item failed: Invalid image type or size");
        exit;
    }
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $image_path = $upload_dir . uniqid() . '_' . basename($_FILES['image']['name']);
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to upload image']);
        error_log("Report item failed: Failed to upload image");
        exit;
    }
}

$stmt = $conn->prepare("INSERT INTO items (user_id, type, category, description, location, date_time, contact_email, contact_phone, image_path, anonymous) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    error_log("Prepare failed: " . $conn->error);
    exit;
}
$stmt->bind_param("issssssssi", $user_id, $type, $category, $description, $location, $date_time, $contact_email, $contact_phone, $image_path, $anonymous);

if ($stmt->execute()) {
    if ($user_id) {
        $item_id = $conn->insert_id;
        $message = "Your $type item ($category) has been reported successfully.";
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, item_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $item_id, $message);
        $stmt->execute();
    }
    echo json_encode(['success' => true, 'message' => 'Item reported successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save item']);
    error_log("Insert failed: " . $stmt->error);
}
ob_end_flush();
?>