<?php
function customAutoloader($class){
    $file = '..\\'.str_replace('/','\\',$class).'.php';
    include_once($file);

}
spl_autoload_register('customAutoloader')
?>