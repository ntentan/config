<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\config\tests\cases;
use ntentan\config\Config;

/**
 * Description of NamespaceTest
 *
 * @author ekow
 */
class NamespaceTest extends \ntentan\config\tests\lib\ConfigTestBase
{
    public function testPlain() 
    {
        Config::readPath(__DIR__ . '/../fixtures/config/plain', 'nmspc');
        $config = [
            'nmspc:app.debug' => true,
            'nmspc:app.caching.driver' => 'redis',
            'nmspc:app.caching.host' => 'redis.mytestserver.tld',
            'nmspc:app.caching' => 
            array (
              'driver' => 'redis',
              'host' => 'redis.mytestserver.tld',
            ),
            'nmspc:app' => 
            array (
              'debug' => true,
              'caching' => 
              array (
                'driver' => 'redis',
                'host' => 'redis.mytestserver.tld',
              ),
            ),
            'nmspc:db.datastore' => 'mysql',
            'nmspc:db.host' => 'localhost',
            'nmspc:db.user' => 'root',
            'nmspc:db.password' => 'root',
            'nmspc:db.name' => 'production',
            'nmspc:db' => 
            array (
              'datastore' => 'mysql',
              'host' => 'localhost',
              'user' => 'root',
              'password' => 'root',
              'name' => 'production',
            ),
        ];
        $this->runArrayAssertions($config);
        $this->assertEquals('default', Config::get('unset', 'default'));
        $this->assertEquals(null, Config::get('unset'));
    }   
    
    public function testContextualized()
    {
        Config::readPath(__DIR__ . '/../fixtures/config/contexts', 'nmspc');
        $config = array (
                'default' => 
                array (
                  'nmspc:app.debug' => false,
                  'nmspc:app.caching' => 
                  array (
                    'driver' => 'redis',
                    'host' => 'redis.mytestserver.tld',
                  ),
                  'nmspc:app' => 
                  array (
                    'debug' => false,
                    'caching' => 
                    array (
                      'driver' => 'redis',
                      'host' => 'redis.mytestserver.tld',
                    ),
                  ),
                  'nmspc:db.datastore' => 'mysql',
                  'nmspc:db.host' => 'localhost',
                  'nmspc:db.user' => 'root',
                  'nmspc:db.password' => 'root',
                  'nmspc:db.name' => 'production',
                  'nmspc:db' => 
                  array (
                    'datastore' => 'mysql',
                    'host' => 'localhost',
                    'user' => 'root',
                    'password' => 'root',
                    'name' => 'production',
                  ),
                ),
                'production' => 
                array (
                  'nmspc:app.debug' => false,
                  'nmspc:app.caching' => 
                  array (
                    'driver' => 'redis',
                    'host' => 'redis.mytestserver.tld',
                  ),
                  'nmspc:app' => 
                  array (
                    'debug' => false,
                    'caching' => 
                    array (
                      'driver' => 'redis',
                      'host' => 'redis.mytestserver.tld',
                    ),
                  ),
                  'nmspc:db.datastore' => 'mysql',
                  'nmspc:db.host' => 'localhost',
                  'nmspc:db.user' => 'root',
                  'nmspc:db.password' => 'root',
                  'nmspc:db.name' => 'production',
                  'nmspc:db' => 
                  array (
                    'datastore' => 'mysql',
                    'host' => 'localhost',
                    'user' => 'root',
                    'password' => 'root',
                    'name' => 'production',
                  ),
                ),
                'test' => 
                array (
                  'nmspc:app.debug' => true,
                  'nmspc:app.caching' => 
                  array (
                    'driver' => 'file',
                    'host' => '/cache/dir',
                  ),
                  'nmspc:app' => 
                  array (
                    'debug' => true,
                    'caching' => 
                    array (
                      'driver' => 'file',
                      'host' => '/cache/dir',
                    ),
                  ),
                  'nmspc:db.datastore' => 'mysql',
                  'nmspc:db.host' => 'localhost',
                  'nmspc:db.user' => 'root',
                  'nmspc:db.password' => null,
                  'nmspc:db.name' => 'test',
                  'nmspc:db' => 
                  array (
                    'datastore' => 'mysql',
                    'host' => 'localhost',
                    'user' => 'root',
                    'password' => null,
                    'name' => 'test',
                  ),
                ),
              );
        
        $this->runArrayAssertions($config['default']);
        
        $this->assertEquals(
            array (
                'datastore' => 'mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'root',
                'name' => 'production',
            ),
            Config::get('nmspc:db')
        );
        Config::setContext('test');
        $this->runArrayAssertions($config['test']);
        
        $this->assertEquals(
            array (
                'datastore' => 'mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => NULL,
                'name' => 'test',
            ),
            Config::get('nmspc:db')
        );
        
        Config::setContext('production');
        $this->runArrayAssertions($config['production']);
    }
}
