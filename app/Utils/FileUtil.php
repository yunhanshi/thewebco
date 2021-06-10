<?php

namespace App\Utils;

class FileUtil
{
    public static function saveFile($filePath, $fileCnt)
    {
        $file = fopen($filePath, "w");
        if($file === false) {
            return false;
        }
        fwrite($file, $fileCnt);
        fclose($file);
        return true;
    }

    /**
     * 简易读文件函数。
     * NOTICE: 会将整个文件内容都读进内存。主要用于小文件。对于大文件，建议以流式方式处理。
     *
     * @param $filePath
     * @return bool|string
     */
    public static function loadFile($filePath)
    {
        $file = fopen($filePath, "r");
        if($file === false) {
            return false;
        }

        $cnt = '';
        while (!feof($file)) {
            $cnt .= fread($file, 8192);
        }
        fclose($file);

        return $cnt;
    }

    /**
     * 读取并解析json文件
     *
     * @param $filePath
     * @return mixed
     */
    public static function loadJson($filePath)
    {
        $cnt = self::loadFile($filePath);
        return json_decode($cnt, true);
    }
}
