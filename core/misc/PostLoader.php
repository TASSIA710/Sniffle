<?php

// Did a route match?
if (!Route::$PATH_FOUND) {
	Utility::showStatus(STATUS_404);
	return;
}
