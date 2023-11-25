<?php
include_once('../src/helper/autoloader.php');
$routes = include_once('../src/routes/routes.php');
// This page will be the entry page for all requests
// based on the url the wanted route will be choosen and processed

$requestedUri=($_SERVER['REQUEST_URI']);
$requestedMethod=($_SERVER['REQUEST_METHOD']);

// start with empty values
$controller = null;
$action = null;
// split the entered uri into single fragments
$splittedUri = explode('/', $requestedUri);
$controller = $splittedUri[1];
$action = $splittedUri[2];

// todo analyze parameter
/**
 * @param $controller
 * @param $routes
 * @param $action
 * @param $requestedMethod
 * @return void
 */
function isValidRoute($controller, $routes, $action, $requestedMethod)
{
// check if route is defined in routes.php
// controller check
    $controllerExists = in_array($controller, (array_keys($routes)));
//action check
    $actionExists = in_array($action, (array_keys($routes[$controller][$requestedMethod])));
    return ($controllerExists && $actionExists);
}

if (isValidRoute($controller, $routes, $action, $requestedMethod)){
    // if there is a valid route process to the wanted Controller
    $controllerNamespace = $controller;
    $controller=new src\controller\loginController();
    if($action===null){
        $action = 'index';
    }
    $controller->$action();
}

?>
