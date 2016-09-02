<?php

set_include_path(__DIR__ . PATH_SEPARATOR . get_include_path());

spl_autoload_register(function($class) {
    $paths = explode(PATH_SEPARATOR, get_include_path());

    $file = str_replace("\\", DIRECTORY_SEPARATOR, trim($class, "\\")) . ".php";


    $combined = stream_resolve_include_path($file);
    if (file_exists($combined)) {
        include($combined);
        return;
    }
});
