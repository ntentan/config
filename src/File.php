<?php
namespace ntentan\config;

class File
{
    public static function read($file, $prefix = null)
    {
        $config = [];
        $dot = $prefix ? "$prefix." : "";
        $fileContents = require("$file");
        if(is_array($fileContents)) {
            foreach($fileContents as $key => $value) {
                $config["{$dot}{$key}"] = $value;
            }
            if($prefix) {
                $config[$prefix] = $fileContents;
            }
        }        
        return $config;
    }
}