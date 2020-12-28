<?php

return [
    'user' => '\App\Models\User',

    'permission' => [
        'model' => null,
        'relation' => 'permissions',
    ],

    'role' => [
        'model' => null,
        'relation' => 'roles'
    ],
];
