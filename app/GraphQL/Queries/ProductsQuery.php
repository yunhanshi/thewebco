<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Services\Product\ProductService;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ProductsQuery extends Query
{
    protected $attributes = [
        'name' => 'product',
        'description' => 'Product queries'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Product'));
    }

    public function args() : array
    {
        return [
            'page_size' => ['name' => 'page_size', 'type' => Type::int()],
            'page' => ['name' => 'page', 'type' => Type::int()],
            'filter_name' => ['name' => 'filter_name', 'type' => Type::string()],
            'filter_category' => ['name' => 'filter_category', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $productService = new ProductService();
        $params = [
            'page' => 1,
            'page_size' => 10
        ];
        if (!empty($args['page'])) {
            $params['page'] = $args['page'];
        }
        if (!empty($args['page_size'])) {
            $params['page_size'] = $args['page_size'];
        }
        if (!empty($args['filter_name'])) {
            $params['filter']['name@like'] = $args['filter_name'];
        }
        if (!empty($args['filter_category'])) {
            $params['filter']['category.name@like'] = $args['filter_category'];
        }

        return $productService->getList($params)['data'];
    }
}
