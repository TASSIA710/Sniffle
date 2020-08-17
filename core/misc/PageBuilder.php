<?php

// Get buffered output
$output = ob_get_contents();
ob_end_clean();

// Build the page
echo $output;