<?php

error_reporting(E_ALL ^ E_DEPRECATED);
require_once 'REST.class.php';

class API extends REST
{
    public $data = "";
    private $current_call;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Public method for access api.
     * This method dynamically calls the method based on the query string
     */
    public function processApi()
    {
        $request = strtolower(trim($_REQUEST['rquest']));
        $func = basename($request);
        if (!isset($_GET['namespace']) and (int)method_exists($this, $func) > 0) {
            $this->$func();
        } else {
            if (isset($_GET['namespace'])) {
                $dir = dirname(__DIR__) . '/api/' . $_GET['namespace'];
                $file = $dir . '/' . $request . '.php';
                if (file_exists($file)) {
                    include $file;
                    $this->current_call = Closure::bind(${$func}, $this, get_class());
                    $this->$func();
                } else {
                    $this->response($this->json(['error' => 'method_not_found']), 404);
                }
            } else {
                $this->response($this->json(['error' => 'method_not_found']), 404);
            }
        }
    }

    public function isAuthenticated()
    {
        return Session::isAuthenticated();
    }

    /**
     * Checks if all supplied parameters exist in the request
     */
    public function paramsExists($parms = array())
    {
        if (!is_array($parms)) {
            $parms = array($parms);
        }
        $exists = true;
        foreach ($parms as $param) {
            if (!array_key_exists($param, $this->_request)) {
                $exists = false;
            }
        }
        return $exists;
    }

    public function isAuthenticatedFor(User $user)
    {
        return Session::getUser()->getEmail() == $user->getEmail();
    }

    public function getUsername()
    {
        return Session::getUser()->getUsername();
    }

    public function die($e)
    {
        $data = [
            "error" => $e->getMessage(),
            "type" => "death"
        ];
        $response_code = 400;
        if ($e->getMessage() == "Expired token" || $e->getMessage() == "Unauthorized") {
            $response_code = 403;
        }
        if ($e->getMessage() == "Not found") {
            $response_code = 404;
        }
        $data = $this->json($data);
        $this->response($data, $response_code);
    }

    public function __call($method, $args)
    {
        if (is_callable($this->current_call)) {
            return call_user_func_array($this->current_call, $args);
        } else {
            $error = ['error' => 'method_not_callable', 'method' => $method];
            $this->response($this->json($error), 404);
        }
    }

    /*************API SPACE START*******************/

    private function test()
    {
        $data = $this->json(getallheaders());
        $this->response($data, 200);
    }

    /*************API SPACE END*********************/

    /**
     * Encode array into JSON
     */
    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data, JSON_PRETTY_PRINT);
        } else {
            return "{}";
        }
    }
}
