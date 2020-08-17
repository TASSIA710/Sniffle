<?php

class Session {
	private $token, $accountid, $lastLogin, $lastIP, $userAgent, $flags;

	/* Constructor */
	public function __construct($token, $accountid, $lastLogin, $lastIP, $userAgent, $flags) {
		$this->token = $token;
		$this->accountid = $accountid;
		$this->lastLogin = $lastLogin;
		$this->lastIP = $lastIP;
		$this->userAgent = $userAgent;
		$this->flags = $flags;
	}

	public static function FromRow($row) {
		return new Session($row['Token'], $row['AccountID'], $row['LastLogin'], $row['LastIP'], $row['UserAgent'], $row['Flags']);
	}

	public static function CreateSession($token, $id) {
		return new Session($token, $id, time(), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], 0);
	}
	/* Constructor */



	/* Generic */
	public function getToken() {
		return $this->token;
	}

	public function getAccountID() {
		return $this->accountid;
	}

	public function getAccount() {
		return Database::getAccount(self::getAccountID());
	}
	/* Generic */



	/* Last Login */
	public function getLastLogin() {
		return $this->lastLogin;
	}

	public function setLastLogin($lastLogin, $noUpdate = false) {
		$this->lastLogin = $lastLogin;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_sessions SET LastLogin = ? WHERE Token = ?;';
		Database::prepare($sql, [$lastLogin, $this->getToken()]);
	}
	/* Last Login */



	/* Last IP */
	public function getLastIP() {
		return $this->lastIP;
	}

	public function setLastIP($lastIP, $noUpdate = false) {
		$this->lastIP = $lastIP;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_sessions SET LastIP = ? WHERE Token = ?;';
		Database::prepare($sql, [$lastIP, $this->getToken()]);
	}
	/* Last IP */



	/* User Agent */
	public function getUserAgent() {
		return $this->userAgent;
	}

	public function setUserAgent($userAgent, $noUpdate = false) {
		$this->userAgent = $userAgent;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_sessions SET UserAgent = ? WHERE Token = ?;';
		Database::prepare($sql, [$userAgent, $this->getToken()]);
	}
	/* User Agent */



	/* Flags */
	public function getFlags() {
		return $this->flags;
	}

	public function setFlags($flags, $noUpdate = false) {
		$this->flags = $flags;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_sessions SET Flags = ? WHERE Token = ?;';
		Database::prepare($sql, [$flags, $this->getToken()]);
	}
	/* Flags */



	/* Push DB */
	public function pushDB() {
		$sql = 'UPDATE gamma_sessions SET LastLogin = ?, LastIP = ?, UserAgent = ?, Flags = ? WHERE Token = ?;';
		Database::prepare($sql, [$this->getLastLogin(), $this->getLastIP(), $this->getUserAgent(), $this->getFlags(), $this->getToken()]);
	}
	/* Push DB */



	/* Pull DB */
	public function pullDB() {
		$sql = 'SELECT * FROM gamma_sessions WHERE Token = ?;';
		$session = null;
		foreach (Database::prepare($sql, [$this->getToken()]) as $row) $session = $row;
		if ($session == null) return false;

		$this->setLastLogin($session['LastLogin'], true);
		$this->setLastIP($session['LastIP'], true);
		$this->setUserAgent($session['UserAgent'], true);
		$this->setFlags($session['Flags'], true);
		return true;
	}
	/* Pull DB */

}
