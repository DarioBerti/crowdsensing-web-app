<?php
    /***
     * da usare ogni volta che si inizia una sessione
     */
    function sec_session_start() {
        $session_name = 'sec_session_id';
        $secure = false; // not use https but only http
        $httponly = true; // hide session id from js
        ini_set('session.use_only_cookies', 1); //use only cookies
        $cookieParams = session_get_cookie_params(); // read cookies
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        session_name($session_name); 
        session_start();
        session_regenerate_id();
    }
?>