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
        self::PRODUCT_TYPE_PIZZA_BASE => ['name' => 'pizza base', 'description' => 'круглая основа для пиццы'],
        self::PRODUCT_TYPE_SAUCE => ['name' => 'sauce', 'description' => 'разнообразные соусы для пиццы'],
        self::PRODUCT_TYPE_CHEESE => ['name' => 'cheese', 'description' => 'сыр для пиццы'],
        self::PRODUCT_TYPE_INGREDIENTS => ['name' => 'ingredients', 'description' => 'доп. ингредиенты для пиццы'],
        self::PRODUCT_TYPE_ADD_PRODUCTS => ['name' => 'add. products', 'description' => 'доп. товары для заказа'],
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
    public const USER_ROLE_COOK = 3;
    public const USER_ROLE_CHEF = 4;
    public const USER_ROLE_COURIER = 5;
    public const USER_ROLE_CUSTOMER = 6;

    public const USER_ROLES_MAP = [
        0 => [],
        self::USER_ROLE_ADMIN => ['name' => 'администратор пиццерии', 'slug' => 'admin', 'salary' => 12000],
        self::USER_ROLE_MANAGER => ['name' => 'менеджер', 'slug' => 'manager', 'salary' => 9000],
        self::USER_ROLE_COOK => ['name' => 'повар', 'slug' => 'cook', 'salary' => 5000],
        self::USER_ROLE_CHEF => ['name' => 'старший повар', 'slug' => 'chef', 'salary' => 10000],
        self::USER_ROLE_COURIER => ['name' => 'курьер', 'slug' => 'courier', 'salary' => 4500],
        self::USER_ROLE_CUSTOMER => ['name' => 'клиент', 'slug' => 'customer', 'salary' => 0],
    ];
}
