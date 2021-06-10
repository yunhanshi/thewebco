<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Services\Product\CategoryService;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class CategoriesQuery extends Query
{
    protected $attributes = [
        'name' => 'categories',
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
        ];
    }

    public function resolve($root, $args)
    {
        $categoryService = new CategoryService();
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

        return $categoryService->getList($params)['data'];
    }
}
