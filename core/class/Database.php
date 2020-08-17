<?php

class Database {
	private static $cachedSessions = [];
	private static $cachedAccounts = [];
	private static $cachedGroups = null;
	private static $db = null;
	private static $queryCount = 0;
	private static $queryTime = 0;

	public static $queryHistory = [];



	/* Generic */
	public static function connect() {
		self::$db = new PDO('mysql:dbname=' . CONFIG['db_database'] . ';host=' . CONFIG['db_hostname'] . ':' . CONFIG['db_port'],
				CONFIG['db_username'], CONFIG['db_password']);
	}

	public static function query($sql) {
		$start = microtime(true) * 1000;

		if (CONFIG['debug_log_sql']) {
			self::$queryHistory[count(self::$queryHistory)] = $sql;
		}

		$res = self::$db->query($sql);
		$end = microtime(true) * 1000;

		self::$queryTime += ($end - $start);
		self::$queryCount++;
		return $res;
	}

	public static function prepare($sql, $data) {
		$start = microtime(true) * 1000;

		if (CONFIG['debug_log_sql']) {
			$sql2 = $sql;
			foreach ($data as $e) {
				if (is_numeric($e)) {
					$sql2 = Utility::replaceFirst('?', $e, $sql2);
				} else {
					$sql2 = Utility::replaceFirst('?', '\'' . $e . '\'', $sql2);
				}
			}
			self::$queryHistory[count(self::$queryHistory)] = $sql2;
		}

		$q = self::$db->prepare($sql);
		$q->execute($data);
		$res = $q->fetchAll();
		$end = microtime(true) * 1000;

		self::$queryTime += ($end - $start);
		self::$queryCount++;
		return $res;
	}

	public static function escape($str) {
		return substr(self::$db->quote($str), 1, -1);
	}

	public static function lastInsert() {
		return self::$db->lastInsertId();
	}
	/* Generic */





	/* Accounts */
	public static function getAccount($id) {
		if (isset(self::$cachedAccounts[$id])) return self::$cachedAccounts[$id];
		$sql = 'SELECT * FROM gamma_accounts WHERE AccountID = ?;';
		$account = null;
		foreach (self::prepare($sql, [$id]) as $row) $account = $row;
		if ($account == null) return null;

		$account = Account::FromRow($account);
		self::$cachedAccounts[$id] = $account;
		return $account;
	}

	public static function getAccountByName($name) {
		$sql = 'SELECT * FROM gamma_accounts WHERE Username = ?;';
		$account = null;
		foreach (self::prepare($sql, [$name]) as $row) $account = $row;
		if ($account == null) return null;

		$account = Account::FromRow($account);
		self::$cachedAccounts[$account->getID()] = $account;
		return $account;
	}

	public static function createAccount($username, $password) {
		$ip = $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'];
		$sql = 'INSERT INTO gamma_accounts (Username, Password, FirstVisit, LastVisit, FirstIP, LastIP) VALUES (?, ?, ?, ?, ?, ?);';
		self::prepare($sql, [$username, $password, time(), time(), $ip, $ip]);
		return self::lastInsert();
	}
	/* Accounts */





	/* Groups */
	private static function cacheGroups() {
		self::$cachedGroups = [];
		$sql = 'SELECT * FROM gamma_groups ORDER BY SortDisplay;';
		foreach (self::query($sql) as $row) {
			$group = Group::FromRow($row);
			self::$cachedGroups[$group->getID()] = $group;
		}
	}

	public static function getGroup($id) {
		if (self::$cachedGroups == null) self::cacheGroups();
		return isset(self::$cachedGroups[$id]) ? self::$cachedGroups[$id] : null;
	}

	public static function getGroupByNameID($id) {
		if (self::$cachedGroups == null) self::cacheGroups();
		foreach (self::$cachedGroups as $group) {
			if ($group->getNameID() == $id) return $group;
		}
		return null;
	}

	public static function getGroupByName($name) {
		if (self::$cachedGroups == null) self::cacheGroups();
		foreach (self::$cachedGroups as $group) {
			if ($group->getName() == $name) return $group;
		}
		return null;
	}

	public static function getAllGroups() {
		if (self::$cachedGroups == null) self::cacheGroups();
		return self::$cachedGroups;
	}

	public static function createGroup($name, $nameID) {
	    // Find sort display
        $sort_display = 0;
        foreach (self::getAllGroups() as $group) {
            if ($sort_display > $group->getSortDisplay()) continue;
            $sort_display = $group->getSortDisplay() + 1;
        }

        // Find sort permission
        $sort_permission = 0;
        foreach (self::getAllGroups() as $group) {
            if ($sort_permission > $group->getSortPermission()) continue;
            $sort_permission = $group->getSortPermission() + 1;
        }

        // Create the group
	    $sql = 'INSERT INTO gamma_groups (GroupNameID, GroupName, Description, Permissions, SortDisplay, SortPermission) VALUES (?, ?, ?, ?, ?, ?);';
	    self::prepare($sql, [$nameID, $name, '', '{}', $sort_display, $sort_permission]);
	    return new Group(self::lastInsert(), $nameID, $name, '', [], $sort_display, $sort_permission);
    }
	/* Groups */





	/* Sessions */
	public static function getSession($token) {
		if (isset(self::$cachedSessions[$token])) return self::$cachedSessions[$token];
		$sql = 'SELECT * FROM gamma_sessions WHERE Token = ?;';
		$session = null;
		foreach (self::prepare($sql, [$token]) as $row) $session = $row;
		if ($session == null) return null;

		$session = Session::FromRow($session);
		self::$cachedSessions[$token] = $session;
		return $session;
	}

	public static function createSession($token, $id) {
		$session = Session::CreateSession($token, $id);

		$sql = 'INSERT INTO gamma_sessions (Token, AccountID, LastLogin, LastIP) VALUES (?, ?, ?, ?);';
		self::prepare($sql, [$token, $id, $session->getLastLogin(), $session->getLastIP()]);

		return $session;
	}

	public static function dropSession($token) {
		$sql = 'DELETE FROM gamma_sessions WHERE Token = ?;';
		self::prepare($sql, [$token]);
	}
	/* Sessions */





	/* Statistics */
	public static function getQueryTime() {
		return self::$queryTime;
	}

	public static function getQueryCount() {
		return self::$queryCount;
	}
	/* Statistics */

}
