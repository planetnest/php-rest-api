<?php

namespace Traits;

use \Api\Db;

/**
 * @author
 * Banjo Mofesola Paul
 * Chief Developer, Planet NEST
 * mofesolapaul@planetnest.org
 * 24/03/2018 09:48
 */
trait DbTransaction
{
    protected $db;

    public function __construct()
    {
        $this->_setupDbConnection();
    }

    public function _setupDbConnection()
    {
        $this->db = Db::init();
    }
}