<?php
/**
 * @author
 * Banjo Mofesola Paul
 * Chief Developer, Planet NEST
 * mofesolapaul@planetnest.org
 * 16/05/2017 12:04
 */

namespace Api;
//use Auth;


class v1 extends Base
{

    protected $classmap = [
        // map classes (Resources) here
        'users' => \Users::class
    ];

    public function __construct($request)
    {
        parent::__construct($request);
    }
}