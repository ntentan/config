<?php

namespace ntentan\config;

class Config
{

    private $config;
    private $context = 'default';
    
    /**
     * Reads the configurations stored at a given path.
     * 
     * Path could either point to a directory or a specific file. In the case of
     * a file, the contents of the file are read into the config object. However,
     * in the case of a directory, the contents of all the files in the directory 
     * are read into the config file with configurations from each file prefixed with
     * the file name.
     *
     * @param string $path
     * @return void
     */
    public function readPath($path) {
        if (is_dir($path)) {
            $dir = new Directory($path);
            $this->config = ['default' => $this->expand($dir->parse())];
            foreach ($dir->getContexts() as $context) {
                $this->config[$context] = array_merge(
                    $this->config['default'], $this->expand($dir->parse($context))
                );
            }
        } else if (is_file($path)) {
            $this->config[$this->context] = $this->expand(File::read($path));
        } else {
            throw new \ntentan\utils\exceptions\FileNotFoundException($path);
        }
    }

    public function isKeySet($key) {
        return isset($this->config[$this->context][$key]);
    }

    public function get($key, $default = null) {
        return isset($this->config[$this->context][$key]) ? $this->config[$this->context][$key] : $default;
    }

    public function setContext($context) {
        $this->context = $context;
    }

    public function set($key, $value) {
        $keys = explode('.', $key);
        $this->config[$this->context] = $this->setValue($keys, $value, $this->config[$this->context]);
        $this->config[$this->context][$key] = $value;
        if (is_array($value)) {
            $this->config[$this->context] += $this->expand($value, $key);
        }
    }

    private function setValue($keys, $value, $config) {
        if (!empty($keys)) {
            $key = array_shift($keys);
            $config[$key] = $this->setValue($keys, $value, isset($config[$key]) ? $config[$key] : []);
            return $config;
        } else {
            return $value;
        }
    }

    private function expand($array, $prefix = null) {
        $config = [];
        if (!is_array($array))
            return $config;
        $dottedPrefix = $prefix ? "$prefix." : "";
        foreach ($array as $key => $value) {
            $newPrefix = $dottedPrefix . $key;
            $config[$newPrefix] = $value;
            $config += $this->expand($value, $newPrefix);
        }
        if ($prefix)
            $config[$prefix] = $array;
        return $config;
    }

}
