<?php
include dirname(__FILE__, 4) . '/wp-config.php';
include 'autoloader.php';
if (PHP_SAPI !== 'cli') {
    echo 'Nope.';
    exit();
}

echo "Welcome in Gloves CLI.\n";

class GlovesCLI {

    protected $function;
    protected $args;
    protected $config;

    public function __construct($args) {
        $this->config = include __DIR__.'/conf.php';
        if (isset($args[1])) {
            $this->function = $args[1];
            $method = $this->function;
            if (method_exists($this, $method)) {
                $arg = isset($args[2]) ? $args[2] : null;
                $arg2 = isset($args[3]) ? str_replace('-', '', $args[3]) : null;
                $this->$method($arg, $arg2);
            } else {
                $this->help();
            }
        } else {
            $this->help();
        }
    }

    /**
     * Shows help for CLI
     */
    private function help() {
        $methods = get_class_methods(__CLASS__);
        echo "\nTry use functions from below: \n";

        foreach ($methods as $method) {
            if ($method == "__construct") {
                continue;
            }
            echo "\t- $method\n";
        }
    }

    /**
     * Creates main plugin file based on data from conf.php
     */
    protected function setup() {
        $name = $this->config['name'];
        $title = "Plugin Name: ".$name;
        
        $domain = $this->config['text-domain'];
        $class = str_replace(' ', '', $name);
        $slug = strtolower(str_replace(' ', '-', $name));
        if(file_exists($slug.'.php')){
            echo "Plugin file already exists.";
            exit();
        }
        echo "Creating $name plugin.\n";
        $file = fopen($slug . '.php', 'w');
        $content = "<?php

/*
 * $title
 * Description: Made in Gloves
 * Author: 
 * Text Domain: $domain
 * Domain Path: 
 * Version: 0.0.1
 * License: GPL3
 * 
 */
include 'autoloader.php';

use Gloves\Plugin;

class $class extends Plugin {

    protected static \$modules = [];
    protected static \$models = [];
    protected static \$settings = [];
    
    public static function init() {
        parent::init();
    }
    
    public static function activate() {
        parent::activate();
    }
    
    public static function deactivate() {
        parent::deactivate();
    }
    
    public static function uninstall() {
        parent::uninstall();
    }
}

$class::init();";

        fwrite($file, $content);
        fclose($file);
    }

    /**
     * Creates Module based on template from Gloves\Template
     * 
     * @param string $name
     * @param string $template
     */
    protected function make_module($name, $template = 'standard'){
        
        echo "Creating Module based on $template.\n";
        
        $template = "Gloves\\Template\\".ucfirst(strtolower($template)).'.php';
        
        
        if(!file_exists($template)){
            echo ("Template doesn't exist.\n");
            echo "Try using one from below. \n";
            $content = scandir(dirname($template));
            foreach ($content as $file){
                if($file == ".." || $file == "."){
                    continue;
                }
                echo "\t-".str_replace(".php", '', $file)."\n";
            }
            exit();
        }
        $dest = "Module\\$name.php";
        if(!copy($template, $dest)){
            echo "Couldn't copy template.";
        }
    }

    /**
     * Creates model based on argument in command-line
     * 
     * @param string $name
     */
    protected function make_model($name) {
        $class = "Model\\" . $name;
        echo "Creating Model file $class...\n";
        $file = fopen($class . '.php', 'w');
        if(!$file){
            exit("Couldn't create $class.");
        }
        $content = "<?php

namespace Model;

use Gloves\Model\Model;

class $arg extends Model{
    protected static \$fields = [
    
    ];
    
    protected static \$version = '';
}";
        fwrite($file, $content);
        fclose($file);
        echo "$class file created. Go to the file and create structure of the table. After you are finished add Model name to array in your main plugin file.";
    }

    /**
     * Removes model with name
     * 
     * @param string $name
     */
    protected function remove_model($name) {
        $class = "Model\\" . $name;

        if (class_exists($class)) {
            echo "Dropping $class...\n";
            $class::drop();
        } else {
            echo "There is no such model. $class";
            exit();
        }
        echo "Table of $class dropped. Do you also want to remove file? (Y/N)";
        $stdin = fopen('php://stdin', 'r');
        $response = fgetc($stdin);
        if ($response != 'Y') {
            echo "Aborted.\n";
            exit;
        }
        if(!unlink($class.'.php')){
            exit("Couldn't remove file $class.php.");
        }
        echo "File $class removed.\nRemember to remove Model from array in your main plugin file.";
    }

}

new GlovesCLI($argv);
