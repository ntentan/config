<?php
namespace ntentan\config\tests\cases;

use ntentan\config\Config;
use ntentan\config\tests\lib\ConfigTestBase;

class ConfigTests extends ConfigTestBase
{   
    public function testPlain() 
    {
        $config = Config::readPath(__DIR__ . '/../fixtures/config/plain');
        $expected = [
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
        $this->runArrayAssertions($expected, $config);
        $this->assertEquals('default', $config->get('unset', 'default'));
        $this->assertEquals(null, $config->get('unset'));
    }
    
    public function testContextualized()
    {
        $config = Config::readPath(__DIR__ . '/../fixtures/config/contexts');
        $expected = array (
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
        
        $this->runArrayAssertions($expected['default'], $config);
        
        $this->assertEquals(
            array (
                'datastore' => 'mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'root',
                'name' => 'production',
            ),
            $config->get('db')
        );
        $config->setContext('test');
        $this->runArrayAssertions($expected['test'], $config);
        
        $this->assertEquals(
            array (
                'datastore' => 'mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => NULL,
                'name' => 'test',
            ),
            $config->get('db')
        );
        
        $config->setContext('production');
        $this->runArrayAssertions($expected['production'], $config);
    }
    
    public function testSet()
    {
        $config = Config::readPath(__DIR__ . '/../fixtures/config/contexts');
        $this->assertEquals(
            array (
                'datastore' => 'mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'root',
                'name' => 'production',
            ),
            $config->get('db')
        );
        $config->set('db.name', 'changed');
        $this->assertEquals(
            array (
                'datastore' => 'mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'root',
                'name' => 'changed',
            ),
            $config->get('db')
        );
        $this->assertEquals('changed', $config->get('db.name'));
    }
    
    public function testEmptySet()
    {
        $config = new Config();
        $config->set('db.driver', 'Hello');
        $this->assertEquals('Hello', $config->get('db.driver'));
    }
    
    public function testEmptySetDirectory()
    {
        $config = new Config();
        $config->set('db.driver', [
            'key1' => 'value1',
            'key2' => 'value2'
        ]);
        $this->assertEquals(
            ['key1' => 'value1', 'key2' => 'value2'], 
            $config->get('db.driver')
        );
        $this->assertEquals('value1', $config->get('db.driver.key1'));
    }
    
    public function testConfigFile()
    {
        $config = Config::readPath(__DIR__ . '/../fixtures/config/file.php');
        $this->assertEquals(true, $config->get('dump'));
    }
}
