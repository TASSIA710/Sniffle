<?php

class AuthManager {
	private static $currentUser, $currentSession;

	/* Initialize */
	public static function initialize() {
		if (IS_API) {
			if (!isset($_SERVER['HTTP_AUTHORIZATION'])) return;
			$auth = $_SERVER['HTTP_AUTHORIZATION'];

			if (Utility::startsWith($auth, 'Session')) {
				$session = Database::getSession(substr($auth, strlen('Session ')));
				if ($session == null) return; // TODO: Handle this error

				$account = $session->getAccount();
				if ($account == null) return; // TODO: Handle this error

				self::$currentSession = $session;
				self::$currentUser = $account;
				return;
			}

			// TODO: Auth via Token/APIKey

		} else {
			$token = Cookies::getCookie('session');
			if ($token == null) return;

			$session = Database::getSession($token);
			if ($session == null) return; // TODO: Handle this error

			$account = $session->getAccount();
			if ($account == null) return; // TODO: Handle this error

			$session->setLastLogin(time(), true);
			$session->setLastIP($_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'], true);
			$session->setUserAgent($_SERVER['HTTP_USER_AGENT'], true);
			$session->pushDB();

			$account->setLastVisit(time(), true);
			$account->setLastIP($_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'], true);
			$account->pushDB();

			self::$currentSession = $session;
			self::$currentUser = $account;
		}
	}
	/* Initialize */



	/* Getters */
	public static function getCurrentUser() {
		return self::$currentUser;
	}

	public static function getCurrentSession() {
		return self::$currentSession;
	}

	public static function hasPermission($permission) {
		if (self::isLoggedIn()) {
			return self::getCurrentUser()->getGroup()->hasPermission($permission);
		} else {
			// TODO: Implement guest group
			return false;
		}
	}
	/* Getters */



	/* Login & Logout */
	public static function isLoggedIn() {
		return self::getCurrentUser() != null;
	}

	public static function login($username, $password) {
		$account = Database::getAccountByName($username);
		if ($account == null) return false;

		if (!password_verify($password, $account->getPassword())) return false;
		return self::loginTo($account);
	}

	public static function loginTo($account) {
		$token = Utility::generateSessionToken();
		$session = Database::createSession($token, $account->getID());

		self::$currentUser = $account;
		self::$currentSession = $session;
		if (!IS_API) Cookies::setCookie('session', $token);
		return $token;
	}

	public static function logout() {
		if (self::isLoggedIn()) Database::dropSession(self::getCurrentSession()->getToken());
		Cookies::removeCookie('session');
		self::$currentUser = null;
		self::$currentSession = null;
	}
	/* Login & Logout */



	/* Create Account */
	public static function createAccount($username, $password) {
		return Database::createAccount($username, password_hash($password, PASSWORD_DEFAULT));
	}
	/* Create Account */

}
