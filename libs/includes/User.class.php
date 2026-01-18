<?php

class User
{
    public function __call($name, $arguments){
        $property = preg_replace("/[^0-9a-zA-Z]/", "", substr($name, 3));
        $property = strtolower(preg_replace('/\B([A-Z])/', '_$1', $property));
        if (substr($property, -5) === 'link') {
            $property = substr($property, 0, -5);
        }
        if (substr($name, 0, 3) == "get") {
            return $this->_get_data($property);
        } elseif (substr($name, 0, 3) == "set") {
            return $this->_set_data($property, $arguments[0]);
        }
    }

    public static function signup($user, $pass, $email, $phone)
    {           
        $options = [
            'cost' => 9,
        ];
        $pass = password_hash($pass, PASSWORD_BCRYPT, $options);
        $conn = Database::getConnection();
        $sql = "INSERT INTO `auth` (`username`, `password`, `email`, `phone`, `active`) VALUES ('"
            . $conn->real_escape_string($user) . "', '" . $conn->real_escape_string($pass) . "', '" . $conn->real_escape_string($email) . "', '" . $conn->real_escape_string($phone) . "', '0')";

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
        $conn = Database::getConnection();
        $user_esc = $conn->real_escape_string($user);
        $query = "SELECT * FROM `auth` WHERE `username` = '" . $user_esc . "' LIMIT 1";
        $result = $conn->query($query);
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (isset($row['password']) && password_verify($pass, $row['password'])) {
                return $row;
            }
        }

        return false;
    }

    public function __construct($username)
    {
        $this->conn = Database::getConnection();
        $this->username = $username;
        $this->id = null;
        $sql = "SELECT `id` FROM `auth` WHERE `username`= '$username' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $this->id = $row['id'];
        } else {
            throw new Exception("Username does't exist");
        }
    }

    private function _get_data($var)
    {
        if (!$this->conn) {
            $this->conn = Database::getConnection();
        }
        if (empty($this->id)) {
            return null;
        }
        $id = (int)$this->id;
        $sql = "SELECT `$var` FROM `users` WHERE `id` = $id LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return array_key_exists($var, $row) ? $row[$var] : null;
        }
        return null;
    }

    //This function helps to  set the data in the database
    private function _set_data($var, $data)
    {
        if (!$this->conn) {
            $this->conn = Database::getConnection();
        }
        if (empty($this->id)) {
            return false;
        }
        $id = (int)$this->id;
        $safe = $this->conn->real_escape_string($data);
        $sql = "UPDATE `users` SET `$var`='" . $safe . "' WHERE `id`=$id LIMIT 1";
        return (bool)$this->conn->query($sql);
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function authenticate()
    {
    }

    public function setDob($year, $month, $day)
    {
        if (checkdate($month, $day, $year)) { 
            return $this->_set_data('dob', sprintf('%04d-%02d-%02d', (int)$year, (int)$month, (int)$day));
        } else return false;
    }

}

