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
            $this->config[$this->context] = File::read($path);
        }
    }
    
    public function isKeySet($key)
    {
        return isset($this->config[$this->context][$key]);
    }

    public function get($key)
    {
        return $this->config[$this->context][$key];
    }

    public function setContext($context)
    {
        $this->context = $context;
    }

    public function set($key, $value)
    {
        $keys = explode('.', $key);
        $this->config[$this->context] = $this->setValue($keys, $value, $this->config[$this->context]);
        $this->config[$this->context][$key] = $value;
    }
    
    private function setValue($keys, $value, $config)
    {
        if(!empty($keys)) {
            $key = array_shift($keys);
            $config[$key] = $this->setValue($keys, $value, $config[$key]);
            return $config;
        } else {
            return $value;
        }
    }

}
