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
$responses = array();

foreach ($questions as $key => $value) {
    $order = $key + 1;
    if ($stmt = $conn->prepare(
        "SELECT `value`
        FROM `responses` 
        WHERE `survey_id` = ? AND `order` = ?"
    )) {
        $stmt->bind_param('ii', $data['survey_id'], $order);
        $stmt->execute();
        $rs = $stmt->get_result();

        while ($row = $rs->fetch_assoc()) {
            $temp = array();
            array_push(
                $temp,
                new Answer($row['order'], $row['value'])
            );
        }
    }
    array_push($responses, $temp);
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
