<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/backend/models/Survey.php');
require_once(CONFIG_DIR.'/Config.class.php');
require_once(WEBROOT.'/backend/dao/DBConnection.php');

$data = json_decode(file_get_contents("php://input"), true);
$title = $data["title"];
$desc = $data["desc"];
$numOfQuestions = $data["numOfQuestions"];
$emails = $data["emails"];
$startD = $data["startD"];
$endD = $data["endD"];
$conn = new DBConnection(new config());
$author = "anon";
$id = "123";
$stmt = $conn->prepare(file_get_contents(__BACKEND_ROOT__.'/SQL/INSERT INTO SURVEYS_METADATA.sql'));
$stmt->bind_param("ss",$id,$author ,$title,$desc, $startD, $endD,$numOfQuestions);
if($stmt->execute()) {
    echo 'Additon Successful.';
    exit;
}


// PHP file ending
?>