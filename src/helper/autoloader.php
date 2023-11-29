<?php
/**
 * This function will load the needed classes
 *
 * @param array<int, string>|string $class
 * @return void
 */
function customAutoloader(array|string $class): void
{
    if (is_array($class)) {
        $class = implode('/', $class);
    }
    $file = str_replace('\\', '/', $class) . '.php';
    $path=__DIR__.'/../../';
    include_once($path.$file);
}
spl_autoload_register('customAutoloader')
?>
