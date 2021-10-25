<?php

namespace App\Library;

use App\Traits\Instance;

/**
 * Class ChineseCharactersByInitialGrouping
 *
 * 汉字首字母进行分组
 *
 * @package App\Helper\ExtendClass
 */
class ChineseCharactersByInitialGrouping
{
    use Instance;

    public function demo($demo_data, $key)
    {
        return $data = $this->groupByInitials($demo_data, $key);
    }

    /**
     * 二维数组根据首字母分组排序
     *
     * @param  array   $data       二维数组
     * @param  string  $targetKey  首字母的键名
     *
     * @return array    根据首字母关联的二维数组
     */
    public function groupByInitials(array $data, string $targetKey = 'key')
    {
        $data = array_map(function($item) use ($targetKey) {
            return array_merge($item, ['initials' => $this->getInitials($item[$targetKey]),]);
        }, $data);
        $data = $this->sortInitials($data);
        return $data;
    }

    /**
     * 按字母排序
     *
     * @param  array  $data
     *
     * @return array
     */
    private function sortInitials(array $data, $default = '#')
    {
        $sortData = [];
        foreach ($data as $key => $value) {
            $sortData[$value['initials']][] = $value;
        }

        // 是否存在 #
        $exits_wei = false;
        if ( !empty($sortData[$default]) ) {
            $exits_wei = true;
            $data_default = $sortData[$default];
        }

        unset($sortData[$default]);
        ksort($sortData);

        if ( $exits_wei ) $sortData[$default] = $data_default;

        return $sortData;
    }

    /**
     * 获取首字母
     *
     * @param  string  $str  汉字字符串
     *
     * @return string 首字母
     */
    private function getInitials($str, $default = '#')
    {
        if ( empty($str) ) {
            return $default;
        }
        $fchar = ord($str[0]);
        if ( $fchar >= ord('A') && $fchar <= ord('z') ) {
            return strtoupper($str[0]);
        }

        $s1 = iconv('UTF-8', 'gb2312', $str);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;
        if ( $asc >= -20319 && $asc <= -20284 ) {
            return 'A';
        }

        if ( $asc >= -20283 && $asc <= -19776 ) {
            return 'B';
        }

        if ( $asc >= -19775 && $asc <= -19219 ) {
            return 'C';
        }

        if ( $asc >= -19218 && $asc <= -18711 ) {
            return 'D';
        }

        if ( $asc >= -18710 && $asc <= -18527 ) {
            return 'E';
        }

        if ( $asc >= -18526 && $asc <= -18240 ) {
            return 'F';
        }

        if ( $asc >= -18239 && $asc <= -17923 ) {
            return 'G';
        }

        if ( $asc >= -17922 && $asc <= -17418 ) {
            return 'H';
        }

        if ( $asc >= -17417 && $asc <= -16475 ) {
            return 'J';
        }

        if ( $asc >= -16474 && $asc <= -16213 ) {
            return 'K';
        }

        if ( $asc >= -16212 && $asc <= -15641 ) {
            return 'L';
        }

        if ( $asc >= -15640 && $asc <= -15166 ) {
            return 'M';
        }

        if ( $asc >= -15165 && $asc <= -14923 ) {
            return 'N';
        }

        if ( $asc >= -14922 && $asc <= -14915 ) {
            return 'O';
        }

        if ( $asc >= -14914 && $asc <= -14631 ) {
            return 'P';
        }

        if ( $asc >= -14630 && $asc <= -14150 ) {
            return 'Q';
        }

        if ( $asc >= -14149 && $asc <= -14091 ) {
            return 'R';
        }

        if ( $asc >= -14090 && $asc <= -13319 ) {
            return 'S';
        }

        if ( $asc >= -13318 && $asc <= -12839 ) {
            return 'T';
        }

        if ( $asc >= -12838 && $asc <= -12557 ) {
            return 'W';
        }

        if ( $asc >= -12556 && $asc <= -11848 ) {
            return 'X';
        }

        if ( $asc >= -11847 && $asc <= -11056 ) {
            return 'Y';
        }

        if ( $asc >= -11055 && $asc <= -10247 ) {
            return 'Z';
        }

        return $default;
    }
}