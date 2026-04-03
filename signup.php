<?php
// Include the database connection file
include('dbc/dbsignup.php');  // Correct path to db.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];

    // Validate the data (ensure that all fields are not empty)
    if (empty($username) || empty($email) || empty($gender) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    try {
        // Check if the username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Username already taken
            echo "Username already taken. Please choose another one.";
            exit;
        }

        // Check if the email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Email already taken
            echo "Email is already registered. Please choose another one.";
            exit;
        }

        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, gender, password) 
                                VALUES (:username, :email, :gender, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            // Redirect to login page after successful registration
            header("Location: login.html");  // Redirect to login page
            exit;  // Make sure to stop further execution
        } else {
            echo "Error: Unable to register user.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();  // Handle any exceptions
    }
}

$conn = null;  // Close the connection
?>
