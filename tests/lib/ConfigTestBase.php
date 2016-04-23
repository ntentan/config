<?php

namespace ntentan\config\tests\lib;

use ntentan\config\Config;

/**
 * Description of ConfigTest
 *
 * @author ekow
 */
class ConfigTestBase extends \PHPUnit_Framework_TestCase
{
    protected function runArrayAssertions($config)
    {
        foreach($config as $key => $value) {
            $this->assertEquals($value, Config::get($key));
        }        
    }    
    
    public function tearDown()
    {
        parent::tearDown();
        Config::reset();
    }
}
