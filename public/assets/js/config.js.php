<?php
include(__DIR__ . '/../../../Configuration.php');
header('Content-Type: text/javascript');

echo 'const ROOT = \'' . CONFIG['root'] . '\';';
echo 'const PROXY = \'' . CONFIG['proxy'] . '\';';
echo 'const SYSTEM_ADMINISTRATOR = \'' . CONFIG['system_administrator'] . '\';';
?>