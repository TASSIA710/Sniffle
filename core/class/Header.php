<?php

class Header {
	private static $stylesheets = [];
	private static $scripts = [];
	private static $tabs = [];
	private static $title = '';
	private static $info = '';
	private static $hideAuthActions = false;
	private static $hideVisible = false;
	private static $hideAll = false;

	/* Stylesheets */
	public static function addStylesheet($stylesheet) {
		self::$stylesheets[$stylesheet] = true;
	}

	public static function removeStylesheet($stylesheet) {
		unset(self::$stylesheets[$stylesheet]);
	}

	public static function getStylesheets() {
		return self::$stylesheets;
	}
	/* Stylesheets */





	/* Scripts */
	public static function addScript($script) {
		self::$scripts[$script] = true;
	}

	public static function removeScript($script) {
		unset(self::$scripts[$script]);
	}

	public static function getScripts() {
		return self::$scripts;
	}
	/* Scripts */





	/* Tabs */
	public static function addTab($name, $url, $icon = null) {
		if (!Utility::startsWith($url, 'https://') && !Utility::startsWith($url, 'http://')) {
			$url = CONFIG['root'] . $url;
		}
		self::$tabs[$name] = [
			'url' => $url,
			'icon' => $icon,
			'active' => false
		];
	}

	public static function removeTab($name) {
		unset(self::$tabs[$name]);
	}

	public static function getTabs() {
		return self::$tabs;
	}

	public static function setTabActive($name, $active = true) {
		self::$tabs[$name]['active'] = $active;
	}
	/* Tabs */





	/* Window Title */
	public static function getTitle() {
		return self::$title;
	}

	public static function setTitle($title) {
		self::$title = $title;
	}
	/* Window Title */





	/* Information */
	public static function getInfo() {
		return self::$info;
	}

	public static function setInfo($info) {
		self::$info = $info;
	}
	/* Information */





	/* Hide Auth Actions */
	public static function getHideAuthActions() {
		return self::$hideAuthActions;
	}

	public static function setHideAuthActions($hideAuthActions) {
		self::$hideAuthActions = $hideAuthActions;
	}
	/* Hide Auth Actions */





	/* Hide Visible */
	public static function getHideVisible() {
		return self::$hideVisible;
	}

	public static function setHideVisible($hideVisible) {
		self::$hideVisible = $hideVisible;
	}
	/* Hide Visible */





	/* Hide All */
	public static function getHideAll() {
		return self::$hideAll;
	}

	public static function setHideAll($hideAll) {
		self::$hideAll = $hideAll;
	}
	/* Hide All */





	/* Global */
	public static function requireGlobal() {
		self::addStylesheet('assets/css/generic.css');
		self::addStylesheet('assets/css/grids.css');
		self::addStylesheet('assets/css/simple_page.css');
		self::addScript('assets/js/config.js');
		self::addScript('assets/js/global.js');
		self::addScript('assets/js/relative_time.js');
	}

	public static function unrequireGlobal() {
		self::removeStylesheet('assets/css/generic.css');
		self::removeStylesheet('assets/css/grids.css');
		self::removeStylesheet('assets/css/simple_page.css');
		self::removeScript('assets/js/config.js');
		self::removeScript('assets/js/global.js');
		self::removeScript('assets/js/relative_time.js');
	}
	/* Global */





	/* Bootstrap */
	public static function requireBootstrap() {
		self::addStylesheet('https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css');
		self::addStylesheet('assets/css/bootstrap/extras.css');
		self::addScript('https://code.jquery.com/jquery-3.4.1.slim.min.js');
		self::addScript('https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js');
		self::addScript('https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js');
	}

	public static function unrequireBootstrap() {
		self::removeStylesheet('https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css');
		self::removeStylesheet('assets/css/bootstrap/extras.css');
		self::removeScript('https://code.jquery.com/jquery-3.4.1.slim.min.js');
		self::removeScript('https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js');
		self::removeScript('https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js');
	}
	/* Bootstrap */





	/* Font Awesome */
	public static function requireFontAwesome() {
		self::addStylesheet('https://use.fontawesome.com/releases/v5.12.1/css/all.css');
	}

	public static function unrequireFontAwesome() {
		self::removeStylesheet('https://use.fontawesome.com/releases/v5.12.1/css/all.css');
	}
	/* Font Awesome */

}
