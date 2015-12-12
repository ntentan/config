<?php

namespace ntentan\config;

class Config
{
    /**
     *
     * @var Data
     */
    private static $data;
    
    public static function init($path)
    {
        self::$data = new Data($path);
    }
    
    public static function get($key)
    {
        return self::$data->get($key);
    }
    
    public static function set($key, $value)
    {
        return self::$data->set($key, $value);
    }
    
    public static function setContext($context)
    {
        self::$data->setContext($context);
    }
    
    public static function reset()
    {
        self::$data = null;
    }
}