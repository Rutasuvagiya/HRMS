<?php
namespace HRMS\core;

/**
 * Class Session
 *
 * This class handles session activities 
 * Singleton pattern is applied to this class to restrice multiple instance
 *
 */
class Session {
    /**
     * @var Session|null Holds the single instance of the Session class.
     */
    private static ?Session $instance = null;

    /**
     * Private constructor to prevent direct instantiation.
     * Starts a session if not already started.
     */
    private function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Start session if not already started
        }
    }

    /**
     * Prevents cloning of the singleton instance.
     */
    private function __clone() {}

    /**
     * Prevents unserialization of the singleton instance.
     */
    public function __wakeup() {}

    /**
     * Gets the single instance of the Session class.
     *
     * @return Session The Singleton instance.
     */
    public static function getInstance(): Session {
        if (self::$instance === null) {
            self::$instance = new Session();
        }
        return self::$instance;
    }

    /**
     * Sets a session variable.
     *
     * @param string $key The session key.
     * @param mixed $value The value to store.
     * @return void
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets a session variable.
     *
     * @param string $key The session key.
     * @return mixed|null Returns the value if set, otherwise null.
     */
    public function get($key) {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Removes a session variable.
     *
     * @param string $key The session key.
     * @return void
     */
    public function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroys the session completely.
     *
     * @return void
     */
    public function destroy() {
        session_unset();
        session_destroy();
        self::$instance = null;
    }

    /**
     * Check if user is logged in, else redirect to login page.
     * To prevent direct access of pages without login.
     *
     * @return void
     */
    public function checkSession() {
        if (!$this->get('userID')) {
            header("Location: /login");
            exit();
        }
    }

     /**
     * Check if user is Admin user, else redirect to front dashboard.
     * To prevent direct access of admin panel pages without admin access.
     *
     * @return void
     */
    public function checkAdminSession() {
        if ($this->get('role') != 'admin') {
            header("Location: /dashboard");
            exit();
        }
    }
}
?>
