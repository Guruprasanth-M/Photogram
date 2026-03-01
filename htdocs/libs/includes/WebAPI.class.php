<?php

class WebAPI
{
    public function __construct()
    {
        global $__site_config;
        $__site_config_path = __DIR__.'/../../../project/photogramconfig.json';
        $__site_config = file_get_contents($__site_config_path);

        $config = json_decode($__site_config, true);

        // Auto-detect base_path based on environment.
        // Docker: DocumentRoot is /var/www/html/htdocs/ → base_path stays "/"
        // Bare metal IP: app lives in /photogram/htdocs/ subdirectory
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
        $scriptFile = $_SERVER['SCRIPT_FILENAME'] ?? '';

        // If the script is inside a /photogram/htdocs/ subdirectory relative
        // to DocumentRoot, we need the subdirectory base path.
        if (preg_match('/^(\d{1,3}\.){3}\d{1,3}/', $host)
            && strpos($scriptFile, $docRoot . '/photogram/htdocs/') === 0) {
            $config['base_path'] = '/photogram/htdocs/';
        }
        $__site_config = json_encode($config);

        Database::getConnection();

        // Auto-bootstrap DB schema (safe: uses CREATE TABLE IF NOT EXISTS)
        $init = __DIR__ . '/../../../db/init.php';
        if (file_exists($init)) {
            require_once $init;
        }
    }

    public function initiateSession()
    {
        Session::start();
        if (Session::isset("session_token")) {
            try {
                Session::$usersession = UserSession::authorize(Session::get('session_token'));
            } catch (Exception $e) {
                // Session invalid or expired — continue as guest
            }
        }
    }
}
