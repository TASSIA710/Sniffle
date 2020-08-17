<?php

register_shutdown_function(function() {

    if (CONFIG['debug_log_access']) {
        $AccessRay = Utility::getSessionID();
        $SessionID = AuthManager::isLoggedIn() ? AuthManager::getCurrentSession()->getToken() : null;
        $AccountID = AuthManager::isLoggedIn() ? AuthManager::getCurrentUser()->getID() : null;
        $Timestamp = time();
        $UserAgent = $_SERVER['HTTP_USER_AGENT'];
        $RequestMethod = $_SERVER['REQUEST_METHOD'];
        $RequestURI = $_SERVER['REQUEST_URI'];
        $RequestQuery = $_SERVER['QUERY_STRING'];
        $Connection = $_SERVER['HTTP_CONNECTION'];
        $Referer = $_SERVER['HTTP_REFERER'];
        $Address = $_SERVER['REMOTE_ADDR'];
        $AddressPort = $_SERVER['REMOTE_PORT'];
        $Protocol = $_SERVER['SERVER_PROTOCOL'];
        $ServerSoftware = $_SERVER['SERVER_SOFTWARE'];
        $ServerName = $_SERVER['SERVER_NAME'];
        $Gateway = $_SERVER['GATEWAY_INTERFACE'];

        $data = "\n";
        $data .= '> Access Ray: `' . $AccessRay . '`' . "\\\n";
        if (AuthManager::isLoggedIn()) {
            $token = AuthManager::getCurrentSession()->getToken();
            $user = AuthManager::getCurrentUser();
            $data .= '> Session ID: `' . $SessionID . '`' . "\\\n";
            $data .= '> Account: `' . $user->getUsername() . ' [' . $user->getID() . ']` @ `' . $token . '`' . "\\\n";
        } else {
            $data .= '> Session ID: `GUEST`' . "\\\n";
            $data .= '> Account: `GUEST`' . "\\\n";
        }
        $data .= '> Timestamp: `' . date('r') . '`' . "\\\n";
        $data .= '> Browser: `' . Utility::getBrowser()->Name . ' ' . Utility::getBrowser()->Version . '`' . "\\\n";
        $data .= '> Platform: `' . Utility::getBrowser()->Platform . '`' . "\\\n";
        $data .= '> Request: `' . $RequestMethod . '` `' . $RequestURI . '` `?' . $RequestQuery . '`' . "\\\n";
        $data .= '> Connection: `' . $Connection . '`' . "\\\n";
        $data .= '> Referer: `' . $Referer . '`' . "\\\n";
        $data .= '> Address: `' . $Address . ':' . $AddressPort . '`' . "\\\n";
        $data .= '> Protocol: `' . $Protocol . '`' . "\\\n";
        $data .= '> Server: `' . $ServerSoftware . '` on `' . $ServerName . '`' . "\\\n";
        $data .= '> Gateway: `' . $Gateway . '`' . "\n";
        file_put_contents(__DIR__ . '/logs/access.md', $data, FILE_APPEND | LOCK_EX);

        $sql = 'INSERT INTO moodclap_access (AccessRay, SessionID, AccountID, Timestamp, UserAgent, RequestMethod, RequestURI, RequestQuery, Connection, Referer, Address, AddressPort, Protocol, ServerSoftware, ServerName, Gateway) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $data = [$AccessRay, $SessionID, $AccountID, $Timestamp, $UserAgent, $RequestMethod, $RequestURI, $RequestQuery, $Connection, $Referer, $Address, $AddressPort, $Protocol, $ServerSoftware, $ServerName, $Gateway];
        Database::prepare($sql, $data);
    }

    if (CONFIG['debug_log_sql']) {
        $data = "\n";
        $data .= '-- ' . Utility::getSessionID() . ' | ' . date('r') . "\n";
        foreach (Database::$queryHistory as $sql) {
            $data .= $sql . "\n";
        }
        if (!file_exists(__DIR__ . '/logs/queries.sql')) {
        	mkdir(__DIR__ . '/logs');
		}
        file_put_contents(__DIR__ . '/logs/queries.sql', $data, FILE_APPEND | LOCK_EX);
    }

});
