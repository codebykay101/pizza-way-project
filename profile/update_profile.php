<?php
session_start();
include '../db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ./profile/sign-in.html');
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);
    $zipCode = $conn->real_escape_string($_POST['zipCode']);
    
    // Check if password and confirmPassword are set before using them
    $password = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $conn->real_escape_string($_POST['confirmPassword']) : '';

    if ($password !== $confirmPassword && !empty($password)) {
        echo "Passwords do not match.";
        exit;
    }

    // Hash the password if provided
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET firstName='$firstName', lastName='$lastName', email='$email', zipCode='$zipCode', password='$hashedPassword' WHERE id='$user_id'";
    } else {
        $sql = "UPDATE users SET firstName='$firstName', lastName='$lastName', email='$email', zipCode='$zipCode' WHERE id='$user_id'";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?success=1"); // Redirect to index.php with success flag
        exit;
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
?>
