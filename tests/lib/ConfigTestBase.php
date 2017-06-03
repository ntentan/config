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
    protected function runArrayAssertions($expected, $config)
    {
        foreach($expected as $key => $value) {
            $this->assertEquals($value, $config->get($key));
        }        
    }   
}
