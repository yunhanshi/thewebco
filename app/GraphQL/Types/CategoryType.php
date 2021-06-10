<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use App\Models\Product\Category;

class CategoryType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Category',
        'description' => 'A category',
        'model' => Category::class
    ];

    public function fields(): array
    {
        return [
            'id'    => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the category',
            ],
            'name'    => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'The name of the category',
            ],
            'sort_order'    => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The sort order of the category',
            ],
        ];
    }
}
