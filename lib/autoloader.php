<?php
spl_autoload_register("\\Autoloader::autoLoadDTO");
spl_autoload_register("\\Autoloader::autoLoadDAO");
spl_autoload_register("\\Autoloader::autoLoadController");

class Autoloader{
    static function autoLoadDTO($class): void{
        $file = "models/DTO/$class.php";
        if(file_exists($file)){
            require_once $file;
        }
    }

    static function autoLoadDAO($class): void{
        $file = "models/DAO/$class.php";
        if(file_exists($file)){
            require_once $file;
        }
    }

    static function autoLoadController($class): void{
        $file = "controllers/controleur" . ucfirst($class) . ".php";
        if(file_exists($file)){
            require_once $file;
        }
    }
}