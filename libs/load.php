<?php
include_once 'includes/Session.class.php';
include_once 'includes/Database.class.php';
include_once 'includes/User.class.php';

Session::start();

function load_template($name)
{
    include $_SERVER['DOCUMENT_ROOT']."/app/_templates/$name.php"; //not consistant.
}

function validate_credentials($username, $password)
{
    if ($username == "sibi@selfmade.ninja" and $password == "password") {
        return true;
    } else {
        return false;
    }
}

function signup($user, $pass, $email, $phone)
{
    return User::signup($user, $pass, $email, $phone);
}
