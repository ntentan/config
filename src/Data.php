<?php

namespace ntentan\config;

class Data
{

    private $config;
    private $context = 'default';

    public function __construct($path)
    {
        if(is_dir($path)) {
            $dir = new Directory($path);
            $this->config = ['default' => $dir->parse()];
            foreach ($dir->getContexts() as $context) {
                $this->config[$context] = array_merge(
                    $this->config['default'], $dir->parse($context)
                );
            }
        } else if(is_file($path)) {
            $this->config = File::read($path);
        }
    }
    
    public function isKeySet($key)
    {
        return isset($this->config[$this->context][$key]);
    }

    public function get($key)
    {
        if ($this->isKeySet($key)) {
            return $this->config[$this->context][$key];
        }
        return null;
    }

    public function setContext($context)
    {
        $this->context = $context;
    }

    public function dump()
    {
        return $this->config;
    }

    public function set($key, $value)
    {
        $exploded = explode('.', $key);
        if (count($exploded) == 2) {
            $this->config[$this->context][$exploded[0]][$exploded[1]] = $value;
        }
        $this->config[$this->context][$key] = $value;
    }

}
