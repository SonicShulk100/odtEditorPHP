<?php
spl_autoload_register("\\autoloader::autoLoadDTO");
spl_autoload_register("\\autoloader::autoLoadDAO");
spl_autoload_register("\\autoloader::autoLoadController");

class autoloader{
    public static function autoLoadDTO($class): void{
        $file = "models/DTO/$class.php";
        if(file_exists($file)){
            require_once $file;
        }
    }

    public static function autoLoadDAO($class): void{
        $file = "models/DAO/$class.php";
        if(file_exists($file)){
            require_once $file;
        }
    }

    public static function autoLoadController($class): void{
        $file = "controllers/controleur" . ucfirst($class) . ".php";
        if(file_exists($file)){
            require_once $file;
        }
    }
}