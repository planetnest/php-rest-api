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
abstract class Base
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
    protected $resource;

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
    protected $classmap = [];

    /**
     * API constructor.
     * Enabling CORS & sorting stuff
     * @param $request
     */
    public function __construct($request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        #!- fetch the pieces: expects resource/endpoint/{args}
        $this->args = sanitize( preg_split('#/#', $request, -1, PREG_SPLIT_NO_EMPTY));
        $this->resource = array_shift($this->args);
        $this->endpoint= array_shift($this->args) ?? 'index'; // use index as default
        $this->shouldThrowError();

        $this->method = determineHttpMethod();

        #!- figure out the HTTP request method
        $this->method = $_SERVER['REQUEST_METHOD'];
        switch ($this->method) {
            case 'GET':
                $this->request = sanitize($_GET);
                break;
            case 'POST':
                $this->request = sanitize($_POST);
                break;
        }
    }

    /**
     * Performs the API operation
     * @return string
     */
    public function execute() {
        $resource = $this->makeResource($this->resource);
        if ( method_exists($resource, $this->endpoint) )
            response($resource->{$this->endpoint}($this));

        response("No endpoint: {$this->endpoint}", 404);
    }

    /**
     * @param $classname
     * @return Class
     */
    private function makeResource($classname) {
        $classname = strtolower($classname);
        if (!array_key_exists($classname, $this->classmap))
            response("Invalid resource", 404);
        return new $this->classmap[$classname];
    }

    /**
     * Tests if an exception must be thrown, based on presence of necessary parts
     */
    private function shouldThrowError()
    {
        if (!$this->resource || !$this->endpoint) response("Invalid API request", 400);
    }
}