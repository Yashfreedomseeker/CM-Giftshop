<?php
class Session {
    public static function start($sessionName = "admin_session") {
        if (session_status() == PHP_SESSION_NONE) {
            session_name($sessionName);
            session_start();
        }
    }

    public static function get($key) {
        self::start(); // Ensure session is started before getting value
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function set($key, $value) {
        self::start(); // Ensure session is started before setting value
        $_SESSION[$key] = $value;
    }

    public static function isLoggedIn() {
        return self::get('user_id') !== null;
    }

    public static function destroy() {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
}

?>