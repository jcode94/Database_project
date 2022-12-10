<?php
$data = json_decode(file_get_contents("php://input"), true);
require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/Config.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/dao/DBConnection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Answer.php');
$conn = new DBConnection(new Config());

$metadata = $conn->getMetaData($data);
$questions = $conn->getQuestions($data);
$responses = array();

for ($i = 1; $i <= count($questions); ++$i) {
    $responsesForQuestionNumber = $conn->getResponsesForOrder($data, $order);
    array_push($responses, $responsesForQuestionNumber);
}

echo json_encode([
    "survey_id" => $data['survey_id'] ?? "",
    "description" => $metadata['description'] ?? "",
    "title" => $metadata['title'] ?? "",
    "startDate" => $metadata['start_date'] ?? "",
    "endDate" => $metadata['end_date'] ?? "",
    "questions" => $questions ?? "",
    "responses" => $responses ?? ""
]);
