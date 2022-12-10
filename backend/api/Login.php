<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/Config.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/dao/DBConnection.php');

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"] ?? "";
$password = $data["password"] ?? "";
$conn = new DBConnection(new Config());
$query = "SELECT `uid`, `password` FROM `users` WHERE `email` = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($uid, $pw);
        $stmt->fetch();
        if ($password === $pw) {
            session_start();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['email'] = $email;
            $_SESSION['uid'] = $uid;
            echo json_encode(["valid" => "valid"]);
            exit;
        }
    } else {
        echo json_encode(["valid" => "Email Not Found"]);
        exit;
    }
}
