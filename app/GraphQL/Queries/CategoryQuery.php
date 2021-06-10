<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Product\Category;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class CategoryQuery extends Query
{
    protected $attributes = [
        'name' => 'category',
        'description' => 'A category query'
    ];

    public function type(): Type
    {
        return GraphQL::type('Category');
    }

    public function args(): array
    {
        return [
            'id'    => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of a category',
                'rules' => ['required']
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $query = Category::Query();

        return $query->find($args['id']);
    }
}
