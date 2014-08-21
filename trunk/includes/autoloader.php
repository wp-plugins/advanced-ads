<?php

/**
 * autoloader
 *
 * currently all classes of real objects (e.g. ads) can be found in /classes on the plugins root directory
 * all classes have the "Advads_" prefix to prevent any conflicts with other plugins
 * but filenames don’t use "advads" and are written in lower case
 *  e.g. Advads_Ad is in file classes/ad.php
 *
 * to be able to change this structure later, I use an autoloader here
 */
class Advads_Autoloader {

    public static function load($classname) {
        // to lower case
        $classname = strtolower($classname);

        // strip "advads_" prefix
        $classname = str_replace('advads_', '', $classname);

        $filepath = ADVADS_BASE_PATH . 'classes' . DIRECTORY_SEPARATOR . $classname . '.php';

        if (file_exists($filepath)) {
            require_once $filepath;
        } else {
            return false;
        }
    }

}
