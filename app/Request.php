<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace app;


use think\facade\{Config, Db};

class Request extends \think\Request
{

    /**
     * 参数接收
     * @param array|null $map 键名
     * @param bool|null  $op 是否转换
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function params(?array $map = [], ?bool $op = false): array
    {
        $param = $this->except(Config::get('parameter.except', []));
        if (empty($param) === true) {
            return [];
        }
        foreach ($param as $k => &$v) {
            if ((string)$v === '0') {
                (int)$v;
            } elseif (empty($v) === true) {
                unset($param[ $k ]);
            }
        }
        return $this->shineUpon($param, $map, $op);
    }


    /**
     * 映射字段
     * @param array|null $param 参数 必须
     * @param array|null $array 需要映射的数组
     * @param bool|null  $op 是否转换 默认true
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function shineUpon(?array $param, ?array $array = [], ?bool $op = true): array
    {
        if (empty($array) === true) {
            if ($op === true) {
                array_walk($param, function (&$v, $k) {
                    $v = [$k, '=', $v];
                });
            }
            return $param;
        }
        $data = [];
        foreach ($array as $k => $v) {
            if (empty($v) === true) {
                continue;
            }
            if (array_key_exists($k, $param)) {
                is_string($v) && $v = [$v];
                $value = $this->designation($param[ $k ], ...$v);
                $value !== false && $data[ $v[0] ] = $value;
                unset($param[ $k ]);
            }
            continue;
        }
        if ($op === true) {
            array_walk($param, function (&$v, $k) {
                $v = [$k, '=', $v];
            });
        }
        return array_merge($data, $param);
    }


    /**
     * 指定查询方式
     * @param string|null $value 值
     * @param null|string $field 字段
     * @param null|string $expression 表达式
     * @param null|string $relation
     * 当变量$expression 被正则匹配到时, $relation 为'or'或者 'and',默认为'and';
     * 当变量$expression 为'in','like'或'exp' $relation 为特殊符号(作为分割变量$value的符号),模糊查询或者原生查询Db::raw('>score')
     * 当变量$expression 没有被正则匹配到时, $relation 为空值或者特殊符号(作为分割变量$value的符号)
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function designation(?string $value = null, ?string $field = null, ?string $expression = null, ?string $relation = null)
    {
        if (empty($field) === true) {
            return false;
        }
        if (preg_match('/\|+/', $expression) > 0) {
            $array = explode('|', $expression);
            $logic = [];
            foreach ($array as $k => $v) {
                if (preg_match('/\:+/', $v)) {
                    list($key, $val) = explode(':', $v);
                    $logic[] = [$key, $val];
                } else {
                    $logic[] = [$v, $value];
                }
            }
            return [$field, $logic, $relation ?? 'and'];
        }

        switch ($expression) {
            case 'in':
                return [$field, 'in', $this->spacesClear($value, $relation)];
                break;
            case 'like':
                return [$field, 'like', "%{$value}%"];
                break;
            case 'like%':
                return [$field, 'like', "{$value}%"];
                break;
            case '%like':
                return [$field, 'like', "%{$value}"];
                break;
            case 'exp':
                return [$field, 'exp', Db::raw($value)];
                break;
            default:
                break;
        }
        if (empty($expression) === false && empty($relation) === false) {
            return [$field, $expression, $this->spacesClear($value, $relation)];
        } else if (empty($expression) === false && empty($relation) === true) {
            return [$field, $expression, $this->spacesClear($value, $relation)];
        } elseif (empty($expression) === true && empty($relation) === false) {
            return $this->spacesClear($value, $relation);
        }

        return $value;
    }

    /**
     * 分割字符串,去除字符两边空格
     * @param null|string $string 字符
     * @param string|null $separation 分割符号
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function spacesClear(?string $string = null, ?string $separation = ',')
    {
        if (empty($string) === true) {
            return $string;
        }
        if (empty($separation) === false && empty($string) === false) {
            list($a, $b) = explode($separation, $string);
            return [trim($a), trim($b)];
        }
        return trim($string);
    }

    /**
     * 时间调整
     * @param string $times 时间
     * @param string $start 开始字段
     * @param string $end 结束字段
     * @param string $delimiter 分隔符
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function cycle(?string $times = null, ?string $start = '', ?string $end = '', string $delimiter = '~'): array
    {
        if (empty($times) === true) {
            return [0 => null, 1 => null];
        }
        list($startTime, $endTime) = explode($delimiter, $times);
        if (empty($start) === false && empty($end) === false) {
            return [$start => trim($startTime), $end => trim($endTime)];
        } else {
            return [0 => trim($startTime), 1 => trim($endTime)];
        }
    }
}
