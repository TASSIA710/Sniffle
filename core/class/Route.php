<?php

class Route {

	/* Methods */
	public static function GET($path, $callback, $requireLogin = true) {
		if ($_SERVER['REQUEST_METHOD'] != 'GET') return;
		self::any($path, $callback, $requireLogin);
	}

	public static function POST($path, $callback, $requireLogin = true) {
		if ($_SERVER['REQUEST_METHOD'] != 'POST') return;
		self::any($path, $callback, $requireLogin);
	}

	public static function PUT($path, $callback, $requireLogin = true) {
		if ($_SERVER['REQUEST_METHOD'] != 'PUT') return;
		self::any($path, $callback, $requireLogin);
	}

	public static function PATCH($path, $callback, $requireLogin = true) {
		if ($_SERVER['REQUEST_METHOD'] != 'PATCH') return;
		self::any($path, $callback, $requireLogin);
	}

	public static function DELETE($path, $callback, $requireLogin = true) {
		if ($_SERVER['REQUEST_METHOD'] != 'DELETE') return;
		self::any($path, $callback, $requireLogin);
	}

	public static function OPTIONS($path, $callback, $requireLogin = true) {
		if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') return;
		self::any($path, $callback, $requireLogin);
	}

	public static function HEAD($path, $callback, $requireLogin = true) {
		if ($_SERVER['REQUEST_METHOD'] != 'HEAD') return;
		self::any($path, $callback, $requireLogin);
	}

	public static function match($methods, $path, $callback, $requireLogin = true) {
		if (!in_array($_SERVER['REQUEST_METHOD'], $methods)) return;
		self::any($path, $callback, $requireLogin);
	}
	/* Methods */



	/* Assets */
	public static function ASSET($path, $document, $content_type) {
		global $REQUEST_URI;
		if ('assets/' . $path != $REQUEST_URI) return;

		ob_end_clean();
		header('Content-Type: ' . $content_type);
		echo file_get_contents($document);
		exit;
	}
	/* Assets */



	/* Redirect */
	public static function redirect($from, $to) {
		// TODO
	}

	public static function redirectPermanent($from, $to) {
		// TODO
	}

	public static function redirectInternal($from, $to) {
		// TODO
	}
	/* Redirect */



	/* Any Method */
	public static $PATH_FOUND = false;

	public static function any($path, $callback, $requireLogin = true) {
		global $REQUEST_URI;
		if (self::$PATH_FOUND) return;

		$matches = [];
		if (preg_match($path, $REQUEST_URI, $matches)) {
			if ($matches[0] != $REQUEST_URI) return;

			if ($requireLogin && !AuthManager::isLoggedIn()) {
				self::$PATH_FOUND = true;
				if (IS_API) {
					global $RESPONSE, $STATUS_CODE;
					$STATUS_CODE = 401;
					$RESPONSE = new stdClass();
					return;
				} else {
					header('Location: ' . CONFIG['root'] . 'auth/login/');
				}
				return;
			}

			self::$PATH_FOUND = true;
			$callback($matches);
		}
	}
	/* Any Method */

}
