<?php
define('__BACKEND_ROOT__', $_SERVER['DOCUMENT_ROOT'] . '/backend');
require_once(dirname(__FILE__) . '/GetSurvey.php');

$data = json_decode(file_get_contents("php://input"), true);
$conn = new DBConnection(new Config());

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
}

$authored = getAuthoredSurveyMetadata($conn, $data);

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

$participant = getParticipantSurveyMetadata($conn, $data);

$ret = ['authored' => $authored, 'participant' => $participant];

echo json_encode($ret);
