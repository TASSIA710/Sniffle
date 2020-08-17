<?php

// Parse request URI
$REQUEST_URI = substr($_SERVER['REQUEST_URI'], strlen(CONFIG['root'] . 'api/v1/'));


// Parse input
$REQUEST = json_decode(file_get_contents('php://input'));


// Prepare output
$RESPONSE = new stdClass();
$STATUS_CODE = 200;
