<?php
session_start(); // Start the session
include './db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Set session variable
            $_SESSION['user_id'] = $row['id'];
            // Redirect to dashboard.php upon successful login
            header("Location: /projects/PizzaWay/dashboard.php");
            exit();
        } else {
            // Redirect to login page with an error message
            header("Location: /projects/PizzaWay/profile/sign-in.html?error=1");
            exit();
        }
    } else {
        // Redirect to login page with an error message
        header("Location: /projects/PizzaWay/profile/sign-in.html?error=1");
        exit();
    }

    $conn->close();
}
?>
