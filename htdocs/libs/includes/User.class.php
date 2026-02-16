<?php

include_once __DIR__ . "/../traits/SQLGetterSetter.trait.php";

class User
{
    use SQLGetterSetter;

    const TABLE = 'auth';
    
    private $conn;
    private $username;
    public $id;
    private $table = self::TABLE;

    public function __construct($username)
    {
        $this->conn = Database::getConnection();
        $this->id = null;
        
        // Handle case where an array is passed (e.g., from login result)
        // This is much faster as it avoids an extra DB query
        if (is_array($username)) {
            if (isset($username['id']) && isset($username['username'])) {
                $this->id = $username['id'];
                $this->username = $username['username'];
                return; // Early return, we have everything we need
            } elseif (isset($username['username'])) {
                $username = $username['username'];
            } elseif (isset($username['id'])) {
                $username = $username['id'];
            }
        }
        
        $u = $this->conn->real_escape_string($username);
        $sql = "SELECT `id`, `username` FROM `$this->table` WHERE `username`= '$u' OR `id` = '$u' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $this->id = $row['id'];
            $this->username = $row['username'];
        } else {
            throw new Exception("User not found: $username");
        }
    }


    public static function signup($user, $pass, $email, $phone)
    {           
        $options = [
            'cost' => 9,
        ];
        $pass = password_hash($pass, PASSWORD_BCRYPT, $options);
        $conn = Database::getConnection();
        
        $user_esc = $conn->real_escape_string($user);
        $email_esc = $conn->real_escape_string($email);
        $check_sql = "SELECT `id` FROM `" . self::TABLE . "` WHERE `username` = '$user_esc' OR `email` = '$email_esc' LIMIT 1";
        $check_res = $conn->query($check_sql);
        if ($check_res && $check_res->num_rows > 0) {
            return "Username or Email already exists.";
        }

        $sql = "INSERT INTO `" . self::TABLE . "` (`username`, `password`, `email`, `phone`, `active`) VALUES ('"
            . $user_esc . "', '" 
            . $conn->real_escape_string($pass) . "', '" 
            . $email_esc . "', '" 
            . $conn->real_escape_string($phone) . "', '0')";

        try {
            if ($conn->query($sql) === true) {
                return true;
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
        $query = "SELECT * FROM `" . self::TABLE . "` WHERE (`username` = '$user_esc' OR `email` = '$user_esc') AND `blocked` = '0' LIMIT 1";
        $result = $conn->query($query);
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (isset($row['password']) && password_verify($pass, $row['password'])) {
                return $row;
            }
        }

        return false;
    }

    public function deleteUser()
    {
        if (empty($this->id)) return false;
        $id = (int)$this->id;
        $conn = Database::getConnection();
        
        $conn->begin_transaction();
        try {
            $conn->query("DELETE FROM `users` WHERE `id` = $id");
            $conn->query("DELETE FROM `session` WHERE `uid` = $id");
            $conn->query("DELETE FROM `$this->table` WHERE `id` = $id");
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setDob($year, $month, $day)
    {
        if (checkdate($month, $day, $year)) { 
            return $this->_set_data('dob', sprintf('%04d-%02d-%02d', (int)$year, (int)$month, (int)$day));
        } else {
            return false;
        }
    }

    // --- Dual-table routing for profile vs auth fields ---

    /**
     * Fields stored in the 'users' table (profile data).
     * Everything else is assumed to be in the 'auth' table.
     */
    private static $profile_fields = [
        'avatar', 'bio', 'firstname', 'lastname',
        'dob', 'instagram', 'twitter', 'facebook'
    ];

    /**
     * Override trait's _get_data to route profile fields to 'users' table.
     */
    private function _get_data($var)
    {
        $table = in_array($var, self::$profile_fields) ? 'users' : $this->table;
        try {
            if (!$this->conn) {
                $this->conn = Database::getConnection();
            }
            $sql = "SELECT `$var` FROM `$table` WHERE `id` = $this->id";
            $result = $this->conn->query($sql);
            if ($result && $result->num_rows == 1) {
                return $result->fetch_assoc()[$var];
            }
            return null;
        } catch (Exception $e) {
            // Profile fields may not exist yet — return null gracefully
            return null;
        }
    }

    /**
     * Override trait's _set_data to route profile fields to 'users' table.
     */
    private function _set_data($var, $data)
    {
        $table = in_array($var, self::$profile_fields) ? 'users' : $this->table;
        try {
            if (!$this->conn) {
                $this->conn = Database::getConnection();
            }
            $safe = $this->conn->real_escape_string($data);
            $sql = "UPDATE `$table` SET `$var`='$safe' WHERE `id`=$this->id";
            if ($this->conn->query($sql)) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}