<?php

namespace App\Consts;

class SystemConst
{
    public const IMAGE_THUMB_SIZES = [
        600,
        300,
    ];

    public const PRODUCT_TYPE_PIZZA_BASE = 1;
    public const PRODUCT_TYPE_SAUCE = 2;
    public const PRODUCT_TYPE_CHEESE = 3;
    public const PRODUCT_TYPE_INGREDIENTS = 4;
    public const PRODUCT_TYPE_ADD_PRODUCTS = 5;

    public const PRODUCT_TYPES_MAP = [
        0 => [],
        self::PRODUCT_TYPE_PIZZA_BASE => ['name' => 'pizza base', 'description' => 'round pizza base'],
        self::PRODUCT_TYPE_SAUCE => ['name' => 'sauce', 'description' => 'a variety of pizza sauces'],
        self::PRODUCT_TYPE_CHEESE => ['name' => 'cheese', 'description' => 'cheeses for pizza'],
        self::PRODUCT_TYPE_INGREDIENTS => ['name' => 'ingredients', 'description' => 'add. pizza ingredients'],
        self::PRODUCT_TYPE_ADD_PRODUCTS => ['name' => 'add. products', 'description' => 'add. order products'],
    ];


    public const ORDER_STATUS_NEW = 1;
    public const ORDER_STATUS_ACCEPTED = 2;
    public const ORDER_STATUS_COOKING = 3;
    public const ORDER_STATUS_READY = 4;
    public const ORDER_STATUS_DELIVERY = 5;
    public const ORDER_STATUS_DELIVERED = 6;
    public const ORDER_STATUS_DECLINED = 7;
    public const ORDER_STATUS_COMPLETED = 8;
    public const ORDER_STATUS_ARCHIVED = 9;

    public const ORDER_STATUSES_MAP = [
        0 => [],
        self::ORDER_STATUS_NEW => ['slug' => 'new', 'name' => 'New'],
        self::ORDER_STATUS_ACCEPTED => ['slug' => 'accepted', 'name' => 'Accepted'],
        self::ORDER_STATUS_COOKING => ['slug' => 'cooking', 'name' => 'Cooking'],
        self::ORDER_STATUS_READY => ['slug' => 'ready', 'name' => 'Ready'],
        self::ORDER_STATUS_DELIVERY => ['slug' => 'delivery', 'name' => 'Delivery'],
        self::ORDER_STATUS_DELIVERED => ['slug' => 'delivered', 'name' => 'Delivered'],
        self::ORDER_STATUS_DECLINED => ['slug' => 'declined', 'name' => 'Declined'],
        self::ORDER_STATUS_COMPLETED => ['slug' => 'completed', 'name' => 'Completed'],
        self::ORDER_STATUS_ARCHIVED => ['slug' => 'archived', 'name' => 'Archived'],
    ];


    public const USER_ROLE_ADMIN = 1;
    public const USER_ROLE_MANAGER = 2;
    public const USER_ROLE_CHEF = 3;
    public const USER_ROLE_COOK = 4;
    public const USER_ROLE_COURIER = 5;
    public const USER_ROLE_CUSTOMER = 6;

    public const USER_ROLES_MAP = [
        0 => [],
        self::USER_ROLE_ADMIN => ['name' => 'pizzeria administrator', 'slug' => 'admin', 'salary' => 12000],
        self::USER_ROLE_MANAGER => ['name' => 'manager', 'slug' => 'manager', 'salary' => 9000],
        self::USER_ROLE_CHEF => ['name' => 'chef', 'slug' => 'chef', 'salary' => 10000],
        self::USER_ROLE_COOK => ['name' => 'cook', 'slug' => 'cook', 'salary' => 5000],
        self::USER_ROLE_COURIER => ['name' => 'courier', 'slug' => 'courier', 'salary' => 4500],
        self::USER_ROLE_CUSTOMER => ['name' => 'customer', 'slug' => 'customer', 'salary' => 0],
    ];

    public const USERS_START_SEED = [
        [
            'name' => 'Admin',
            'role_id' => self::USER_ROLE_ADMIN,
            'password' => '12345678',
            'email' => 'pizza.app.admin@gmail.com',
            'phone' => '123',
            'address' => '12345',
        ],
        [
            'name' => 'Manager',
            'role_id' => self::USER_ROLE_MANAGER,
            'password' => '12345678',
            'email' => 'pizza.app.manager@gmail.com',
            'phone' => '123',
            'address' => '12345',
        ],
        [
            'name' => 'Chef',
            'role_id' => self::USER_ROLE_CHEF,
            'password' => '12345678',
            'email' => 'pizza.app.chef@gmail.com',
            'phone' => '123',
            'address' => '12345',
        ],
        [
            'name' => 'Cook',
            'role_id' => self::USER_ROLE_COOK,
            'password' => '12345678',
            'email' => 'pizza.app.cook@gmail.com',
            'phone' => '123',
            'address' => '12345',
        ],
        [
            'name' => 'Courier',
            'role_id' => self::USER_ROLE_COURIER,
            'password' => '12345678',
            'email' => 'pizza.app.courier@gmail.com',
            'phone' => '123',
            'address' => '12345',
        ],
    ];
}
