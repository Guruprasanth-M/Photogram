<?php

class User
{
    public static function signup($user, $pass, $email, $phone)
    {   
        $pass = md5($pass);
        $conn = Database::getConnection();
        $sql = "INSERT INTO `auth` (`username`, `password`, `email`, `phone`, `active`) VALUES ('"
            . $conn->real_escape_string($user) . "', '" . $conn->real_escape_string($pass) . "', '" . $conn->real_escape_string($email) . "', '" . $conn->real_escape_string($phone) . "', '1')";

        try {
            if ($conn->query($sql) === true) {
                return true; // success
            } else {
                return $conn->error;
            }
        } catch (mysqli_sql_exception $e) {
            return $e->getMessage();
        }
    }

    public static function login($user, $pass)
    {
        // validate using MD5-hashed password only
        $md = md5($pass);
        $conn = Database::getConnection();
        $user_esc = $conn->real_escape_string($user);
        $query = "SELECT * FROM `auth` WHERE `username` = '" . $user_esc . "' AND `password` = '" . $conn->real_escape_string($md) . "' LIMIT 1";
        $result = $conn->query($query);
        if ($result && $result->num_rows == 1) {
            return $result->fetch_assoc();
        }

        return false;
    }

    public function __construct($username)
    {
        $this->conn = Database::getConnection();
        $this->conn->query();
    }

    public function authenticate()
    {
    }

    public function setBio()
    {
    }

    public function getBio()
    {
    }

    public function setAvatar()
    {
    }

    public function getAvatar()
    {
    }
}


