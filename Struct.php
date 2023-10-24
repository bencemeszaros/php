<?php

class Struct {
    
    final public function __construct(...$arguments) {
        
        $class = get_class($this);
        $classProperties = get_class_vars($class); //this doesn't include dynamic properties, parent class properties
        
        foreach ($arguments as $key => $value) {
            $this->$key = $value; //this might throw TypeError (needs strict types), dynamic property error
        }
        
        foreach ($classProperties as $key => $value) {
            if (
                !isset($this->$key)
            ) {
                $this->$key = null; //this might throw TypeError (should be missing argument error)
            }
        }
    }
    
    //this method can be removed after PHP removes all support for dynamic properties
    final public function __set($key, $value) {
        $trace = debug_backtrace();
        throw new ErrorException("Creation of dynamic property " . get_class($this) . "::$" . $key . " is not allowed", 0, E_ERROR, $trace[0]["file"], $trace[0]["line"]);
    }
}
