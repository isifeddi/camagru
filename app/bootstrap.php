<?php 

// load config 
require_once 'config/config.php';

//load helper
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';

//autoload Core libraries
spl_autoload_register(function($className){
    require_once 'libraries/' . $className . '.php';
});