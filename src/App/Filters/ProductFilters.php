<?php

namespace App\Filters;

class ProductFilters
{
    final public const LIST = [
        [
            'name' => 'page',
            'in' => 'query',
            'required' => false,
            'type' => 'integer',
            'format' => 'integer',
            'example' => '1',
            'default' => 1,
        ],
        [
            'name' => 'items_per_page',
            'in' => 'query',
            'required' => false,
            'type' => 'integer',
            'format' => 'integer',
            'example' => '10',
            'default' => 10,
        ],
        [
            'name' => 'category_ids',
            'in' => 'query',
            'required' => false,
            'schema' => [
                'type' => 'array',
                'items' => [
                    'type' => 'integer',
                ],
            ],
        ],
        [
            'name' => 'name',
            'in' => 'query',
            'required' => false,
            'type' => 'string',
            'format' => 'string',
            'example' => 'name',
            'default' => null,
        ],
        [
            'name' => 'price',
            'in' => 'query',
            'required' => false,
            'type' => 'float',
            'format' => 'string',
            'example' => '49.99',
            'default' => null,
        ],
        [
            'name' => 'order_by',
            'in' => 'query',
            'required' => false,
            'type' => 'string',
            'format' => 'string',
            'example' => 'id',
            'default' => 'id',
        ],
        [
            'name' => 'order_direction',
            'in' => 'query',
            'required' => false,
            'type' => 'string',
            'format' => 'string',
            'example' => 'ASC',
            'default' => 'ASC',
        ],
    ];
}
