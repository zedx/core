<?php

namespace Tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        m::close();
        $this->init();
    }

    protected function init()
    {
    }
}
