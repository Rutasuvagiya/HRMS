<?php

namespace HRMS\Core;

/**
 * Class Session
 * Implements Singleton pattern for session management.
 */
class Session
{
    /**
     * @var Session|null The single instance of the class.
     */
    private static ?Session $instance = null;
/**
     * @var bool Whether session should be started (for testing).
     */
    private static bool $testMode = false;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
        if (!self::$testMode && session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
    }

    /**
     * Gets the singleton instance of Session.
     *
     * @return Session
     */
    public static function getInstance(): Session
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Enables test mode (prevents actual session start).
     */
    public static function enableTestMode(): void
    {
        self::$testMode = true;
        self::$instance = null;
// Reset instance for testing
        $_SESSION = []; // Mock session storage
    }

    /**
     * Sets a session variable.
     *
     * @param string $key The session key.
     * @param mixed $value The session value.
     */
    public function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets a session variable.
     *
     * @param string $key The session key.
     * @return mixed|null The session value or null if not set.
     */
    public function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Destroys the session.
     */
    public function destroy(): void
    {
        $_SESSION = [];
        session_unset();
        session_destroy();
    }

    /**
     * Check if user is logged in, else redirect to login page.
     * To prevent direct access of pages without login.
     *
     * @return void
     */
    public function checkSession()
    {
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
    public function checkAdminSession()
    {
        if ($this->get('role') != 'admin') {
            header("Location: /dashboard");
            exit();
        }
    }
}
