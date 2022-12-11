<?php

define('__BACKEND_ROOT__', $_SERVER['DOCUMENT_ROOT'] . '/backend');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/Config.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/dao/DBConnection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/backend/models/Survey.php');

$data = json_decode(file_get_contents("php://input"), true);
$conn = new DBConnection(new Config());

$authored = $conn->getAuthoredSurveyMetadata($data) ?? array();
$participant = $conn->getParticipantSurveyMetadata($data) ?? array();

$ret = ['authored' => $authored, 'participant' => $participant];

echo json_encode($ret);
