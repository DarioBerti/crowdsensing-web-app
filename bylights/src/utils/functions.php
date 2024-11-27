<?php
function sec_session_start() {
    if (session_status() == PHP_SESSION_NONE) {
        $session_name = 'sec_session_id';
        $secure = false; // non usa https
        $httponly = true; // previene accesso javascript ad ID

        //sessione usa solo cookies
        if (ini_set('session.use_only_cookies', 1) === false) {
            header("Location: error.php?err=Could not initiate a safe session (ini_set)");
            exit();
        }

        // get sessione corrente params
        $cookieParams = session_get_cookie_params();

        // Set nuovi params della sessione
        session_set_cookie_params(
            $cookieParams["lifetime"],
            $cookieParams["path"],
            $cookieParams["domain"],
            $secure,
            $httponly
        );

        // Set nome sessione e incomincia nuova sessione
        session_name($session_name);
        session_start();
        session_regenerate_id(true);
    }
}
