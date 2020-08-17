<?php

// Start output buffering
ob_start();

// Parse request URI
$REQUEST_URI = substr($_SERVER['REQUEST_URI'], strlen(CONFIG['root']));

// Add default breadcrumb
Breadcrumbs::add('Sniffle', '');

// Prepare header
Header::setInfo('Framework');
Header::requireGlobal();
Header::requireBootstrap();
Header::requireFontAwesome();
