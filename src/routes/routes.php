<?php
// this file contains all allowed routes
// the routes are stored as an array
// First level defines the Controller
// Second level defines the request method post/get/put
// Third level defines the controller action
// Fourth level will be the wanted Model or GET parameters, based on request method
//
// Template:
//'controller' =>
//        ['GET' => [
//            'routeAction' => 'methodName'],
//          'POST' => [
//             'routeAction' => 'methodName']
//        ]


return [

// Home as landingpage
    'home' => [
        'GET' => [
            '' => 'index'
        ]
    ],
// Login when already registered
    'login' =>
        ['GET' => [
            '' => 'login'],
            'POST' => [
                '' => 'login']
        ],
// restering a new user
    'register' =>
        ['GET' => [
            '' => 'form'],
            'POST' => [
                '' => 'form']
        ],
    'dashboard' =>
        ['GET' => [
            '' => 'index'],
//          'POST' => [
//             'routeAction' => 'methodName']
        ],
    'logout' =>
        [
//            'GET' => [
//            'routeAction' => 'methodName'],
            'POST' => [
                '' => 'logout']
        ],
    'offer' =>
        ['GET' => [
            '' => 'index',
            'create'=>'create',
            'show'=>'show'
        ],
          'POST' => [
             'create' => 'create',
              'togglestatus'=>'toggleStatus'],

        ],
    'image' =>
        ['GET'=>[],
            'POST' => [
             'save' => 'save',
              'delete'=>'delete']
        ],
    'impressum' =>
        ['GET' => [
            '' => 'index']
        ],
    'option' =>
        ['GET' => [
            '' => 'index',
            'create'=>'create',
            'showall'=>'showall'
        ],
            'POST' => [
                'create' => 'create']
        ],
    'booking' =>
        ['GET' => [
            'create' => 'createBookingposition',
        ], 'POST' => [
            'create'=>'createBookingposition']
        ],
];
