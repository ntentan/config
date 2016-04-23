<?php

namespace ntentan\config;

class Config
{
    /**
     *
     * @var Data
     */
    protected static $data;
    
    /**
     * Read a path and append its contents to the current configuration.
     * This method will read a path and append the contents of the path to 
     * the current configuration. The path could either be a directory or a
     * file. Directories are parsed recursively for config files. First level
     * sub-directories are used as contexts which can modify some of the 
     * configuration settings in the main directory.
     * 
     * @param string $path The path to read
     * @param string $namespace The namespace into which the configuration should be read.
     */
    public static function readPath($path, $namespace = null)
    {
        static::$data = new Data($path, $namespace);
    }
    
    /**
     * 
     * @return Data
     */
    private static function getData()
    {
        if(!static::$data) {
            static::$data = new Data();
        }
        return static::$data;
    }
    
    public static function get($key, $default = null)
    {
        return self::getData()->isKeySet($key) ? static::$data->get($key) : $default;
    }
    
    public static function set($key, $value)
    {
        return self::getData()->set($key, $value);
    }
    
    public static function setContext($context)
    {
        static::$data->setContext($context);
    }
    
    public static function reset()
    {
        static::$data = null;
    }    
}
