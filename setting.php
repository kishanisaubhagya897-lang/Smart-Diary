<?php
session_start();
require_once 'dbc/dbs.php';

$userId = $_SESSION['user_id']; // Ensure user is logged in

$toastMessage = ""; // Toast message variable
$toastType = "";    // Type: success or error

// Handle updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_username'])) {
        $newUsername = trim($_POST['username']);
        $stmt = $conn->prepare("UPDATE Users SET username = ? WHERE id = ?");
        $stmt->bind_param("si", $newUsername, $userId);
        if ($stmt->execute()) {
            $toastMessage = "Username updated successfully!";
            $toastType = "success";
        } else {
            $toastMessage = "Error updating username.";
            $toastType = "error";
        }
    } elseif (isset($_POST['update_email'])) {
        $newEmail = trim($_POST['email']);
        $stmt = $conn->prepare("UPDATE Users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $newEmail, $userId);
        if ($stmt->execute()) {
            $toastMessage = "Email updated successfully!";
            $toastType = "success";
        } else {
            $toastMessage = "Error updating email.";
            $toastType = "error";
        }
    } elseif (isset($_POST['update_password'])) {
        $currentPassword = trim($_POST['current_password']);
        $newPassword = trim($_POST['new_password']);
        $confirmPassword = trim($_POST['confirm_password']);

        $stmt = $conn->prepare("SELECT password FROM Users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();

        if (password_verify($currentPassword, $userData['password'])) {
            if ($newPassword === $confirmPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE Users SET password = ? WHERE id = ?");
                $stmt->bind_param("si", $hashedPassword, $userId);
                if ($stmt->execute()) {
                    $toastMessage = "Password updated successfully!";
                    $toastType = "success";
                } else {
                    $toastMessage = "Error updating password.";
                    $toastType = "error";
                }
            } else {
                $toastMessage = "New passwords do not match.";
                $toastType = "error";
            }
        } else {
            $toastMessage = "Current password is incorrect.";
            $toastType = "error";
        }
    }
}

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Smart Diary</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/setting.css">
</head>
<body>
    <div class="settings-container">
        <div class="header">
            <h1>Account Settings</h1>
        </div>

        <div class="settings-categories">
            <div class="category-card" id="usernameChange">
                <div class="icon"><i class="fas fa-user-circle"></i></div>
                <div class="category-title">Change Username</div>
            </div>

            <div class="category-card" id="emailChange">
                <div class="icon"><i class="fas fa-envelope"></i></div>
                <div class="category-title">Change Email</div>
            </div>

            <div class="category-card" id="passwordChange">
                <div class="icon"><i class="fas fa-lock"></i></div>
                <div class="category-title">Change Password</div>
            </div>

            <form method="POST" class="logout-form">
                <button class="logout-btn" name="logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal" id="usernameModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Change Username</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="New Username" required>
                <button type="submit" name="update_username" class="submit-btn">Update Username</button>
            </form>
        </div>
    </div>

    <div class="modal" id="emailModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Change Email</h2>
            <form method="POST">
                <input type="email" name="email" placeholder="New Email" required>
                <button type="submit" name="update_email" class="submit-btn">Update Email</button>
            </form>
        </div>
    </div>

    <div class="modal" id="passwordModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Change Password</h2>
            <form method="POST">
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" name="update_password" class="submit-btn">Update Password</button>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast <?= $toastType ?>">
        <i class="icon"></i>
        <span id="toastMessage"><?= htmlspecialchars($toastMessage) ?></span>
    </div>

    <script>
        // Modal controls
        const usernameModal = document.getElementById('usernameModal');
        const emailModal = document.getElementById('emailModal');
        const passwordModal = document.getElementById('passwordModal');

        document.getElementById('usernameChange').addEventListener('click', () => usernameModal.style.display = 'flex');
        document.getElementById('emailChange').addEventListener('click', () => emailModal.style.display = 'flex');
        document.getElementById('passwordChange').addEventListener('click', () => passwordModal.style.display = 'flex');

        document.querySelectorAll('.close').forEach(close => {
            close.addEventListener('click', () => {
                usernameModal.style.display = 'none';
                emailModal.style.display = 'none';
                passwordModal.style.display = 'none';
            });
        });

        // Toast Notification
        const toastMessage = "<?= $toastMessage ?>";
        if (toastMessage) {
            const toast = document.getElementById('toast');
            toast.style.display = 'flex';
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.style.display = 'none', 500);
            }, 3000);
        }
    </script>

</body>
</html>
