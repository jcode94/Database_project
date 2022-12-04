<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/backend/models/Constants.php');

$data = json_decode(file_get_contents("php://input"), true);

// insert survey into table surveys_metadata, get back its ID
$conn = new DBConnection(new Config());
$numOfQuestions = count($data['questions']);
$stmt = $conn->prepare(
    file_get_contents(__BACKEND_ROOT__.'/SQL/INSERT_INTO_SURVEYS_METADATA.sql')
);
$stmt->bind_param("sssssi", 
    $data['author'], 
    $data['title'],
    $data['description'],
    $data['startD'],
    $data['endD'],
    $numOfQuestions);
$stmt->execute();
$stmt->bind_result($survey_id);
$stmt_fetch();

if (isset($survey)) {
    foreach($data['emails'] as $key=>$value) {
        // INSERT INTO PARTICIPANTS (survey_id, email, status)
        $stmt = $conn->prepare(
            file_get_contents(__BACKEND_ROOT__.'/SQL/INSERT_INTO_PARTICIPANTS.sql')
        )
        $stmt->bind_param("is", $survey_id, $value);
        $stmt->execute();
        
        // SET UP DEFAULT RESPONSES(save state)
        $stmt = $conn->prepare(
            file_get_contents(__BACKEND_ROOT__.'/SQL/INSERT_INTO_RESPONSES.sql')
        )
        $stmt->bind_param("isis", $survey_id, $value, $key+1);
        $stmt->execute();
    }
    echo 'Creation Success!';
    exit;
} else {
    echo 'Unable to save survey T-T';
    exit;
}
?>