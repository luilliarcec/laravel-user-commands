<?php

return [
    /**
     * Register here your user model
     */
    'user' => '\App\Models\User',

    /**
     * Register here the fields that will be asked by console
     */
    'fields' => [],

    /**
     * Register here the fields that are hashed before being saved
     */
    'hash_fields' => [],

    /**
     * Register here the rules you want to apply to your fields
     */
    'rules' => [],

    /**
     * If you are using permissions, register here the permission model
     * and the name of the relationship in your user model.
     */
    'permission' => [
        'model' => null,
        'relation' => 'permissions',
    ],

    /**
     * If you are using roles, register here the role model
     * and the name of the relationship in your user model.
     */
    'role' => [
        'model' => null,
        'relation' => 'roles'
    ],
];
