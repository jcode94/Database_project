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

$continue = $stmt->execute();

$rs = $conn->query("SELECT `survey_id` FROM `surveys_metadata` ORDER BY `survey_id` DESC LIMIT 1");
$survey_id = $rs->fetch_row()[0];

if (isset($continue)) {
    // INSERT INTO QUESTIONS (survey_id, number, type, statement)
    foreach ($data['questions'] as $index => $question) {
        $stmt = $conn->prepare(
            file_get_contents(__BACKEND_ROOT__ . '/SQL/INSERT_INTO_QUESTIONS.sql')
        );
        $stmt->bind_param(
            "iiis",
            $survey_id,
            $question['number'],
            $question['type'],
            $question['statement']
        );
        if ($stmt->execute()) {
            echo 'Insert questions success.';
        } else {
            echo json_encode(["valid" => "alr entered this record"]);
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
    echo json_encode(["valid" => "valid"]);
    exit;
}
