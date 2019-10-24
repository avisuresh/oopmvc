<?php
    // Load confi file
    require_once 'config/config.php';

    // Load url helpers
    require_once 'helpers/url_helpers.php';
    require_once 'helpers/session_helpers.php';
    // Load all Libraries
    /*require_once 'libraries/core.php';
    require_once 'libraries/controller.php';
    require_once 'libraries/database.php';*/

    // Autoload Core Libraries....... loads all the files from the library folder
    // Class name and file name should be the same
    spl_autoload_register(function ($className){
        require_once 'libraries/'.$className.'.php';
    });