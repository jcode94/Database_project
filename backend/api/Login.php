<?php
$email = $_POST['email'];
$password = $_POST['password'];
$conn = new DBConnection();

if (empty($email) || empty($password)) {
    echo 'empty field';
    exit;
} else {
    if ($stmt = $conn->prepare("SELECT `uid`, `password` FROM `users` WHERE `email` = ?")) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($uid, $password);
            $stmt->fetch();
            if ($_POST['password'] === $password)) {
                // Verification success! User has logged-in!
                // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['uid'] = $uid;
                echo 'success';
                exit;
            }
        }
    }
}
?>
