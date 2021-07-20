<?php

return [
    'user' => '\App\Models\User',

    'fields' => [],

    'rules' => [],

    'permission' => [
        'model' => null,
        'relation' => 'permissions',
    ],

    'role' => [
        'model' => null,
        'relation' => 'roles'
    ],
];
