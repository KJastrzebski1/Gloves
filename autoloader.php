<?php
ini_set('include_path', dirname(__FILE__));
function autoload($class){
    $paths = explode(PATH_SEPARATOR, get_include_path());
    
    $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
    $file = str_replace("\\", DIRECTORY_SEPARATOR, trim($class, "\\")).".php";
    
    foreach ($paths as $path){
        $combined = $path.DIRECTORY_SEPARATOR.$file;
        if(file_exists($combined)){
            include($combined);
            return;
        }
    }
    throw new Exception("{$class} not found");
}
spl_autoload_register('autoload');