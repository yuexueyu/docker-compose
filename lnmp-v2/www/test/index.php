<?php

$arr = [4, 2, 3, 1, 9];
//$arr = [4, 2, 3, 9];
//$arr = [-12, -3, -2, -1, 1];
//$arr = [0];

var_dump(minA($arr));

function minA($arr) {
    $minArr = min($arr);
    $maxArr = max($arr);
    if (!in_array(1, $arr)) {
        return 1; //无1就是1
    } else {
        for ($i = 0; $i <= $maxArr - $minArr; $i++) {
            $num = $minArr + $i + 1;
            if ($num > 0 && !in_array($num, $arr)) {
                return $num;
            }
        }
    }
}


//$str = 'abc';
//// 字符串转换为数组
//$a = str_split($str);
//// 调用perm函数
//perm($a, 0,count($a) - 1);
/**
 * 定义perm函数
 * @param $ar // 排列的字符串
 * @param $k // 初始值
 * @param $m // 最大值
 */
function perm(&$ar, $k, $m)
{
    // 初始值是否等于最大值
    if ($k == $m) {
        // 将数组转换为字符串
        echo join('', $ar), PHP_EOL;
    } else {
        // 循环调用函数
        for ($i = $k; $i <= $m; $i++) {
            // 调用swap函数
            swap($ar[$k], $ar[$i]);
            // 递归调用自己
            perm($ar, $k + 1, $m);
            // 再次调用swap函数
            swap($ar[$k], $ar[$i]);
        }
    }
}

function swap(&$a, &$b)
{
    $c = $a;
    $a = $b;
    $b = $c;
}
