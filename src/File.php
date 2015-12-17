<?php
namespace ntentan\config;

class File
{
    public static function read($file, $prefix = null)
    {
        return self::expand(require $file, $prefix);
    }
}
