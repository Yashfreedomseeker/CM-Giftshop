<?php
class UserSession {
    public static function checkSessionValidity() {
        // Debug output for session and cookie status
        echo "<script>console.log('SESSION user_id: " . (Session::get('user_id') ? Session::get('user_id') : 'NOT SET') . "');</script>";
        echo "<script>console.log('All Cookies: " . json_encode($_COOKIE) . "');</script>";

        if (!Session::isLoggedIn()) {
            echo "<script>console.log('SESSION user_id NOT SET! Redirecting...');</script>";
            Session::destroy();
            header("Location: login.php");
            exit();
        }

        // Ensure session_expire cookie is set
        if (!Cookie::get('session_expire')) {
            echo "<script>console.log('COOKIE session_expire NOT SET! Setting cookie...');</script>";
            Cookie::set('session_expire', time() + 1800); // Set new expiry for 30 mins
            // No redirect here to avoid loop
        }

        $currentTime = time();
        $cookieTime = Cookie::get('session_expire');

        echo "<script>console.log('Current Time: " . $currentTime . "');</script>";
        echo "<script>console.log('Cookie Expiration Time: " . $cookieTime . "');</script>";

        if ($currentTime > $cookieTime) {
            echo "<script>console.log('Session EXPIRED! Redirecting...');</script>";
            Cookie::delete('session_expire');
            Session::destroy();
            header("Location: login.php");
            exit();
        }

        // Extend session expiry time by 30 minutes
        $newExpiryTime = time() + 1800; // 30 minutes from now
        Cookie::set('session_expire', $newExpiryTime);

        echo "<script>console.log('Session is valid. Expiry time extended to: " . $newExpiryTime . "');</script>";
    }
}
?>