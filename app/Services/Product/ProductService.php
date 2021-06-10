<?php

namespace App\Services\Product;

use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use App\Services\BasicDataService;
use Illuminate\Database\Eloquent\Builder;

class ProductService extends BasicDataService
{
    /**
     * get builder
     *
     * @return Builder
     */
    protected function getModelBuilder(): Builder
    {
        return Product::query();
    }

    /**
     * get product list
     *
     * @param array $params
     * @return array
     */
    public function getList($params)
    {
        $query = Product::query();

        $query->with("category");

        if (empty($params['sort'])) {
            $params['sort']['sort_order'] = 'asc';
        }

        return $this->queryList($query, $params);
    }

    /**
     * add new product
     *
     * @param array $data
     * @return array
     */
    public function add($data)
    {
        DB::beginTransaction();
        try {
            $param = $data;
            unset($param['category_ids']);
            $res = $this->addRecord($this->getModelBuilder(), $param);
            if ($res['success'] === true && !empty($data['category_ids'])) {
                $query = Product::find($res['data']['id']);
                $query->category()->sync($data['category_ids']);
            }
            DB::commit();
            return $res;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->result(false, 'System error: ' . $e->getMessage(), []);
        }
    }

    /**
     * update product by id
     *
     * @param $id
     * @param $data
     * @return array
     */
    public function updateById($id, $data)
    {
        DB::beginTransaction();
        try {
            $param = $data;
            unset($param['category_ids']);
            $res = $this->updateRecord($this->getModelBuilder(), $param, [
                'id' => $id,
            ]);
            $query = Product::find($id);
            $query->category()->sync($data['category_ids']);
            DB::commit();
            return $res;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->result(false, 'System error: ' . $e->getMessage(), []);
        }
    }
}
