<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . '/projects/PizzaWay/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $phoneNum = htmlspecialchars(trim($_POST['phoneNum']));
    $zipCode = htmlspecialchars(trim($_POST['zipCode']));
    $DOB = $_POST['DOB'];

    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        die("Please fill in all required fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Check if the email is already registered
    $checkEmailQuery = $conn->prepare("SELECT email FROM users WHERE email=?");
    $checkEmailQuery->bind_param("s", $email);
    $checkEmailQuery->execute();
    $checkEmailQuery->store_result();

    if ($checkEmailQuery->num_rows > 0) {
        die("Email is already registered.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert data into the database
    $sql = $conn->prepare("INSERT INTO users (firstName, lastName, email, password, phoneNum, zipCode, DOB) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("sssssss", $firstName, $lastName, $email, $hashedPassword, $phoneNum, $zipCode, $DOB);

    if ($sql->execute() === TRUE) {
        // Send email to user
        $subject = "Welcome to PizzaWay!";
        $message = "Hello $firstName $lastName, \n\nThank you for signing up with PizzaWay. We're excited to have you with us!";
        $headers = "From: no-reply@pizzaway.com";

        mail($email, $subject, $message, $headers);

        // Redirect to login page with a query parameter indicating success
        header("Location: /projects/PizzaWay/profile/sign-in.html?success=1");
        exit();
    } else {
        echo "Error: " . $sql->error;
    }

    $sql->close();
    $conn->close();
}
?>
