<?php
namespace ntentan\config\tests\cases;

use ntentan\config\Config;
use ntentan\config\tests\lib\ConfigTestBase;

class ConfigTests extends ConfigTestBase
{   
    public function testPlain() 
    {
        Config::readPath(__DIR__ . '/../fixtures/config/plain');
        $config = [
            'app.debug' => true,
            'app.caching.driver' => 'redis',
            'app.caching.host' => 'redis.mytestserver.tld',
            'app.caching' => 
            array (
              'driver' => 'redis',
              'host' => 'redis.mytestserver.tld',
            ),
            'app' => 
            array (
              'debug' => true,
              'caching' => 
              array (
                'driver' => 'redis',
                'host' => 'redis.mytestserver.tld',
              ),
            ),
            'db.datastore' => 'mysql',
            'db.host' => 'localhost',
            'db.user' => 'root',
            'db.password' => 'root',
            'db.name' => 'production',
            'db' => 
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
        Config::readPath(__DIR__ . '/../fixtures/config/contexts');
        $config = array (
                'default' => 
                array (
                  'app.debug' => false,
                  'app.caching' => 
                  array (
                    'driver' => 'redis',
                    'host' => 'redis.mytestserver.tld',
                  ),
                  'app' => 
                  array (
                    'debug' => false,
                    'caching' => 
                    array (
                      'driver' => 'redis',
                      'host' => 'redis.mytestserver.tld',
                    ),
                  ),
                  'db.datastore' => 'mysql',
                  'db.host' => 'localhost',
                  'db.user' => 'root',
                  'db.password' => 'root',
                  'db.name' => 'production',
                  'db' => 
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
                  'app.debug' => false,
                  'app.caching' => 
                  array (
                    'driver' => 'redis',
                    'host' => 'redis.mytestserver.tld',
                  ),
                  'app' => 
                  array (
                    'debug' => false,
                    'caching' => 
                    array (
                      'driver' => 'redis',
                      'host' => 'redis.mytestserver.tld',
                    ),
                  ),
                  'db.datastore' => 'mysql',
                  'db.host' => 'localhost',
                  'db.user' => 'root',
                  'db.password' => 'root',
                  'db.name' => 'production',
                  'db' => 
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
                  'app.debug' => true,
                  'app.caching' => 
                  array (
                    'driver' => 'file',
                    'host' => '/cache/dir',
                  ),
                  'app' => 
                  array (
                    'debug' => true,
                    'caching' => 
                    array (
                      'driver' => 'file',
                      'host' => '/cache/dir',
                    ),
                  ),
                  'db.datastore' => 'mysql',
                  'db.host' => 'localhost',
                  'db.user' => 'root',
                  'db.password' => null,
                  'db.name' => 'test',
                  'db' => 
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
            Config::get('db')
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
            Config::get('db')
        );
        
        Config::setContext('production');
        $this->runArrayAssertions($config['production']);
    }
    
    public function testSet()
    {
        Config::readPath(__DIR__ . '/../fixtures/config/contexts');
        $this->assertEquals(
            array (
                'datastore' => 'mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'root',
                'name' => 'production',
            ),
            Config::get('db')
        );
        Config::set('db.name', 'changed');
        $this->assertEquals(
            array (
                'datastore' => 'mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'root',
                'name' => 'changed',
            ),
            Config::get('db')
        );
        $this->assertEquals('changed', Config::get('db.name'));
    }
    
    public function testEmptySet()
    {
        Config::set('db.driver', 'Hello');
        $this->assertEquals('Hello', Config::get('db.driver'));
    }
    
    public function testEmptySetDirectory()
    {
        Config::set('db.driver', [
            'key1' => 'value1',
            'key2' => 'value2'
        ]);
        $this->assertEquals(
            ['key1' => 'value1', 'key2' => 'value2'], 
            Config::get('db.driver')
        );
        $this->assertEquals('value1', Config::get('db.driver.key1'));
    }
    
    public function testConfigFile()
    {
        Config::readPath(__DIR__ . '/../fixtures/config/file.php');
        $this->assertEquals(true, Config::get('dump'));
    }
}
