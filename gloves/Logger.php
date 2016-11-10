<?php

namespace Gloves;

defined('ABSPATH') or die('No script kiddies please!');
/**
 * Simple logger which saves communicates in the given file
 */
class Logger
{

    protected static $dir;

    public static function init($dir)
    {
        static::$dir = $dir;
        if (WP_DEBUG === true && isset(static::$dir)) {
            $file = fopen(static::$dir, "a");
            fwrite($file, "Init\n");
            fclose($file);
        }
    }

    public static function write($log)
    {
        if (WP_DEBUG === true && isset(static::$dir)) {
            $file = fopen(static::$dir, "a");
            if (is_array($log) || is_object($log)) {
                fwrite($file, json_encode($log) . "\n");
            } elseif (is_bool($log)) {
                if ($log) {
                    fwrite($file, "true\n");
                } else {
                    fwrite($file, "false\n");
                }
            } else {
                fwrite($file, $log . "\n");
            }
            fclose($file);
        }
    }
}
