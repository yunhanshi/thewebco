<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Services\Product\ProductService;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\SelectFields;

class CreateProductMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createProduct',
        'description' => 'A mutation to create a product'
    ];

    public function type(): Type
    {
        return GraphQL::type('Product');
    }

    public function args(): array
    {
        return [
            'name' => [
                'name' => 'name',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required']
            ],
            'category_ids' => [
                'name' => 'category_ids',
                'type' => Type::listOf(Type::int())
            ],
            'price' => [
                'name' => 'price',
                'type' => Type::nonNull(Type::float()),
                'rules' => ['required']
            ],
            'sort_order' => [
                'name' => 'sort_order',
                'type' => Type::float()
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $productService = new ProductService();
        return $productService->add($args)['data'];
    }
}
