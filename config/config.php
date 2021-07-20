<?php

return [
    'user' => '\App\Models\User',

    'fields' => [],

    'permission' => [
        'model' => null,
        'relation' => 'permissions',
    ],

    'role' => [
        'model' => null,
        'relation' => 'roles'
    ],
];
