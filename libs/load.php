<?php
include_once 'includes/Session.class.php';
include_once 'includes/Database.class.php';
include_once 'includes/User.class.php';

// ensure session is started for every request
if (session_status() !== PHP_SESSION_ACTIVE) {
    Session::start();
}
function load_template($name)
{
    include '_templates/' . $name . '.php';
}
