<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Product\Product;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ProductQuery extends Query
{
    protected $attributes = [
        'name' => 'product',
        'description' => 'A product query'
    ];

    public function type(): Type
    {
        return GraphQL::type('Product');
    }

    public function args(): array
    {
        return [
            'id'    => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of a product',
                'rules' => ['required']
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $query = Product::Query();

        return $query->with($fields->getRelations())->find($args['id']);
    }
}
