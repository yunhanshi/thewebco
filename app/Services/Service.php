<?php

namespace App\Services;


use App\Models\Model;
use App\Utils\DynamicUtil;
use App\Utils\Log;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;

/**
 * 服务基类
 * Class Service
 */
abstract class Service
{
    /**
     * 默认单页容量
     */
    const ITEM_PER_PAGE = 15;

    /**
     * 乐观锁重试最大次数
     */
    const OPTIMISTIC_LOCK_RETRY_TIMES = 5;

    /**
     * 乐观锁重试时间间隔，单位：ms
     */
    const OPTIMISTIC_LOCK_RETRY_INTERVAL_MS = 100;

    /**
     * 查询列表特殊字段
     */
    protected $listSpecialFields = ['filter', 'like', 'page', 'page_size', 'sort'];

    /**
     * 合法的比较操作符
     */
    protected $compareOperators = [
        'gt' => '>',
        'lt' => '<',
        'ge' => '>=',
        'le' => '<=',
        'ne' => '<>',
    ];

    /**
     * 合法的like操作符
     */
    protected $likeOperators = ['like'];

    /**
     * 可以执行比较操作的字段白名单
     */
    protected $compareWhiteList = ['id', 'paid_at', 'created_at', 'updated_at'];

    /**
     * 可执行like的字段白名单
     */
    protected $likeWhiteList = ['barcode', 'name', 'keyword', 'telephone', 'mobile', 'email', 'app_id', 'task', 'cust_name'];

    /**
     * 合法的排序字段取值
     */
    protected $sortValues = ['asc', 'desc'];

    protected function result($success=true, $msg='', $data=[], $meta=[]) {
        return [
            'success' => $success,
            'msg' => $msg,
            'data' => $data,
            'meta' => $meta,
        ];
    }

    protected function normalizeDbResult($success=true, $msg='', $dbResult=[]) {
        $res = $this->normalize($dbResult);
        return $this->result($success, $msg, Arr::get($res, 'data', []), Arr::get($res, 'meta', []));
    }

    /**
     * 解析列表查询参数。
     *
     * @param Builder $builder
     * @param $params
     */
    protected function parseListParams(Builder $builder, $params) {
        // 解析filter参数
        $filterParams = Arr::get($params, 'filter', []) ?? [];
        foreach ($filterParams as $k => $v) {
            $kArray = explode('.', $k);
            if (2 === count($kArray)) {
                // 联表查询
                $this->processRelativeFilterParam($builder, $kArray[0], $kArray[1], $v);
            } else {
                // 单表查询
                $processFilterParamClosure = $this->processFilterParam($k, $v, true);
                $processFilterParamClosure($builder);
            }
        }

        // sort参数
        // 如果有join，为了避免字段冲突，默认不用created_at排序
        $defaultSort = !empty($builder->getQuery()->joins) ? [] : ['created_at'=>'desc'];
        $sortParams = Arr::get($params, 'sort', $defaultSort);
        foreach($sortParams as $k => $v) {
            $this->processSortParam($builder, $k, $v);
        }
    }

    /**
     * 根据参数拼接联表查询条件
     *
     * @param Builder $builder
     * @param string $relation
     * @param string $key
     * @param string $value
     */
    private function processRelativeFilterParam(Builder $builder, $relation, $key, $value) {
        if (empty($value)) {
            return ;
        }
        $processFilterParamClosure = $this->processFilterParam($key, $value);
        $builder->whereHas($relation, $processFilterParamClosure);
    }

    private function processFilterParam($key, $value, $qualify=false) {
        return function (Builder $builder) use ($key, $value, $qualify) {
            //判断查询条件是否为空
            if (empty($value) && !is_numeric($value)) {
                return ;
            }
            $key = strtolower($key);

            // in
            if(is_array($value)) {
                if($qualify) {
                    $key = $this->qualifyColumn($builder, $key);
                }
                $builder->whereIn($key, $value);
                return;
            }

            // 普通字段
            if(strpos($key, '@') === false) {
                if($qualify) {
                    $key = $this->qualifyColumn($builder, $key);
                }
                $builder->where($key, '=', $value);
                return;
            }

            // 带有operator
            $ts = explode('@', $key);
            $k = $ts[0];
            $op = $ts[1];

            // 比较操作符
            if(isset($this->compareOperators[$op])) {
                if(!in_array($k, $this->compareWhiteList)) {
                    // 忽略不在白名单的字段
                    return;
                }

                if($qualify) {
                    $k = $this->qualifyColumn($builder, $k);
                }
                $builder->where($k, $this->compareOperators[$op], $value);
                return;
            }

            // like
            if(in_array($op, $this->likeOperators)) {
                if(!in_array($k, $this->likeWhiteList)) {
                    // 忽略不在白名单的字段
                    return;
                }

                $value = str_replace('%', '', $value);
                if($qualify) {
                    $k = $this->qualifyColumn($builder, $k);
                }
                $builder->where($k, $op, '%' . $value . '%');
                return;
            }
        };
    }

    private function processSortParam(Builder $builder, $key, $value) {
        $key = strtolower($key);
        $value = strtolower($value);
        if(!in_array($value, $this->sortValues)) {
            return;
        }

        $builder->orderBy($this->qualifyColumn($builder, $key), $value);
    }

    protected function qualifyColumn(Builder $builder, $key) {
        if(strpos($key, '.') !== false) {
            return $key;
        }

        // 对无别名前缀的字段，加上表名
        $model = $builder->getModel();
        DynamicUtil::safeCall($model, 'doHasField', [$key], $key);
        if(DynamicUtil::safeCall($model, 'doHasField', [$key], false)) {
            return $builder->getModel()->qualifyColumn($key);
        }
        return $key;
    }

    protected function qualifyColumns(Builder $builder, $kvs) {
        $res = [];
        foreach ($kvs as $k => $v) {
            $res[$this->qualifyColumn($builder, $k)] = $v;
        }
        return $res;
    }

    /**
     * 根据请求参数查询列表数据。
     * 具体参数格式参考： https://tower.im/teams/712116/documents/200/#toc-10
     *
     * params['distinct_count']: eloquent默认的分页统计不会加distinct，一些需要distinct的统计场景会导致页数不对。如需distinct分页，可以通过这个参数传统计的字段进来，如：pos_admins.id；
     *
     * @param Builder $builder 外界指定的eloquent builder
     * @param array $params 请求参数，有一些约定的参数需要做处理
     * @return array
     */
    protected function queryList(Builder $builder, $params) {
        $this->parseListParams($builder, $params);

        $page = Arr::get($params, 'page', 1);
        $pageSize = Arr::get($params, 'page_size', static::ITEM_PER_PAGE);

        if(!empty($params['distinct_count'])) {
            $res = $this->paginate($builder, $pageSize, [new Expression('DISTINCT ' . $params['distinct_count'])], 'page', $page);
        } else {
            $res = $builder->paginate($pageSize, ['*'], 'page', $page);
        }

        return $this->normalizeDbResult(true, '', $res);
    }

    protected function paginate(Builder $builder, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: static::ITEM_PER_PAGE;

        $results = ($total = $builder->toBase()->getCountForPagination($columns))
            ? $builder->forPage($page, $perPage)->get($columns)
            : $builder->getModel()->newCollection();

        return $this->paginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    protected function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }

    protected function normalize($dbResult) {
        $data = [];
        if(!empty($dbResult)) {
            $data = $dbResult->toArray();
            if ($dbResult instanceof LengthAwarePaginator) {
                return [
                    'data' => $data['data'],
                    'meta' => [
                        'current_page' => (int)$data['current_page'],
                        'per_page' => (int)$data['per_page'],
                        'total' => (int)$data['total'],
                    ],
                ];
            }
        }

        return [
            'data' => $data,
            'meta' => [],
        ];
    }

    protected function addRecord(Builder $builder, $data) {
        try {
            $model = $builder->getModel();
            if($model instanceof Model) {
                $data = $model::filterFields($data, $model);
            }
            $record = $builder->create($data);
            return $this->normalizeDbResult(true, '', $record);
        } catch (QueryException $qe) {
            Log::error($qe);
            return $this->result(false, 'fail to insert record for query exception');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->result(false, 'fail to insert record for exception');
        }
    }

    protected function updateRecord(Builder $builder, $data, $cnds) {
        try {
            $model = $builder->getModel();
            if($model instanceof Model) {
                $data = $model::filterFields($data, $model);
            }
            $res = $builder->where($cnds)->update($data);
            return $this->result($res >= 0, 'Update success');
        } catch (QueryException $qe) {
            Log::error($qe);
            var_dump($qe->getMessage());
            return $this->result(false, 'fail to update record for query exception');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->result(false, 'fail to update record for exception');
        }
    }

    protected function deleteRecord(Builder $builder, $cnds) {
        try {
            $res = $builder->where($cnds)->delete();
            return $this->result($res >= 0, 'Delete success');
        } catch (QueryException $qe) {
            Log::error($qe);
            return $this->result(false, 'fail to delete record for query exception');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->result(false, 'fail to delete record for exception');
        }
    }

    protected function queryRecord(Builder $builder, $cnds) {
        $cnds = $cnds ?? [];
        foreach($cnds as $k => $v) {
            if(is_array($v)) {
                $builder->whereIn($k, $v);
            } else {
                $builder->where($k, '=', $v);
            }
        }
        return $this->normalizeDbResult(true, '', $builder->first());
    }

    protected function queryReocrdById(Builder $builder, $id) {
        $key = $this->qualifyColumn($builder, 'id');
        return $this->normalizeDbResult(true, '', $builder->where($key, $id)->first());
    }

    protected function queryCount(Builder $builder, $cnds) {
        return $this->result(true, '', $builder->where($cnds)->count());
    }

    /**
     * 获取一条记录，并加排他锁
     * @param Builder $builder
     * @param String $id
     * @return Model
     * @throws \Exception
     */
    protected function lockForUpdate(Builder $builder, $id) {
        if (empty($id)) {
            throw new \Exception('添加排他锁失败');
        }
        return $builder->where('id', $id)->lockForUpdate()->first();
    }

    protected function getAsArray($query) {
        return $this->toArray($query->get());
    }

    protected function firstAsArray($query) {
        return $this->toArray($query->first());
    }

    protected function toArray($collection) {
        if(empty($collection)) {
            return [];
        }

        if($collection instanceof \Illuminate\Database\Eloquent\Collection) {
            return $collection->toArray();
        }

        return json_decode(json_encode($collection), 1);
    }

    protected function hasJoint($query, $tableName) {
        if($query instanceof Builder) {
            $query = $query->getQuery();
        }

        if(!$query instanceof \Illuminate\Database\Query\Builder) {
            return false;
        }

        if(empty($query->joins)) {
            return false;
        }

        foreach($query->joins as $join) {
            if($join->table == $tableName) {
                return true;
            }
        }
        return false;
    }
}
