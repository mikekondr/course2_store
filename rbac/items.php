<?php

use yii\rbac\Item;

return [
    'manageUsers' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Manage users',
    ],
    'viewClassifiers' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'View classifiers',
    ],
    'viewRemains' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'View remains of goods and it\'s consignments'
    ],
    'editClassifiers' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Edit (add, update, delete) classifiers',
        'children' => [
            'viewClassifiers',
        ],
    ],
    'viewOperations' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'View operations',
    ],
    'editOperations' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Edit (add, update, delete) classifiers',
        'children' => [
            'viewOperations',
        ]
    ],
    'viewOrders' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'View orders',
    ],
    'editOrders' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Edit (add, update, delete) classifiers',
        'children' => [
            'viewOrders',
        ]
    ],
    'viewOwnOrders' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'View own orders',
        'ruleName' => 'isAuthor',
    ],
    'editOwnOrders' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Edit own orders',
        'children' => [
            'viewOwnOrders',
        ],
        'ruleName' => 'isAuthor',
    ],

    'guest' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Guest',
        'children' => [],
    ],
    'manager' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Manager',
        'children' => [
            'manageUsers',
            'editClassifiers',
            'editOperations',
            'editOrders',
            'viewRemains',
        ],
    ],
    'storekeeper' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Storekeeper',
        'children' => [
            'viewClassifiers',
            'editOperations',
            'viewOrders',
            'viewRemains',
        ],
    ],
    'client' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Client',
        'children' => [
            'editOwnOrders',
            'viewRemains',
        ],
    ]
];