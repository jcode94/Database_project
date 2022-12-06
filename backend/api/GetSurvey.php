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


// Create composite return from the results sets of the following:
function getMetaData($conn, $survey_id)
{
    if ($stmt = $conn->prepare(
        "SELECT `title`, `description`, `start_date`, `end_date`, `number_of_questions`
        FROM `surveys_metadata` 
        WHERE `survey_id` = ?"
    )) {
        $stmt->bind_param('i', $survey_id);
        $stmt->execute();
        $rs = $stmt->get_result();
        $metadata = $rs->fetch_assoc();
    }
    echo json_encode($metadata);
    return $metadata;
}

$metadata = getMetaData($conn, $data);

function getQuestions($conn, $data)
{
    if ($stmt = $conn->prepare(
        "SELECT *
        FROM `questions` 
        WHERE `survey_id` = ?"
    )) {
        $stmt->bind_param('i', $data['survey_id']);
        $stmt->execute();
        $rs = $stmt->get_result();
        $questions = array();
        while ($row = $rs->fetch_assoc()) {
            array_push(
                $questions,
                new Question($row['order'], $row['type'], $row['statement'])
            );
        }
    }
    return $questions;
}

$questions = getQuestions($conn, $data);

function getResponses($conn, $data)
{
    if ($stmt = $conn->prepare(
        "SELECT `order`, `value`
        FROM `responses` 
        WHERE `survey_id` = ? AND `email` = ?"
    )) {
        $stmt->bind_param('is', $data['survey_id'], $data['email']);
        $stmt->execute();
        $rs = $stmt->get_result();
        $responses = array();
        while ($row = $rs->fetch_assoc()) {
            array_push(
                $responses,
                new Answer($row['order'], $row['value'])
            );
        }
    }
    return $responses;
}

$responses = getResponses($conn, $data);

echo json_encode([
    "survey_id" => $survey_id,
    "description" => $metadata['description'],
    "title" => $metadata['title'],
    "participant_email" => $participant_email,
    "startDate" => $startDate,
    "endDate" => $endDate,
    "number_of_questions" => $metadata['number_of_questions'],
    "questions" => $questions,
    "responses" => $responses
]);
