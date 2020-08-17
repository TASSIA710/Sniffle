<?php

// Load crucial
const IS_API = false;
$MOODCLAP_START = microtime(true) * 1000;
include(__DIR__ . '/Constants.php');
include(__DIR__ . '/../Configuration.php');


// Load classes
include(__DIR__ . '/class/Account.php');
include(__DIR__ . '/class/AuthManager.php');
include(__DIR__ . '/class/Breadcrumbs.php');
include(__DIR__ . '/class/Cookies.php');
include(__DIR__ . '/class/Database.php');
include(__DIR__ . '/class/Group.php');
include(__DIR__ . '/class/Header.php');
include(__DIR__ . '/class/Route.php');
include(__DIR__ . '/class/Session.php');
include(__DIR__ . '/class/Utility.php');


// Load data resources
include(__DIR__ . '/data/Errors.php');


// Load misc
include(__DIR__ . '/misc/Cleanup.php');
include(__DIR__ . '/misc/Loader.php');


// Initialize
Database::connect();
AuthManager::initialize();


// Load app
include(__DIR__ . '/../app/Init.php');

// Load cleanup
include(__DIR__ . '/misc/PostLoader.php');
include(__DIR__ . '/misc/PageBuilder.php');
