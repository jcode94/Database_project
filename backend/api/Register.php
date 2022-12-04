<?php

define('__BACKEND_ROOT__', $_SERVER['DOCUMENT_ROOT'].'/backend');
require_once(__BACKEND_ROOT__.'/dao/DBConnection.php');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$conn = new DBConnection();

if (empty($email) || empty($password)) {
    echo 'empty field';
} else {
    $query = "SELECT `email` FROM `users` where `email` = $email";
    $rs = $conn->query($query);
    if (mysqli_num_rows($rs) > 0) {
        echo 'already exists';
    } else {
        if ($_server["REQUEST_METHOD"] == "POST") {
            $stmt = $conn->prepare(file_get_contents(__BACKEND_ROOT__.'/SQL/INSERT_NEW_USER.sql'));
            $stmt->bind_param("is", $email, $password);
            if($stmt->execute()) {
                echo 'success';
                exit;
            } 
        }
    }
}

echo 'fail';
?>