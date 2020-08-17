<?php

// Index route
Route::GET('//', function() {
	include(__DIR__ . '/app/Index.php');
}, false);


// License route
Route::GET('/license\\//', function() {
	echo file_get_contents(__DIR__ . '/../LICENSE');
}, false);
