<?php

namespace ntentan\config\tests\lib;

use ntentan\config\Config;
use PHPUnit\Framework\TestCase;

/**
 * Description of ConfigTest
 *
 * @author ekow
 */
class ConfigTestBase extends TestCase
{
    protected $config;

    public function setUp() {
        $this->config = new Config();
    }

    protected function runArrayAssertions($expected, $config) {
        foreach($expected as $key => $value) {
            $this->assertEquals($value, $config->get($key));
        }        
    }   
}
