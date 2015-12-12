<?php
namespace ntentan\config;

class Directory
{
    private $path;
    private $contexts = [];
    
    public function __construct($path)
    {
        $this->path = $path;
    }
    
    public function parse($path = '') 
    {
        $config = [];
        $dir = "$this->path/$path";
        $files = scandir($dir);
        foreach($files as $file) {
            if(fnmatch("*.conf.php", $file)) {
                $this->readFile($dir, $file, $config);
            }
            else if(is_dir("{$this->path}/$file") && $file != '.' && $file != '..' && $path === '') {
                $this->contexts[] = $file;
            }
        } 
        
        return $config;
    }
    
    public function getContexts()
    {
        return $this->contexts;
    }
    
    private function readFile($path, $file, &$config)
    {
        $prefix = substr($file, 0, -9);
        $fileContents = require("$path/$file");
        if(is_array($fileContents)) {
            foreach($fileContents as $key => $value) {
                $config["$prefix.$key"] = $value;
            }
            $config[$prefix] = $fileContents;
        }
    }    
}
