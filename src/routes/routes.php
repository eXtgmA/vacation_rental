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
        ],
    'logout' =>
        [
            'POST' => [
                '' => 'logout']
        ],
    'offer' =>
        ['GET' => [
            '' => 'index',
            'create'=>'create',
            'edit'=>'edit',
            'detail'=>'detail',
            'find'=>'find',
            'filter'=>'filter'
        ],
          'POST' => [
             'create' => 'create',
              'edit'=>'edit',
              'togglestatus'=>'toggleStatus',
              'delete'=>'delete',
              'storefilter'=>'storeFilter'],

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
            'create'=>'create',
            'showall'=>'showall',
            'edit'=>'edit'
        ],
            'POST' => [
                'create' => 'create',
                'delete'=>'delete',
                'edit' => 'edit',
                'togglestatus'=>'toggleStatus',]
        ],
    'booking' =>
        ['GET' => [
            'create' => 'createBookingposition',
//            'checkout' => 'checkout'
        ], 'POST' => [
            'create'=>'createBookingposition',
//            'checkout'=>'checkout',
            'delete' => 'deleteBookingposition']
        ],
    'cart' =>
        ['GET' => [
            '' => 'cart']
        ],
    'checkout' =>
        ['GET' => [
            '' => 'checkout'
        ], 'POST' => [
            'booking' => 'checkout']
        ],
    'profile' =>
        ['GET' => [
            '' => 'edit',
            'history' => 'history'],
          'POST' => [
             'update' => 'update']
        ]
];
