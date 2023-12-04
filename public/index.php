<?php

use src\controller\ViewController;
use src\helper;

session_start();
//include_once('..\src\helper\autoloader.php');
include_once('../src/helper/autoloader.php');

$routes = include_once('../src/routes/routes.php');
// This page will be the entry page for all requests
// based on the url the wanted route will be choosen and processed
$requestedUri = ($_SERVER['REQUEST_URI']);
$requestedMethod = ($_SERVER['REQUEST_METHOD']);
// split the entered uri into single fragments
$splittedUri = explode('/', $requestedUri);
$splittedUri[1] != "" ? $controller = $splittedUri[1] : $controller = "home"; // seting "home" as fallback when only domain is called
$id = null;
if (count($splittedUri) > 2) {
    $action = $splittedUri[2];
    if (count($splittedUri)>3) {
        $id = (int)$splittedUri[3];
    }
} else {
    $action = '';
}

executeRoute($controller, $action, $routes, $requestedMethod, $id);

/**
 * redirect to the correct controller and action
 *
 * @param string $controller
 * @param string $action
 * @param array<string, array<string, array<string, string>>> $routes
 * @param string $requestedMethod
 * @return void
 */
function executeRoute(string $controller, string $action, array $routes, string $requestedMethod, ?int $id): void
{
    // Trying to get the routes endpoint if the route is completely defined in the routesfile
    $routeIsValid = isValidRoute($controller, $action, $routes, $requestedMethod);
    if ($routeIsValid) {
        // fetch internal functionname
        $controllerFunction = strtolower($requestedMethod) . $routes[$controller][$requestedMethod][$action];
        // prepare Controller namespace for calling the Class
        $controllername = ucfirst($controller).'Controller';

        $controllerNamespace = "\\src\\controller\\$controllername";
        $controller = new $controllerNamespace();
        // trigger the function in controller
        $controller->$controllerFunction($id);
    } else {
        new ViewController('notFound'); // redirect if no route is defined
        // todo change with exception handling
    }
}

/**
 * This function will test if there is a defined internal route based on the wanted url parameter
 *
 * @param string $controller
 * @param string $action
 * @param array<string, array<string, array<string, string>>> $routes
 * @param string $requestedMethod
 * @return bool
 */
function isValidRoute(string $controller, string $action, array $routes, string $requestedMethod): bool
{
    // check if route is defined in routes.php
    $controllerExists = in_array($controller, (array_keys($routes)));
    if (!$controllerExists) {
        return false; // stop here if controller is not correct
    }
    $actionExists = in_array($action, array_keys($routes[$controller][$requestedMethod]));
    return ($actionExists); // here we must only return if action is ok too, because controller is already checked
}

?>
