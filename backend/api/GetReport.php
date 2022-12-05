<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/api/GetSurvey.php');
$data = json_decode(file_get_contents("php://input"), true);
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/dao/DBConnection.php');
$conn = new DBConnection(new Config());

$metadata = getMetaData($conn, $data);
$questions = getQuestions($conn, $data);
$responses = array();

if ($stmt = $conn->prepare("SELECT `order`, `value`
    FROM `responses` 
    WHERE `survey_id` = ?")) {
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

$stmt->prepare($query);

$report = new Report(
    $data['survey_id'],
    $metadata['title'],
    $metadata['description'],
    $metadata['startDate'],
    $metadata['endDate'],
    $questions,
    $responses
);

echo json_encode($report);
