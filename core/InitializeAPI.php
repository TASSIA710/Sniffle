<?php

// Load crucial
const IS_API = true;
include(__DIR__ . '/Constants.php');
include(__DIR__ . '/../Configuration.php');


// Load classes
include(__DIR__ . '/class/Account.php');
include(__DIR__ . '/class/AuthManager.php');
include(__DIR__ . '/class/Database.php');
include(__DIR__ . '/class/Group.php');
include(__DIR__ . '/class/Route.php');
include(__DIR__ . '/class/Session.php');
include(__DIR__ . '/class/Utility.php');


// Load misc
include(__DIR__ . '/misc/LoaderAPI.php');


// Initialize
Database::connect();
AuthManager::initialize();


// Load modules
include(__DIR__ . '/../app/InitAPI.php');

// Load cleanup
include(__DIR__ . '/misc/PostLoaderAPI.php');
