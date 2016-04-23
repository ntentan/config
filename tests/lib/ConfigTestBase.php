<?php

namespace ntentan\config\tests\lib;

use ntentan\config\Config;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
}
