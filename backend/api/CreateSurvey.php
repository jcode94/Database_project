<?php
header('Content-Type: application/json; charset=utf-8');
define('__BACKEND_ROOT__', $_SERVER['DOCUMENT_ROOT'] . '/backend');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/Config.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/dao/DBConnection.php');

$data = json_decode(file_get_contents("php://input"), true);

$a = $data["author"] ?? "";
$t = $data["title"] ?? "";
$d = $data["description"] ?? "";
$s = $data["startD"] ?? "";
$e = $data["endD"] ?? "";
$nq = $data["numOfQuestions"] ?? 0;

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

if ($stmt->execute()) {
    $rs = $conn->query("SELECT `survey_id` FROM `surveys_metadata` ORDER BY `survey_id` DESC LIMIT 1");
    $survey_id = $rs->fetch_assoc()['survey_id'];
    // INSERT INTO QUESTIONS (survey_id, number, type, statement)
    $stmt = $conn->prepare(
        file_get_contents(__BACKEND_ROOT__ . '/SQL/INSERT_INTO_QUESTIONS.sql')
    );
    foreach ($data['questions'] as $index => $question) {
        $order = $question['number'] ?? "";
        $type = $question['type'] ?? "";
        $statement = $question['statement'] ?? "";

        $stmt->bind_param(
            "iiis",
            $survey_id,
            $order,
            $type,
            $statement
        );

        if (!$stmt->execute()) {
            echo json_encode(["valid" => "invalid q"]);
            exit;
        }
    }
    
    $email_stmt = $conn->prepare(
        file_get_contents(__BACKEND_ROOT__ . '/SQL/INSERT_INTO_PARTICIPANTS.sql')
    );
    $responses_stmt = $conn->prepare(
        file_get_contents(__BACKEND_ROOT__ . '/SQL/INSERT_INTO_RESPONSES.sql')
    );
    
    $emails = $data['emails'] ?? array();
    foreach ($emails as $key => $value) {
        // INSERT INTO PARTICIPANTS (survey_id, email, status)
        $email_stmt->bind_param("is", $survey_id, $email);
        $email = $value ?? "";
        if (!$email_stmt->execute()) {
            echo json_encode(["valid" => "invalid participant"]);
            exit;
        }

        for ($i = 1; $i <= $nq; $i++) {
            // SET UP DEFAULT RESPONSES(save state)
            $response = "";
            $responses_stmt->bind_param("isis", $survey_id, $email, $i, $response);

            if (!$responses_stmt->execute()) {
                echo json_encode(["valid" => "invalid response"]);
                exit;
            }
            $responses_stmt->close();
            $conn->next_result();
        }
    }
    echo json_encode(["valid" => "valid"]);
    exit;
} else {
    echo json_encode(["valid" => "invalid survey_metadata"]);
    exit;
}
