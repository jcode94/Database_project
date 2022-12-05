<?php

$data = json_decode(file_get_contents("php://input"), true);

$survey_id = $data['survey_id'];
$participant_email = $data['email'];


// Create composite return from the results sets of the following:
function getMetaData($conn, $data)
{
    if ($stmt = $conn->prepare(
        "SELECT `title`, `description`, `start_date`, `end_date`
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
            $questions[] = new Question($row['order'], $row['type'], $row['statement']);
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
        $row = $rs->fetch_assoc();
        $responses[] = new Answer($row['order'], $row['value']);
    }
    return $responses;
}

$responses = getResponses($conn, $data);

// return survey details, all questions, all responses
$survey = new Survey(
    $survey_id,
    $participant_email,
    $metadata['title'],
    $metadata['desc'],
    $metadata['startDate'],
    $metadata['endDate'],
    count($questions),
    $questions,
    $responses
);

echo json_encode($survey);
