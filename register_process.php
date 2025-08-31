<?php
//session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    if (validate_csrf_token($_POST['csrf_token'])) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $username;
            echo "success";
        } else {
            if ($conn->errno === 1062) {
                echo "Username or email already exists";
            } else {
                echo "Error creating account: " . $conn->error;
            }
        }
    } else {
        echo "Invalid CSRF token";
    }
}
?>