<?php

// Did a route match?
if (!Route::$PATH_FOUND) {
	$STATUS_CODE = 404;
}


// Build response
http_response_code($STATUS_CODE);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: max-age=0, private, must-revalidate');
header('X-GAMMA-APIVersion: gamma.v1');
header('X-RayID: ' . Utility::getRayID());

echo json_encode($RESPONSE);
