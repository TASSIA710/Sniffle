<?php

class Utility {

	/* Generations */
	public static function generateToken($length, $chars) {
		$clen = strlen($chars);
		$str = '';
		for ($i = 0; $i < $length; $i++) {
			$str .= $chars[rand(0, $clen - 1)];
		}
		return $str;
	}

	public static function generateSessionToken() {
		return Utility::generateToken(CONFIG['token_session_length'], CONFIG['token_session_chars']);
	}
	/* Generations */



	/* Escape */
	public static function escapeXSS($str) {
		return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	}
	/* Escape */



	/* String Utility */
	public static function startsWith($str, $check) {
		return substr($str, 0, strlen($check)) === $check;
	}

	public static function endsWith($str, $check) {
		if ($check === '') return true;
		return substr($str, -strlen($check)) === $check;
	}

	public static function replaceFirst($from, $to, $content) {
	    $from = '/' . preg_quote($from, '/') . '/';
	    return preg_replace($from, $to, $content, 1);
	}
	/* String Utility */



	/* Access ID */
	private static $sessionID = null;

	public static function getSessionID() {
		if (self::$sessionID != null) return self::$sessionID;
		if (AuthManager::isLoggedIn()) {
			self::$sessionID = uniqid(AuthManager::getCurrentUser()->getID() . '-', true);
		} else {
			self::$sessionID = uniqid('guest-', true);
		}
		return self::$sessionID;
	}
	/* Access ID */



	/* Browser */
	private static $browser = null;
	public static function getBrowser() {
		if (self::$browser != null) return self::$browser;
	    $u_agent = $_SERVER['HTTP_USER_AGENT'];
	    $bname = 'Unknown';
	    $platform = 'Unknown';
	    $version= '';

	    //First get the platform?
	    if (preg_match('/linux/i', $u_agent)) {
	        $platform = 'Linux';
	    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
	        $platform = 'Macintosh / Mac OS X';
	    } elseif (preg_match('/windows|win32/i', $u_agent)) {
	        $platform = 'Windows';
	    }

	    $matches = [];

	    // Next get the name of the useragent yes seperately and for good reason
	    if (preg_match('/MSIE/i' , $u_agent, $matches) && !preg_match('/Opera/i', $u_agent, $matches)) {
	        $bname = 'Internet Explorer';
	        $ub = 'MSIE';
	    } elseif (preg_match('/Firefox/i', $u_agent, $matches)) {
	        $bname = 'Mozilla Firefox';
	        $ub = 'Firefox';
	    } elseif (preg_match('/Chrome/i', $u_agent, $matches)) {
	        $bname = 'Google Chrome';
	        $ub = 'Chrome';
	    } elseif (preg_match('/Safari/i', $u_agent, $matches)) {
	        $bname = 'Apple Safari';
	        $ub = 'Safari';
	    } elseif (preg_match('/Opera/i', $u_agent, $matches)) {
	        $bname = 'Opera';
	        $ub = 'Opera';
	    } elseif (preg_match('/Netscape/i', $u_agent, $matches)) {
	        $bname = 'Netscape';
	        $ub = 'Netscape';
	    } else {
            $bname = 'Unknown';
            $ub = 'Unknown';
        }

	    // finally get the correct version number
	    $known = array('Version', $ub, 'other');
	    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	    // if (!preg_match_all($pattern, $u_agent, $matches)) {
	    //     // we have no matching number just continue
	    // }

	    // see how many we have
	    $i = count($matches['browser']);
	    if ($i != 1) {
	        //we will have two since we are not using 'other' argument yet
	        //see if version is before or after the name
	        if (strripos($u_agent, 'Version') < strripos($u_agent, $ub)) {
	            $version = $matches['version'][0];
	        } else {
	            $version = $matches['version'][1];
	        }
	    } else {
	        $version = $matches['version'][0];
	    }

	    // check if we have a number
		if ($version == null || $version == '') $version = '?';

		$data = new stdClass();
		$data->UserAgent = $u_agent;
		$data->Name = $bname;
		$data->Version = $version;
		$data->Platform = $platform;
		$data->Pattern = $pattern;
		self::$browser = $data;
		return $data;
	}
	/* Browser */





	/* Display Page */
	public static function displayPage($path, $replacements = [], $replaceDefaults = true) {
		$content = file_get_contents($path);

		if ($replaceDefaults) {
			if (!isset($replacements['cfg_root'])) $replacements['cfg_root'] = CONFIG['root'];
			if (!isset($replacements['cfg_proxy'])) $replacements['cfg_proxy'] = CONFIG['proxy'];
			if (!isset($replacements['cfg_sysadmin'])) $replacements['cfg_sysadmin'] = CONFIG['system_administrator'];
		}

		foreach ($replacements as $key => $value) {
			$content = str_replace('{%' . $key . '%}', Utility::escapeXSS($value), $content);
			$content = str_replace('[%' . $key . '%]', $value, $content);
		}

		return $content;
	}
	/* Display Page */





	/* Ray ID */
	public static function getRayID() {
		if (CONFIG['proxy'] == 'cloudflare' && isset($_SERVER['HTTP_CF_RAY'])) return $_SERVER['HTTP_CF_RAY'];
		return substr(sha1($_SERVER['REQUEST_URI']), 0, 16) . '-DRCT';
	}
	/* Ray ID */





	/* Show Status */
	public static function showStatus($status) {
		if (isset(ERRORS[$status])) {
			self::showCustomStatus(ERRORS[$status]);
		} else {
			self::showCustomStatus([
				'client_error' => false,
				'error_code' => $status,
				'error_name' => 'Unimplemented Error',
				'server_error' => 'Error',
				'error_message' => <<<EOT
					An unknown error occurred, and we don't even know what error page to display. That's all we know.
				EOT,
			]);
		}
	}

	public static function showCustomStatus($error) {
		Header::setHideAll(true);

		if ($error['client_error']) {
			$content = file_get_contents(__DIR__ . '/../../resources/html/ErrorClient.html');
		} else {
			$content = file_get_contents(__DIR__ . '/../../resources/html/ErrorServer.html');
		}

		$content = str_replace('%error_code%', $error['error_code'], $content);
		$content = str_replace('%error_name%', $error['error_name'], $content);

		if (CONFIG['proxy'] == 'cloudflare') {
			if (isset($_SERVER['HTTP_CF_RAY'])) {
				$content = str_replace('%proxy_location%', preg_replace('([a-zA-Z0-9]+-)', '', $_SERVER['HTTP_CF_RAY']), $content);
				$content = str_replace('%proxy_icon%', 'fa-check-circle', $content);
				$content = str_replace('%proxy_name%', 'CloudFlare', $content);
				$content = str_replace('%proxy_state%', 'working', $content);
				$content = str_replace('%proxy_state_name%', 'Working', $content);
			} else {
				$content = str_replace('%proxy_location%', '&ensp;', $content);
				$content = str_replace('%proxy_icon%', 'fa-minus-circle', $content);
				$content = str_replace('%proxy_name%', 'CloudFlare', $content);
				$content = str_replace('%proxy_state%', 'misc', $content);
				$content = str_replace('%proxy_state_name%', 'Bypassed', $content);
			}
		} else {
			$content = str_replace('%proxy_location%', '&ensp;', $content);
			$content = str_replace('%proxy_icon%', 'fa-minus-circle', $content);
			$content = str_replace('%proxy_name%', 'Proxy', $content);
			$content = str_replace('%proxy_state%', 'misc', $content);
			$content = str_replace('%proxy_state_name%', 'None', $content);
		}

		$content = str_replace('%server_name%', $_SERVER['SERVER_NAME'], $content);

		if ($error['client_error']) {
			$content = str_replace('%resource_name%', $_SERVER['REQUEST_URI'], $content);
			$content = str_replace('%resource_state%', 'error', $content);
			$content = str_replace('%resource_state_name%', $error['document_error'], $content);
		} else {
			$content = str_replace('%server_icon%', 'fa-times-circle', $content);
			$content = str_replace('%server_state%', 'error', $content);
			$content = str_replace('%server_state_name%', $error['server_error'], $content);
		}

		$content = str_replace('%server_software%', $_SERVER['SERVER_SOFTWARE'], $content);
		$content = str_replace('%server_port%', $_SERVER['SERVER_PORT'], $content);
		$content = str_replace('%info_rayid%', Utility::getRayID(), $content);
		$content = str_replace('%info_date%', date('c'), $content);

		$content = str_replace('%error_message%', $error['error_message'], $content);
		$content = str_replace('%system_administrator%', CONFIG['system_administrator'], $content);
		echo $content;
	}
	/* Show Status */

}
