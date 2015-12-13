<?php
namespace ntentan\config\tests;

require 'vendor/autoload.php';

use ntentan\config\ConfigManager;

class ConfigTests extends \PHPUnit_Framework_TestCase
{   
    public function testPlain() 
    {
        ConfigManager::init(__DIR__ . '/../fixtures/config/plain');
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
        $this->assertEquals('default', ConfigManager::get('unset', 'default'));
        $this->assertEquals(null, ConfigManager::get('unset'));
    }
    
    private function runArrayAssertions($config)
    {
        foreach($config as $key => $value) {
            $this->assertEquals($value, ConfigManager::get($key));
        }        
    }
    
    public function testContextualized()
    {
        ConfigManager::init(__DIR__ . '/../fixtures/config/contexts');
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
            ConfigManager::get('db')
        );
        ConfigManager::setContext('test');
        $this->runArrayAssertions($config['test']);
        
        $this->assertEquals(
            array (
                'datastore' => 'mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => NULL,
                'name' => 'test',
            ),
            ConfigManager::get('db')
        );
        
        ConfigManager::setContext('production');
        $this->runArrayAssertions($config['production']);
    }
    
    public function testSet()
    {
        ConfigManager::init(__DIR__ . '/../fixtures/config/contexts');
        $this->assertEquals(
            array (
                'datastore' => 'mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'root',
                'name' => 'production',
            ),
            ConfigManager::get('db')
        );
        ConfigManager::set('db.name', 'changed');
        $this->assertEquals(
            array (
                'datastore' => 'mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'root',
                'name' => 'changed',
            ),
            ConfigManager::get('db')
        );
        $this->assertEquals('changed', ConfigManager::get('db.name'));
    }
    
    public function testConfigFile()
    {
        ConfigManager::init(__DIR__ . '/../fixtures/config/file.php');
        $this->assertEquals(true, ConfigManager::get('dump'));
    }
}
