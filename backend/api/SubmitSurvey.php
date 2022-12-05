<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/backend/models/Constants.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../config/Config.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/backend/dao/DBConnection.php');

$data = json_decode(file_get_contents("php://input"), true);

"UPDATE `responses`
SET "