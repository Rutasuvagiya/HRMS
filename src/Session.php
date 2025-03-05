<?php
namespace HRMS;

class Session {
    private static ?Session $instance = null;

    private function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Start session if not already started
        }
    }

    public static function getInstance(): Session {
        if (self::$instance === null) {
            self::$instance = new Session();
        }
        return self::$instance;
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public function destroy() {
        session_unset();
        session_destroy();
        self::$instance = null;
    }

    public function checkSession() {
        if (!$this->get('userID')) {
            header("Location: /login");
            exit();
        }
    }

    public function checkAdminSession() {
        if ($this->get('role') != 'admin') {
            header("Location: /dashboard");
            exit();
        }
    }
}
?>
