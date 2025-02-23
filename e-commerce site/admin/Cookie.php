<?php
class Cookie {
    public static function set($name, $value, $expires = 0, $secure = false) {
        if (PHP_VERSION_ID >= 70300) { // If PHP 7.3 or later
            setcookie($name, $value, [
                'expires' => $expires ?: time() + 1800, // Default 30 minutes if not set
                'path' => '/',
                'secure' => $secure, // Set true for HTTPS
                'httponly' => true,
                'samesite' => 'None' // Allows cross-domain usage
            ]);
            echo "<script>console.log('cookie set...');</script>";
        } else { // For older PHP versions (below 7.3)
            setcookie($name, $value, $expires ?: time() + 1800, '/', '', $secure, true);
            echo "<script>console.log('Cookie Set...');</script>";
        }
    }

    public static function get($name) {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public static function delete($name) {
        setcookie($name, '', time() - 3600, '/');
        unset($_COOKIE[$name]);
    }
}
?>