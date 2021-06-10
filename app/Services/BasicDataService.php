<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

/**
 * 默认数据服务接口
 *
 * Interface BasicDataService
 * @package App\Services
 */
abstract class BasicDataService extends Service
{
    /**
     * 获取builder实例
     *
     * @return Builder
     */
    protected abstract function getModelBuilder(): Builder;

    /**
     * 查询参数格式：
     * [
     *  'filter' => [kvs],
     *  'sort' => [<colmun_name> => 'asc/desc', ...],
     *  'page' => number,
     *  'page_size' => number,
     *  'distinct_count' => string,
     * ]
     * @param $params
     * @return array
     */
    public function getList($params)
    {
        return $this->queryList($this->getModelBuilder(), $params);
    }

    /**
     * 默认等同getList，用于获取单存的数据列表，不需要联表的场景
     *
     * @param $params
     * @return array
     */
    public function getSimpleList($params) {
        return $this->queryList($this->getModelBuilder(), $params);
    }

    /**
     * @param $data
     * @return array
     */
    public function add($data)
    {
        return $this->addRecord($this->getModelBuilder(), $data);
    }

    /**
     * @param $data
     * @param $cnds
     * @return array
     */
    public function update($data, $cnds)
    {
        return $this->updateRecord($this->getModelBuilder(), $data, $cnds);
    }

    public function updateById($id, $data)
    {
        return $this->updateRecord($this->getModelBuilder(), $data, [
            'id' => $id,
        ]);
    }

    public function batchUpdate($ids, $data) {
        $res = $this->getModelBuilder()->whereIn('id', $ids)->update($data);
        return $this->result($res >= 0, '');
    }

    /**
     * @param $id
     * @return array
     */
    public function deleteById($id) {
        return $this->deleteRecord($this->getModelBuilder(), ['id' => $id]);
    }

    /**
     * @param $id
     * @return array
     */
    public function deleteByIds($ids) {
        $res = $this->getModelBuilder()->whereIn('id', $ids)->delete();
        return $this->result($res >= 0, '');
    }

    /**
     * @param $cnds
     * @return array
     */
    public function deleteByCnds($cnds) {
        return $this->deleteRecord($this->getModelBuilder(), $cnds);
    }

    /**
     * @param $id
     * @return array
     */
    public function getById($id, $with=[]) {
        $query = $this->getModelBuilder();
        if(!empty($with)) {
            foreach($with as $table) {
                $query->with($table);
            }
        }
        return $this->queryReocrdById($query, $id);
    }

    /**
     * 根据条件查询数据
     *
     * @param $cnds
     * @return array
     */
    public function getByCnds($cnds)
    {
        return $this->queryRecord($this->getModelBuilder(), $cnds);
    }

    public function count($cnds) {
        return $this->queryCount($this->getModelBuilder(), $cnds);
    }
}
