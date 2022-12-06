<?php

define('__BACKEND_ROOT__', $_SERVER['DOCUMENT_ROOT'] . '/backend');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/Config.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/dao/DBConnection.php');

$data = json_decode(file_get_contents("php://input"), true);
$conn = new DBConnection(new Config());
$survey_id = $data['survey_id'] ?? 0;
$answers = $data['answers'] ?? array();
$email = $data['email'] ?? "";

foreach ($answers as $key => $value) {
    $query =
        "UPDATE `responses`
    SET `value` = ?
    WHERE `email` = ?
    AND `survey_id` = ?
    AND `order` = ?";
    $stmt = $conn->prepare($query);
    $order = $key + 1;
    $answer = $answers[$key];
    $stmt->bind_param('ssii', $answer, $email, $survey_id, $order);

    $stmt->execute();
}

$query = "UPDATE `participants`
    SET `status` = ?
    WHERE `email` = ?
    AND `survey_id` = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('isi', 1, $email, $survey_id);

echo json_encode(['valid' => 'valid']);
