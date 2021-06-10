<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use App\Models\Product\Product;

class ProductType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Product',
        'description' => 'A product',
        'model' => Product::class
    ];

    public function fields(): array
    {
        return [
            'id'    => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the product',
            ],
            'name'    => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'The name of the product',
            ],
            'category'    => [
                'type'        => Type::listOf(GraphQL::type('Category')),
                'description' => 'The categories of the product',
            ],
            'price' => [
                'type'        => Type::nonNull(Type::float()),
                'description' => 'The price of the product',
            ],
            'sort_order'    => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The sort order of the product',
            ],
        ];
    }
}
