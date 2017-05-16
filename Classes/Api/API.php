<?php

namespace Api;

/**
 * @author
 * Banjo Mofesola Paul
 * Chief Developer, Planet NEST
 * mofesolapaul@planetnest.org
 * 16/05/2017 11:16
 *
 * API request structure: /<class>/<method>/[<id>]
 */
abstract class API
{
    /**
     * HTTP request method
     * @var
     */
    public $method;

    /**
     * Synonym for target class
     * @var
     */
    protected $entity;

    /**
     * Synonym for target method
     * @var
     */
    protected $endpoint;

    /**
     * Args
     * @var
     */
    public $args;

    /**
     * Http data (file) passed via put
     * @var
     */
    public $file;

    /**
     * Data passed via HTTP
     * @var
     */
    public $request;

    /**
     * Our class tree
     * @var array
     */
    protected $class_tree = [
        'users' => \Users::class
    ];

    /**
     * API constructor.
     * Enabling CORS & sorting stuff
     * @param $request
     * @throws \Exception
     */
    public function __construct($request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        #!- fetch the args
        $this->args = explode('/', $request);
        $this->dieTest();

        #!- fetch the entity (class)
        $this->entity = array_shift($this->args);
        $this->dieTest();

        #!- fetch the endpoint (method)
        $this->endpoint = array_shift($this->args);

        #!- figure out the HTTP request method
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            switch ($_SERVER['HTTP_X_HTTP_METHOD']) {
                case 'PUT':
                    $this->method = 'PUT';
                    break;
                case 'DELETE':
                    $this->method = 'DELETE';
                    break;
                case 'PATCH':
                    $this->method = 'PATCH';
                    break;
                default:
                    throw new \Exception("Invalid request method");
                    break;
            }
        }

        switch ($this->method) {
            case 'DELETE':
            case 'PATCH':
            case 'POST':
                $this->request = $this->_cleanInputs($_POST);
                break;
            case 'GET':
                $this->request = $this->_cleanInputs($_GET);
                break;
            case 'PUT':
                $this->request = $this->_cleanInputs($_GET);
                $this->file = file('php://input');
                break;
        }
    }

    /**
     * Tests if an exception must be thrown, based on args length
     */
    private function dieTest()
    {
        if (count($this->args) == 0) throw new \Exception("Invalid API request");
    }

    /**
     * Recursively cleans an array input
     * @param $data
     * @return array|string
     */
    private function _cleanInputs($data)
    {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }

    /**
     * Error disambiguation
     * @param $code
     * @return mixed
     */
    private function _requestStatus($code)
    {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error'
        );
        return ($status[$code]) ? $status[$code] : $status[500];
    }

    /**
     * Process data, and output appropriate header
     * @param $data
     * @param int $status
     * @return string
     */
    private function _response($data, $status = 200)
    {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        return json_encode($data);
    }

    /**
     * Performs the API operation
     * @return string
     */
    public function processAPI()
    {
        $class = $this->getClass($this->entity);

        if ($class->hasMethod($this->endpoint)) {
            return $this->_response($class->{$this->endpoint}($this));
        }
        return $this->_response("No Endpoint: $this->endpoint", 404);
    }

    /**
     * @param $classname
     * @return \Apiable
     * @throws \Exception
     */
    private function getClass($classname) {
        $classname = strtolower($classname);

        if (!array_key_exists($classname, $this->class_tree)) throw new \Exception('API Invalid entity call');
        return new $this->class_tree[$classname];
    }
}