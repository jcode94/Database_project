<?php
require_once(__DIR__ . '/../../../config/Config.class.php');
define('__BACKEND_ROOT__', $_SERVER['DOCUMENT_ROOT'].'/backend');
require_once(__BACKEND_ROOT__.'/dao/DBConnection.php');
$_POST = json_decode(file_get_contents("php://input"), true);

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$db_config = new Config();
$conn = new DBConnection($db_config);

if (empty($email) || empty($password)) {
    echo 'empty field';
    exit;
} else {
    $query = "SELECT `email` FROM `users` where `email` = $email";
    $rs = mysqli_query($conn, $query);
    if (mysqli_num_rows($rs) > 0) {
        echo 'already exists';
        exit;
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