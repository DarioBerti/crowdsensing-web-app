<?php
function sec_session_start() {
    if (session_status() == PHP_SESSION_NONE) {
        $session_name = 'sec_session_id';
        $secure = false; // Not using HTTPS
        $httponly = true; // Prevent JavaScript access to session ID

        // Ensure session uses only cookies
        if (ini_set('session.use_only_cookies', 1) === false) {
            header("Location: error.php?err=Could not initiate a safe session (ini_set)");
            exit();
        }

        // Get current cookie parameters
        $cookieParams = session_get_cookie_params();

        // Set new cookie parameters
        session_set_cookie_params(
            $cookieParams["lifetime"],
            $cookieParams["path"],
            $cookieParams["domain"],
            $secure,
            $httponly
        );

        // Set session name and start the session
        session_name($session_name);
        session_start();
        session_regenerate_id(true);
    }
}
