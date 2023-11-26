<?php
function customAutoloader($class){
    $file = str_replace('\\', '/', $class) . '.php';
    $path=__DIR__.'/../../';
    include_once($path.$file);

}
spl_autoload_register('customAutoloader')
?>