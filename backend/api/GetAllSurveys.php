<?php

define('__BACKEND_ROOT__', $_SERVER['DOCUMENT_ROOT'] . '/backend');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/Config.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/dao/DBConnection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Survey.php');

$data = json_decode(file_get_contents("php://input"), true);
$conn = new DBConnection(new Config());

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

// Get all surveys for which email is eq to author col in surveys metadata
function getAuthoredSurveyMetadata($conn, $data)
{
    if ($stmt = $conn->prepare(
        "SELECT `survey_id`
        FROM `surveys_metadata` 
        WHERE `author` = ?"
    )) {
        $stmt->bind_param('s', $data['email']);
        $stmt->execute();
        $rs = $stmt->get_result();
        $survey_metadata = array();
        while ($row = $rs->fetch_assoc()) {
            array_push(
                $survey_metadata,
                getMetaData($conn, $row)
            );
        }
    }
    return $survey_metadata;
}

$authored = getAuthoredSurveyMetadata($conn, $data) ?? array();

// Get all surveys for which email is participant
function getParticipantSurveyMetadata($conn, $data)
{
    if ($stmt = $conn->prepare(
        "SELECT `survey_id`
        FROM `participants` 
        WHERE `email` = ?"
    )) {
        $stmt->bind_param('s', $data['email']);
        $stmt->execute();
        $rs = $stmt->get_result();
        $participant_surveys = array();
        while ($row = $rs->fetch_assoc()) {
            $stmt = $conn->prepare(
                "SELECT `status`
                FROM `participants`
                WHERE `survey_id` = ?
                AND `email` = ?"
            );
            $stmt->bind_param('is', $row['survey_id'], $data['email']);
            $stmt->execute();
            $status = $stmt->fetch();
            $metadata = [getMetaData($conn, $row), 'status' => $status];
            array_push(
                $participant_surveys,
                $metadata
            );
        }
    }
    return $participant_surveys;
}

$participant = getParticipantSurveyMetadata($conn, $data) ?? array();

$ret = ['authored' => $authored, 'participant' => $participant];

echo json_encode($ret);
