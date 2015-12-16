<?php

namespace ntentan\config;

class Config
{
    /**
     *
     * @var Data
     */
    protected static $data;
    
    public static function init($path)
    {
        static::$data = new Data($path);
    }
    
    public static function get($key, $default = null)
    {
        return static::$data->isKeySet($key) ? static::$data->get($key) : $default;
    }
    
    public static function set($key, $value)
    {
        return static::$data->set($key, $value);
    }
    
    public static function setContext($context)
    {
        static::$data->setContext($context);
    }
}
