<?php
include_once 'includes/Session.class.php';
include_once 'includes/Database.class.php';
include_once 'includes/User.class.php';

global $__site_config;
//Note: Change this path if you run this code outside lab.
$__site_config = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/../photogramconfig.json');


// ensure session is started for every request
if (session_status() !== PHP_SESSION_ACTIVE) {
    Session::start();
}
function load_template($name)
{
    include '_templates/' . $name . '.php';
}


function get_config($key, $default=null)
{
    global $__site_config;
    $array = json_decode($__site_config, true);
    if (isset($array[$key])) {
        return $array[$key];
    } else {
        return $default;
    }
}


