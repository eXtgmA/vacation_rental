<?php

use src\controller\ViewController;
use src\helper;

session_start();
//include_once('..\src\helper\autoloader.php');
include_once('../src/helper/autoloader.php');

//    when there are only 2 parameter given, we have to check for route get params
//    []
//    these prams start with ?
//    []
//    we have to strip off these data and pass it to the controller method
//    []
//    maybe as a named array
//

$routes = include_once('../src/routes/routes.php');
// This page will be the entry page for all requests
// based on the url the wanted route will be choosen and processed
$requestedUri = ($_SERVER['REQUEST_URI']);
$requestedMethod = ($_SERVER['REQUEST_METHOD']);
// split the entered uri into single fragments
$splittedUri = explode('/', $requestedUri);
$splittedUri[1] != "" ? $controller = $splittedUri[1] : $controller = "dashboard";
$id = null;
/**
 * @param string $getParamsString
 * @return array<string>
 */
function transformGetStringToArray(string $getParamsString)
{
    $resultArray = [];
//    separator is the '&'
    $unnamedArray = explode('&', $getParamsString);
//    now we have to explode it by the '=' and set key=> value in our result Array
    foreach ($unnamedArray as $parameter) {
        $keyValueArray = explode('=', $parameter);
        $resultArray[$keyValueArray[0]] = $keyValueArray[1];
    }
    return $resultArray;
}

$paramArray = null; // Setting fallback value
if (count($splittedUri) > 2) {
    $action = $splittedUri[2];
    // check for further information eg. an id for show pages
    if (count($splittedUri)>3) {
        $id = (int)$splittedUri[3];
    } else {
//        action is given, check for get parameter beggining with '?'
        $getParamsString = explode('?', $splittedUri[2], 2);
        if (count($getParamsString)==2) {
            $paramArray = transformGetStringToArray($getParamsString[1]);
//            remove string from action
            $action = $getParamsString[0];
        }
    }
} else {
    // no action is given and will be set do fallback ''
    $action = '';
}

executeRoute($controller, $action, $routes, $requestedMethod, $paramArray, $id);


/**
 * redirect to the correct controller and action
 *
 * @param string $controller
 * @param string $action
 * @param array<string, array<string, array<string, string>>> $routes
 * @param string $requestedMethod
 * @param array<string>|null $paramArray
 * @param int|null $id
 * @return void
 */
function executeRoute(string $controller, string $action, array $routes, string $requestedMethod, array $paramArray = null, ?int $id): void
{
    // Trying to get the routes endpoint if the route is completely defined in the routesfile
    $routeIsValid = isValidRoute($controller, $action, $routes, $requestedMethod);
    if ($routeIsValid) {
        // if user is not logged in => allow him to visit all public pages (checked in prag_match)
        if (!isset($_SESSION['user']) && !preg_match('#^/(login|register|dashboard|impressum|(offer/(find|(detail/\d*)|filter|storefilter)))?$#', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '')) {
            $_SESSION['redirect_back'] = $_SERVER['REQUEST_URI'];
            new ViewController('loginIndex');
        }
        // reset redirect path after login if no login happened
        if (isset($_SESSION['redirect_back']) && !str_contains($_SERVER['REQUEST_URI'], 'register') && !str_contains($_SERVER['REQUEST_URI'], 'login')) {
            unset($_SESSION['redirect_back']);
        }
        // fetch internal functionname
        $controllerFunction = strtolower($requestedMethod) . $routes[$controller][$requestedMethod][$action];
        // prepare Controller namespace for calling the Class
        $controllername = ucfirst($controller).'Controller';

        $controllerNamespace = "\\src\\controller\\$controllername";
        $controller = new $controllerNamespace();
        // trigger the function in controller
        if ($id) {
            $controller->$controllerFunction($id);
        } else {
            $controller->$controllerFunction($paramArray);
        }
    } else {
        new ViewController('notFound'); // redirect if no route is defined
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
