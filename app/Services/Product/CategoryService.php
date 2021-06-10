<?php

namespace App\Services\Product;

use App\Models\Product\Category;
use App\Services\BasicDataService;
use Illuminate\Database\Eloquent\Builder;

class CategoryService extends BasicDataService
{
    /**
     * get builder
     *
     * @return Builder
     */
    protected function getModelBuilder(): Builder
    {
        return Category::query();
    }

    /**
     * get product list
     *
     * @param array $params
     * @return array
     */
    public function getList($params)
    {
        if (empty($params['sort'])) {
            $params['sort']['sort_order'] = 'asc';
        }
        return $this->queryList(Category::query(), $params);
    }
}
