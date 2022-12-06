<?php

define('__BACKEND_ROOT__', $_SERVER['DOCUMENT_ROOT'] . '/backend');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/Config.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/dao/DBConnection.php');

$data = json_decode(file_get_contents("php://input"), true);
$conn = new DBConnection(new Config());
$survey_id = $data['survey_id'] ?? 0;
$email = $data['email'] ?? "";
$answers = $data['answers'] ?? array();

$query =
    "UPDATE `responses`
    SET `value` = ?
    WHERE `email` = ?
    AND `survey_id` = ?
    AND `order` = ?";

$stmt = $conn->prepare($query);
$answer = $answers[$key];

$num_answers = count($answers);
for ($i = 1; $i <= $num_answers; $i++) {
    $stmt->bind_param('ssii', $answer, $email, $survey_id, $i);
    $stmt->execute();
}

$query = "UPDATE `participants`
    SET `status` = ?
    WHERE `email` = ?
    AND `survey_id` = ?";

$stmt = $conn->prepare($query);
$status = 1;
$stmt->bind_param('isi', $status, $email, $survey_id);
$stmt->execute();
echo json_encode(['valid' => 'valid']);
