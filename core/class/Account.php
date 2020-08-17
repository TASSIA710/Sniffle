<?php

class Account {
	private $accountID, $username, $password, $groupID, $firstVisit, $lastVisit, $firstIP, $lastIP, $flags;

	/* Constructor */
	public function __construct($accountID, $username, $password, $groupID, $firstVisit, $lastVisit, $firstIP, $lastIP, $flags) {
		$this->accountID = $accountID;
		$this->username = $username;
		$this->password = $password;
		$this->groupID = $groupID;
		$this->firstVisit = $firstVisit;
		$this->lastVisit = $lastVisit;
		$this->firstIP = $firstIP;
		$this->lastIP = $lastIP;
		$this->flags = $flags;
	}

	public static function FromRow($row) {
		return new Account($row['AccountID'], $row['Username'], $row['Password'], $row['GroupID'], $row['FirstVisit'],
				$row['LastVisit'], $row['FirstIP'], $row['LastIP'], $row['Flags']);
	}
	/* Constructor */



	/* Generic */
	public function getID() {
		return $this->accountID;
	}
	/* Generic */



	/* Username */
	public function getUsername() {
		return $this->username;
	}

	public function setUsername($username, $noUpdate = false) {
		$this->username = $username;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_accounts SET Username = ? WHERE AccountID = ?;';
		Database::prepare($sql, [$username, $this->getID()]);
	}
	/* Username */



	/* Password */
	public function getPassword() {
		return $this->password;
	}

	public function setPassword($password, $noUpdate = false) {
		$password = password_hash($password, PASSWORD_DEFAULT);
		$this->password = $password;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_accounts SET Password = ? WHERE AccountID = ?;';
		Database::prepare($sql, [$password, $this->getID()]);
	}
	/* Password */



	/* Group */
	public function getGroupID() {
		return $this->groupID;
	}

	public function getGroup() {
		return Database::getGroup($this->getGroupID());
	}

	public function setGroupID($groupID, $noUpdate = false) {
		$this->groupID = $groupID;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_accounts SET GroupID = ? WHERE AccountID = ?;';
		Database::prepare($sql, [$groupID, $this->getID()]);
	}
	/* Group */



	/* First Visit */
	public function getFirstVisit() {
		return $this->firstVisit;
	}

	public function setFirstVisit($firstVisit, $noUpdate = false) {
		$this->firstVisit = $firstVisit;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_accounts SET FirstVisit = ? WHERE AccountID = ?;';
		Database::prepare($sql, [$firstVisit, $this->getID()]);
	}
	/* First Visit */



	/* Last Visit */
	public function getLastVisit() {
		return $this->lastVisit;
	}

	public function setLastVisit($lastVisit, $noUpdate = false) {
		$this->lastVisit = $lastVisit;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_accounts SET LastVisit = ? WHERE AccountID = ?;';
		Database::prepare($sql, [$lastVisit, $this->getID()]);
	}
	/* Last Visit */



	/* First IP */
	public function getFirstIP() {
		return $this->firstIP;
	}

	public function setFirstIP($firstIP, $noUpdate = false) {
		$this->firstIP = $firstIP;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_accounts SET FirstIP = ? WHERE AccountID = ?;';
		Database::prepare($sql, [$firstIP, $this->getID()]);
	}
	/* First IP */



	/* Last IP */
	public function getLastIP() {
		return $this->lastIP;
	}

	public function setLastIP($lastIP, $noUpdate = false) {
		$this->lastIP = $lastIP;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_accounts SET LastIP = ? WHERE AccountID = ?;';
		Database::prepare($sql, [$lastIP, $this->getID()]);
	}
	/* Last IP */



	/* Flags */
	public function getFlags() {
		return $this->flags;
	}

	public function setFlags($flags, $noUpdate = false) {
		$this->flags = $flags;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_accounts SET Flags = ? WHERE AccountID = ?;';
		Database::prepare($sql, [$flags, $this->getID()]);
	}
	/* Flags */



	/* Push DB */
	public function pushDB() {
		$sql = 'UPDATE gamma_accounts SET Username = ?, Password = ?, GroupID = ?, FirstVisit = ?, LastVisit = ?, FirstIP = ?, LastIP = ?, Flags = ? WHERE AccountID = ?;';
		Database::prepare($sql, [$this->getUsername(), $this->getPassword(), $this->getGroupID(), $this->getFirstVisit(),
				$this->getLastVisit(), $this->getFirstIP(), $this->getLastIP(), $this->getFlags(), $this->getID()]);
	}
	/* Push DB */



	/* Pull DB */
	public function pullDB() {
		$sql = 'SELECT * FROM gamma_accounts WHERE AccountID = ?;';
		$account = null;
		foreach (Database::prepare($sql, [$this->getID()]) as $row) $account = $row;
		if ($account == null) return false;

		$this->username = $account['Username'];
		$this->password = $account['Password'];
		$this->groupID = $account['GroupID'];
		$this->firstVisit = $account['FirstVisit'];
		$this->lastVisit = $account['LastVisit'];
		$this->firstIP = $account['FirstIP'];
		$this->lastIP = $account['LastIP'];
		$this->flags = $account['Flags'];
		return true;
	}
	/* Pull DB */

}
