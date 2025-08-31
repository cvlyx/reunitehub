<?php
//session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token'])) {
    if (validate_csrf_token($_POST['csrf_token'])) {
        // Get form data
        $type = $_POST['type'];
        $category = trim($_POST['category']);
        $description = trim($_POST['description']);
        $location = trim($_POST['location']);
        $date_time = $_POST['date_time'];
        $anonymous = isset($_POST['anonymous']) ? 1 : 0;
        $contact_email = trim($_POST['contact_email']);
        $contact_phone = trim($_POST['contact_phone']);
        
        // Handle file upload
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_extension;
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = $target_path;
            }
        }
        
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO items (user_id, type, category, description, location, date_time, contact_email, contact_phone, image_path, anonymous) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssssi", $_SESSION['user_id'], $type, $category, $description, $location, $date_time, $contact_email, $contact_phone, $image_path, $anonymous);
        
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error reporting item: " . $conn->error;
        }
    } else {
        echo "Invalid CSRF token";
    }
}
?>