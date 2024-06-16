<?php

use yii\rbac\Item;

return [
    'manageUsers' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Manage users',
    ],
    'viewGoods' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'View goods',
    ],
    'viewClassifiers' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'View classifiers',
        'children' => [
            'viewGoods',
        ]
    ],
    'viewRemains' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'View remains of goods and it\'s consignments'
    ],
    'viewExpires' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'View and manage goods circulations',
        'children' => [
            'viewRemains',
        ]
    ],
    'manageRemains' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'View and manage goods circulations',
        'children' => [
            'viewExpires',
        ]
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
        ],
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
        ],
    ],
    'viewOwnOrders' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'View own orders',
    ],
    'editOwnOrders' => [
        'type' => Item::TYPE_PERMISSION,
        'description' => 'Edit own orders',
        'children' => [
            'viewOwnOrders',
        ],
    ],

    'guest' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Guest',
        'children' => [
            'viewGoods'
        ],
    ],
    'manager' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Manager',
        'children' => [
            'manageUsers',
            'editClassifiers',
            'editOperations',
            'editOrders',
            'manageRemains'
        ],
    ],
    'storekeeper' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Storekeeper',
        'children' => [
            'viewClassifiers',
            'editOperations',
            'viewOrders',
            'viewExpires',
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