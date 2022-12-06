<?php
$data = json_decode(file_get_contents("php://input"), true);
require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/Config.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/dao/DBConnection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Answer.php');
$conn = new DBConnection(new Config());

function getMetaData($conn, $data)
{
    if ($stmt = $conn->prepare(
        "SELECT `title`, `description`, `start_date`, `end_date`, `number_of_questions`
        FROM `surveys_metadata` 
        WHERE `survey_id` = ?"
    )) {
        $stmt->bind_param('i', $data['survey_id']);
        $stmt->execute();
        $rs = $stmt->get_result();
        $metadata = $rs->fetch_assoc();
    }
    return $metadata;
}

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
                array(
                    "order" => $row['order'],
                    "type" => $row['type'],
                    "statement" => $row['statement']
                )
            );
        }
    }
    return $questions;
}

$metadata = getMetaData($conn, $data);
$questions = getQuestions($conn, $data);
$responses = getResponses($conn, $data);

if ($stmt = $conn->prepare(
    "SELECT `order`, `value`
    FROM `responses` 
    WHERE `survey_id` = ?"
)) {
    $stmt->bind_param('i', $data['survey_id']);
    $stmt->execute();
    $rs = $stmt->get_result();

    while ($row = $rs->fetch_assoc()) {
        array_push(
            $responses,
            new Answer($row['order'], $row['value'])
        );
    }
}

echo json_encode([
    "survey_id" => $data['survey_id'] ?? "",
    "description" => $metadata['description'] ?? "",
    "title" => $metadata['title'] ?? "",
    "startDate" => $metadata['start_date'] ?? "",
    "endDate" => $metadata['end_date'],
    "questions" => $questions ?? "",
    "responses" => $responses ?? ""
]);
