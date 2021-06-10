<?php

namespace App\Models;

use App\Utils\ArrayUtil;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

trait FiledFilterTrait
{
    private static $DB_NUMBER_TYPES = [
        'bigint' => 1,
        'int' => 1,
        'mediumint' => 1,
        'smallint' => 1,
        'tinyint' => 1,
        'decimal' => 1,
        'numeric' => 1,
        'bit' => 1,
        'float' => 1,
        'double' => 1,
    ];

    /**
     * @param $data
     * @param null $model
     * @return array
     */
    public static function filterFields($data, $model=null) {
        $instance = $model ?? (new static);
        return $instance->doFilterFields($data);
    }

    public function doFilterFields($data) {
        $tables = config('table_columns');
        $columns = Arr::get($tables, $this->getTable(), null);

        if(!empty($columns)) {
            $data = self::extractValuesByKeyAndType($data, $columns);
        }
        return $data;
    }

    /**
     * @param $list
     * @param null $model
     * @return array
     */
    public static function filterListFields($list, $model=null) {
        $instance = $model ?? (new static);
        return $instance->doFilterLickFields($list);
    }

    public function doFilterLickFields($list) {
        $res = [];
        foreach ($list as $data) {
            $res[] = $this->doFilterFields($data);
        }
        return $res;
    }

    /**
     * @param string $field
     * @param null $model
     * @return boolean
     */
    public static function hasField($field, $model=null) {
        $instance = $model ?? (new static);
        return $instance->doHasField($field);
    }

    public function doHasField($field) {
        $tables = config('table_columns');

        $columns = Arr::get($tables, $this->getTable(), null);

        if(!empty($columns)) {
            $keys = array_keys($columns);
            return in_array($field, $keys);
        }
        return false;
    }

    public static function isNumberType($type) {
        return !empty(self::$DB_NUMBER_TYPES[$type]);
    }

    private static function extractValuesByKeyAndType($arr, array $keyAndTypes) {
        $res = array();
        foreach($keyAndTypes as $key => $type) {
            if(!isset($arr[$key])) {
                continue;
            }
            $value = $arr[$key];
            if(self::isNumberType($type) && $value === '') {
                // 如果数据表的字段类型是数字，跳过空字符串的赋值
                continue;
            }

            $res[$key] = $value;
        }
        return $res;
    }
}
