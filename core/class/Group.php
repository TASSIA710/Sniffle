<?php

class Group {
	private $groupID, $groupNameID, $groupName, $description, $permissions, $sortDisplay, $sortPermission;

	/* Constructor */
	public function __construct($groupID, $groupNameID, $groupName, $description, $permissions, $sortDisplay, $sortPermission) {
		$this->groupID = $groupID;
		$this->groupNameID = $groupNameID;
		$this->groupName = $groupName;
		$this->description = $description;
		$this->permissions = $permissions;
		$this->sortDisplay = $sortDisplay;
		$this->sortPermission = $sortPermission;
	}

	public static function FromRow($row) {
		return new Group($row['GroupID'], $row['GroupNameID'], $row['GroupName'], $row['Description'], json_decode($row['Permissions'], true),
				$row['SortDisplay'], $row['SortPermission']);
	}

	public static function getDefault() {
		return Database::getGroup(1);
	}

	public static function getSysAdmin() {
		return Database::getGroup(2);
	}
	/* Constructor */





	/* Generic */
	public function getID() {
		return $this->groupID;
	}
	/* Generic */





	/* Group Name ID */
	public function getNameID() {
		return $this->groupNameID;
	}

	public function setNameID($groupNameID, $noUpdate = false) {
		$this->groupNameID = $groupNameID;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_groups SET GroupNameID = ? WHERE GroupID = ?;';
		Database::prepare($sql, [$groupNameID, $this->getID()]);
	}
	/* Group Name ID */





	/* Group Name */
	public function getName() {
		return $this->groupName;
	}

	public function setName($groupName, $noUpdate = false) {
		$this->groupName = $groupName;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_groups SET GroupName = ? WHERE GroupID = ?;';
		Database::prepare($sql, [$groupName, $this->getID()]);
	}
	/* Group Name */





	/* Description */
	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description, $noUpdate = false) {
		$this->description = $description;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_groups SET Description = ? WHERE GroupID = ?;';
		Database::prepare($sql, [$description, $this->getID()]);
	}
	/* Description */





	/* Permissions */
	public function getPermissions() {
		return $this->permissions;
	}

	public function getPermissionJSON() {
		return json_encode($this->getPermissions());
	}

	public function getPermission($permission) {
		if (isset($this->getPermissions()[$permission])) return $this->getPermissions()[$permission];
		if ($permission == '*') return false;
		$permission = explode('.', $permission);
		if ($permission[count($permission) - 1] == '*') {
			unset($permission[count($permission) - 1]);
			$permission[count($permission) - 1] = '*';
		} else {
			$permission[count($permission) - 1] = '*';
		}
		return $this->getPermission(join('.', $permission));
	}

	public function hasPermission($permission) {
		$v = $this->getPermission($permission);
		return $v !== null && $v !== false;
	}

	public function setPermission($permission, $value, $noUpdate = false) {
		$this->permissions[$permission] = $value;
		$this->setPermissions($this->permissions, $noUpdate);
	}

	public function unsetPermission($permission, $noUpdate = false) {
		unset($this->permissions[$permission]);
		$this->setPermissions($this->permissions, $noUpdate);
	}

	public function grantPermission($permission, $noUpdate = false) {
		$this->setPermission($permission, true, $noUpdate);
	}

	public function denyPermission($permission, $noUpdate = false) {
		$this->setPermission($permission, false, $noUpdate);
	}

	public function setPermissions($permissions, $noUpdate = false) {
		$this->permissions = $permissions;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_groups SET Permissions = ? WHERE GroupID = ?;';
		Database::prepare($sql, [json_encode($permissions), $this->getID()]);
	}

	public function setPermissionJSON($json, $noUpdate = false) {
		$this->permissions = json_decode($json, true);
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_groups SET Permissions = ? WHERE GroupID = ?;';
		Database::prepare($sql, [$json, $this->getID()]);
	}
	/* Permissions */





	/* Sort Display */
	public function getSortDisplay() {
		return $this->sortDisplay;
	}

	public function setSortDisplay($sortDisplay, $noUpdate = false) {
		$this->sortDisplay = $sortDisplay;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_groups SET SortDisplay = ? WHERE GroupID = ?;';
		Database::prepare($sql, [$sortDisplay, $this->getID()]);
	}
	/* Sort Display */





	/* Sort Permission */
	public function getSortPermission() {
		return $this->sortPermission;
	}

	public function setSortPermission($sortPermission, $noUpdate = false) {
		$this->sortPermission = $sortPermission;
		if ($noUpdate) return;

		$sql = 'UPDATE gamma_groups SET SortPermission = ? WHERE GroupID = ?;';
		Database::prepare($sql, [$sortPermission, $this->getID()]);
	}
	/* Sort Permission */





	/* Push DB */
	public function pushDB() {
		$sql = 'UPDATE gamma_groups SET GroupNameID = ?, GroupName = ?, Description = ?, Permissions = ?, SortDisplay = ?, SortPermission = ? WHERE GroupID = ?;';
		Database::prepare($sql, [$this->getNameID(), $this->getName(), $this->getDescription(), $this->getPermissionJSON(),
				$this->getSortDisplay(), $this->getSortPermission(), $this->getID()]);
	}
	/* Push DB */





	/* Pull DB */
	public function pullDB() {
		$sql = 'SELECT * FROM gamma_groups WHERE GroupID = ?;';
		$group = null;
		foreach (Database::prepare($sql, [$this->getID()]) as $row) $group = $row;
		if ($group == null) return false;

		$this->setNameID($group['GroupNameID'], true);
		$this->setName($group['GroupName'], true);
		$this->setDescription($group['Description'], true);
		$this->setPermissionJSON($group['Permissions'], true);
		$this->setSortDisplay($group['SortDisplay'], true);
		$this->setSortPermission($group['SortPermission'], true);
		return true;
	}
	/* Pull DB */

}
