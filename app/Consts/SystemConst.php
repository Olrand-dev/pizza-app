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
}
