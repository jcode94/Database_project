<?php
define('__BACKEND_ROOT__', $_SERVER['DOCUMENT_ROOT'] . '/backend');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Question.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Answer.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/Config.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/dao/DBConnection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Survey.php');
$conn = new DBConnection(new Config());

$data = json_decode(file_get_contents("php://input"), true);

$survey_id = $data['survey_id'] ?? "";
$participant_email = $data['email'] ?? "";

$metadata = getMetaData($data);
$questions = getQuestions($data);
$responses = getResponses($data);

echo json_encode([
    "survey_id" => $survey_id ?? "",
    "description" => $metadata['description'] ?? "",
    "title" => $metadata['title'] ?? "",
    "participant_email" => $participant_email ?? "",
    "startDate" => $metadata['start_date'] ?? "",
    "endDate" => $metadata['end_date'] ?? "",
    "number_of_questions" => $metadata['number_of_questions'] ?? "",
    "questions" => $questions ?? "",
    "responses" => $responses ?? ""
]);
