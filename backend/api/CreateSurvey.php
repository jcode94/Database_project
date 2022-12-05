<?php
header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents("php://input"), true);

define('__BACKEND_ROOT__', $_SERVER['DOCUMENT_ROOT'] . '/backend');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/Config.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/dao/DBConnection.php');

// insert survey into table surveys_metadata, get back its ID
$conn = new DBConnection(new Config());
$stmt = $conn->prepare(
    file_get_contents(__BACKEND_ROOT__ . '/SQL/INSERT_INTO_SURVEYS_METADATA.sql')
);

$stmt->bind_param(
    'sssssi',
    $a,
    $t,
    $d,
    $s,
    $e,
    $nq
);

$a = $data["author"] ?? "";
$t = $data["title"] ?? "";
$d = $data["description"] ?? "";
$s = $data["startD"] ?? "";
$e = $data["endD"] ?? "";
$nq = $data["numOfQuestions"] ?? 0;

$stmt->execute();

$query = "SELECT IDENT_CURRENT(`survey_project_db.surveys_metadata`)";

$stmt = $conn->prepare($query);

$stmt->execute();

$stmt->bind_result($survey_id);

if (isset($survey)) {
    // INSERT INTO QUESTIONS (survey_id, number, type, statement)
    foreach ($data['questions'] as $index => $question) {
        $stmt = $conn->prepare(
            file_get_contents(__BACKEND_ROOT__ . '/SQL/INSERT_INTO_QUESTIONS.sql')
        );
        $stmt->bind_param(
            "iiis",
            $survey_id,
            $index,
            $question['type'],
            $question['statement']
        );
        if ($stmt->execute()) {
            echo 'Insert questions success.';
        }
    }

    foreach ($data['emails'] as $key => $value) {
        // INSERT INTO PARTICIPANTS (survey_id, email, status)
        $stmt = $conn->prepare(
            file_get_contents(__BACKEND_ROOT__ . '/SQL/INSERT_INTO_PARTICIPANTS.sql')
        );
        $stmt->bind_param("is", $survey_id, $value);
        if ($stmt->execute()) {
            echo 'Insert participants success.';
        }

        // SET UP DEFAULT RESPONSES(save state)
        $stmt = $conn->prepare(
            file_get_contents(__BACKEND_ROOT__ . '/SQL/INSERT_INTO_RESPONSES.sql')
        );
        $order = $key + 1;
        $stmt->bind_param("isis", $survey_id, $value, $order);
        if ($stmt->execute()) {
            echo 'Insert default responses success.';
        }
    }
    echo 'Creation Success!';
    exit;
} else {
    echo 'Unable to save survey T-T';
    exit;
}
