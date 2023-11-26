<?php
// this file contains all allowed routes
// the routes are stored as an array
// First level defines the Controller
// Second level defines the request method post/get/put
// Third level defines the controller action
// Fourth level will be the wanted Model or GET parameters, based on request method

return [
    'login' =>
        ['GET' => [
            '' => 'login'],
        'POST' => [
            '' => 'login']
        ]
];