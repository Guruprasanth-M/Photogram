<?php

class WebAPI
{
    public function __construct()
    {
        global $__site_config;
        $__site_config_path = __DIR__.'/../../../project/photogramconfig.json';
        $__site_config = file_get_contents($__site_config_path);

        // TODO: Remove this dynamic base_path detection once development is done
        //       and the app runs on a single domain. Keep only the config value.
        $config = json_decode($__site_config, true);
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (preg_match('/^(\d{1,3}\.){3}\d{1,3}/', $host)) {
            // IP-based access (e.g. 172.30.20.210) → needs subdirectory base path
            $config['base_path'] = '/photogram/htdocs/';
        } else {
            // Domain-based access (e.g. array.zeal.ninja) → root base path
            $config['base_path'] = '/';
        }
        $__site_config = json_encode($config);

        Database::getConnection();
    }

    public function initiateSession()
    {
        Session::start();
        if (Session::isset("session_token")) {
            try {
                Session::$usersession = UserSession::authorize(Session::get('session_token')); 
            } 
            catch (Exception $e){
                //TODO: Handle error
            }
        }
    }
}
