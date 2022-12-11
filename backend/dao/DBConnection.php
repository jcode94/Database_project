<?php


class DBConnection
{
    private $host;
    private $username;
    private $password;
    private $database;

    protected $connection;

    public function __construct(Config $config)
    {

        if (!isset($this->connection)) {
            $this->host = $config->host;
            $this->username = $config->username;
            $this->password = $config->password;
            $this->database = $config->database;

            $this->connection = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );

            if (!$this->connection) {
                echo 'Cannot connect to database server';
                exit;
            }
        }

        return $this->connection;
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }

    public function prepare($sql)
    {
        return $this->connection->prepare($sql);
    }

    public function __destruct()
    {
        mysqli_close($this->connection);
    }

    public function next_result()
    {
        return $this->connection->next_result();
    }

    // Create composite return from the results sets of the following:
    public function getMetaData($data)
    {
        if ($stmt = $this->connection->prepare(
            "SELECT `survey_id`, `title`, `description`, `start_date`, `end_date`, `number_of_questions`
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
    
    // Get all surveys for which email is eq to author col in surveys metadata
    function getAuthoredSurveyMetadata($data)
    {
        $survey_metadata = array();
        if ($stmt = $this->connection->prepare(
            "SELECT `survey_id`
            FROM `surveys_metadata` 
            WHERE `author` = ?"
        )) {
            $stmt->bind_param('s', $data['email']);
            $stmt->execute();
            $rs = $stmt->get_result();
            while ($row = $rs->fetch_assoc()) {
                array_push(
                    $survey_metadata,
                    $this->getMetaData($row)
                );
            }
        }
        return $survey_metadata;
    }
    
    // Get all surveys for which email is participant
    function getParticipantSurveyMetadata($data)
    {
        if ($stmt = $this->connection->prepare(
            "SELECT `survey_id`
            FROM `participants` 
            WHERE `email` = ?"
        )) {
            $stmt->bind_param('s', $data['email']);
            $stmt->execute();
            $rs = $stmt->get_result();
            $survey_id_list = array();
            while ($row = $rs->fetch_assoc()) {
                array_push(
                    $survey_id_list,
                    $row['survey_id']
                );
            }
            $stmt->close();
            $this->next_result();
        }

        $metadata = array();
        $stmt = $this->connection->prepare(
            "SELECT `status`
            FROM `participants`
            WHERE `survey_id` = ?
            AND `email` = ?"
        );
        foreach ($survey_id_list as $survey_id) {
            $stmt->bind_param('is', $survey_id, $data['email']);
            $stmt->execute();
            $rs = $stmt->get_result();
            $status = $rs->fetch_assoc();
            $stmt->close();
            $this->connection->next_result();
            $temp = $this->getMetaData(array("survey_id" => $survey_id)) ?? array();
            $temp["status"] = $status['status'];
            array_push($metadata, $temp);
        }
        return $metadata;
    }

    public function getQuestions($data)
    {
        if ($stmt = $this->connection->prepare(
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

    public function getResponses($data)
    {
        if ($stmt = $this->connection->prepare(
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
    
    public function getResponsesForOrder($data, $order) {
        if ($stmt = $this->connection->prepare(
            "SELECT `value`
            FROM `responses` 
            WHERE `survey_id` = ? AND `order` = ?"
        )) {
            $stmt->bind_param('ii', $data['survey_id'], $order);
            $stmt->execute();
            $rs = $stmt->get_result();
    
            $temp = array();
            while ($row = $rs->fetch_assoc()) {
                $temp[] = array(
                    "response" => $row['value'],
                    "order" => $order
                );
            }
            $stmt->free_result();
            $stmt->close();
        }
        return $temp;
    }
}
