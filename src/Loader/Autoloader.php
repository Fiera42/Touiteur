<?php

namespace touiteur\loader;

class Autoloader {

    private string $prefixe;
    private string $chemin;

    public function __construct(string $prefixe, string $chemin) {
        $this->prefixe = $prefixe;
        $this->chemin = $chemin;
    }

    public function loadclass(string $classname) : void{
        //var_dump($classname);
        $classname = ltrim($classname);
        $classname = rtrim($classname);

        $filename = str_replace($this->prefixe, $this->chemin, $classname);
        $filename = str_replace('\\', '/', $filename);
        $filename = $filename.'.php';
        //var_dump($filename);
        if(is_file($filename)) require_once($filename) ;
    }

    public function register() : void {
        spl_autoload_register([$this, 'loadClass']);
    }
}
