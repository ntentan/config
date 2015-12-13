<?php
namespace ntentan\config;

class File
{
    public static function read($file, $prefix = null)
    {
        return self::expand(require $file, $prefix);
    }
    
    private static function expand($array, $prefix)
    {
        $config = [];
        if(is_array($array)) {
            $dot = $prefix ? "$prefix." : "";
            foreach($array as $key => $value) {
                $config[$dot.$key] = $value;
                $config += self::expand($value, $dot.$key);
            }
            $config[$prefix] = $array;
        }
        return $config;
    }
}