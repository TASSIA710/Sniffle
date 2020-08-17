<?php

class Cookies {

	public static function setCookie($name, $value, $domain = CONFIG['cookie_domain']) {
		$_COOKIE[$name] = $value;
		setcookie($name, $value, time() + CONFIG['cookie_duration'], '/', $domain);
	}

	public static function removeCookie($name, $domain = CONFIG['cookie_domain']) {
		if (isset($_COOKIE[$name])) {
			unset($_COOKIE[$name]);
		}
		setcookie($name, '', time() - 3600, '/', $domain);
	}

	public static function getCookie($name) {
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
	}

}
