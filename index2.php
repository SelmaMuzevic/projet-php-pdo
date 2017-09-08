<?php
//spl_autoload_extensions(".php");
//spl_autoload_register();
function myLoader($className){
    $class = str_replace('\\', '/', $className);
    require($class . '.php');
}
spl_autoload_register('myLoader');
$u = new \DB\User();