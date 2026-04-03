<?php
session_start();  // Start the session

// Include the database connection file
include('dbc/dblogin.php');  // Ensure the path is correct

// Debugging: Check if $conn is defined
if (!$conn) {
    die("Database connection failed");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted email and password
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the inputs
    if (empty($email) || empty($password)) {
        // Set the error message if the fields are empty
        $error_message = "Both email and password are required.";
    } else {
        try {
            // Prepare the SQL statement to check for the user with the provided email
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Check if the user exists
            if ($stmt->rowCount() > 0) {
                // Fetch user data
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify if the entered password matches the hashed password
                if (password_verify($password, $user['password'])) {
                    // Password is correct, set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];

                    // Redirect the user to home.html after successful login
                    header("Location: home.php");  // Redirect to home.html
                    exit;
                } else {
                    // Set the error message for invalid password
                    $error_message = "Invalid password.";
                }
            } else {
                // Set the error message if no user is found
                $error_message = "No user found with that email.";
            }
        } catch (PDOException $e) {
            // Handle any exceptions (e.g., database connection issues)
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Include the login page again with the error message (if any)
include('login.html');
?>
