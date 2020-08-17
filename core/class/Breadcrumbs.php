<?php

class Breadcrumbs {
	private static $breadcrumbs = [];

	public static function add($name, $url) {
		self::$breadcrumbs[count(self::$breadcrumbs)] = [
			'name' => $name,
			'url' => $url
		];
	}

	public static function get() {
		return self::$breadcrumbs;
	}

}
