<?php

const ERRORS = [

	STATUS_400 => [
		'client_error' => true,
		'error_code' => 400,
		'error_name' => 'Bad Request',
		'document_error' => 'Error',
		'error_message' => <<<EOT
			The server cannot or will not process your request due to something that is perceived to be a client error
			(e.g., malformed request syntax, invalid request message framing, or deceptive request routing).
		EOT,
	],

	STATUS_401 => [
		'client_error' => true,
		'error_code' => 401,
		'error_name' => 'Unauthorized',
		'document_error' => 'Restricted',
		'error_message' => <<<EOT
			The request has not been applied because it lacks valid authentication credentials for the target resource.
		EOT,
	],

	STATUS_402 => [
		'client_error' => true,
		'error_code' => 402,
		'error_name' => 'Payment Required',
		'document_error' => 'Restricted',
		'error_message' => <<<EOT
			Officially, this error code is reserved for future use. Otherwise, this might indicate that you haven't
			yet received access for the given resource. Access may be received against payment.
		EOT,
	],

	STATUS_403 => [
		'client_error' => true,
		'error_code' => 403,
		'error_name' => 'Forbidden',
		'document_error' => 'Restricted',
		'error_message' => <<<EOT
			The server understood your request but refuses to authorize it.
			Maybe you aren't logged in, or you are logged in but don't have enough access? Or you are logged in
			and try to access a feature only available to guests (e.g. login).
		EOT,
	],

	STATUS_404 => [
		'client_error' => true,
		'error_code' => 404,
		'error_name' => 'Not Found',
		'document_error' => 'Not Found',
		'error_message' => <<<EOT
			The resource you tried to access was not found on this server. It may have either been renamed or deleted,
			or never existed in the first place. Sometimes, a 404 error is also thrown if a private resource is trying
			to be accessed, to prevent it's existance from being compromised.
		EOT,
	],

	STATUS_405 => [
		'client_error' => true,
		'error_code' => 405,
		'error_name' => 'Method Not Allowed',
		'document_error' => 'Not Found',
		'error_message' => <<<EOT
			The current request method is not allowed on the attempted resource.
		EOT,
	],

	STATUS_500 => [
		'client_error' => false,
		'error_code' => 500,
		'error_name' => 'Internal Server Error',
		'server_error' => 'Error',
		'error_message' => <<<EOT
			The server encountered an unexpected condition that prevented it from fulfilling your request.
		EOT,
	],

	STATUS_501 => [
		'client_error' => false,
		'error_code' => 501,
		'error_name' => 'Not Implemented',
		'server_error' => 'Error',
		'error_message' => <<<EOT
			The server does not support the functionality required to fulfill the request.
		EOT,
	],

	STATUS_502 => [
		'client_error' => false,
		'error_code' => 502,
		'error_name' => 'Bad Gateway',
		'server_error' => 'Error',
		'error_message' => <<<EOT
			The server, while acting as a gateway or proxy, received an invalid response from an inbound server
			it accessed while attempting to fulfill the request.
		EOT,
	],

];
