<?php
header("Content-type: application/json");
echo json_encode(file_get_contents("frontend/HTML/landing.html"));
