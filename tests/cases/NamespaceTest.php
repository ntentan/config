<?php

namespace ntentan\config\tests\cases;
use ntentan\config\Config;
use ntentan\config\tests\lib\ConfigTestBase;

/**
 * Description of NamespaceTest
 *
 * @author ekow
 */
class NamespaceTest extends ConfigTestBase
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
    
    public function testEmptySet()
    {
        Config::set('nmspc:db.driver', 'Hello');
        $this->assertEquals('Hello', Config::get('nmspc:db.driver'));
    }
    
    public function testEmptySetDirectory()
    {
        Config::set('nmspc:db.driver', [
            'key1' => 'value1',
            'key2' => 'value2'
        ]);
        $this->assertEquals(
            ['key1' => 'value1', 'key2' => 'value2'], 
            Config::get('nmspc:db.driver')
        );
    }
    
    public function testConfigFile()
    {
        Config::readPath(__DIR__ . '/../fixtures/config/file.php', 'nmspc');
        $this->assertEquals(true, Config::get('nmspc:dump'));
    }    
}
