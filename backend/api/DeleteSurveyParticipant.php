<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/dao/DBConnection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/Config.class.php');

$data = json_decode(file_get_contents("php://input"), true);
$conn = new DBConnection(new Config());
$survey_id = $data['survey_id'] ?? 0;
$email = $data['email'] ?? "";

// Delete participant responses

$query = "DELETE FROM TABLE `responses` WHERE `survey_id` = ? AND `email` = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('is', $survey_id, $email);
$stmt->execute();
$stmt->free_result();
$conn->next_result();

// delete participant record

$query2 = "DELETE FROM TABLE `participants` WHERE `survey_id` = ? AND `email` = ?";

$stmt = $conn->prepare($query2);
$stmt->bind_param('is', $survey_id, $email);
$stmt->execute();
$stmt->free_result();
$conn->next_result();
