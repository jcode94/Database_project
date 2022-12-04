<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/backend/models/Constants.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../config/Config.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/backend/dao/DBConnection.php');

$data = json_decode(file_get_contents("php://input"), true);
$email = $data["email"];
$password = $data["password"];
$conn = new DBConnection(new Config());

if (empty($email) || empty($password)) {
    echo 'Email or password blank.';
    exit;
} else {
    $query = "SELECT `email` FROM `users` where `email` = '$email'";
    $rs = $conn->query($query);
    if (mysqli_num_rows($rs) > 0) {
        echo 'Email already exists.';
        exit;
    } else {
        $stmt = $conn->prepare(file_get_contents(__BACKEND_ROOT__.'/SQL/INSERT_NEW_USER.sql'));
        $stmt->bind_param("ss", $email, $password);
        if($stmt->execute()) {
            echo 'Register success.';
            exit;
        }
    }
}
?>