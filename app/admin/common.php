<?php

use app\facade\UserInfo;
use think\facade\{
    Config,Request,Log,Cache,Cookie,Session,Db
};

if (!function_exists('get_action_type')) {
    /**
     * 获取行为类型
     * @param int|null $type 类型
     * @param bool     $all 是否返回全部类型
     * @return array|mixed
     * @author huajie <banhuajie@163.com>
     */
    function get_action_type(?int $type = null, bool $all = false)
    {
        $list = Config::get('app.action_type');
        if ($all) {
            return $list;
        }
        return isset($list[ $type ]) ? $list[ $type ] : null;
    }
}

if (!function_exists('parse_config_attr')) {
    /**
     *  分析枚举类型配置值
     *  格式 a:名称1,b:名称2
     * @param string $string 配置值
     * @return array
     */
    function parse_config_attr($string)
    {
        $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
        if (strpos($string, ':')) {
            $value = [];
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $value[ $k ] = $v;
            }
        } else {
            $value = $array;
        }
        return $value;
    }
}

if (!function_exists('get_avatar')) {
    /**
     * 获取用户头像
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\db\exception\DbException
     */
    function get_avatar()
    {
        $default=Config::get('app.default_picture');
        $info = \app\facade\UserInfo::getMemberInfo();
        if (!isset($info['avatar']) || empty($info['avatar']) === true) {
            return $default;
        }
        return $info['avatar'];
    }
}

if (!function_exists('check_position')) {
    /**
     * 检查$pos(推荐位的值)是否包含指定推荐位$contain
     * @param int|number $pos 推荐位的值
     * @param int|number $contain 指定推荐位
     * @return bool true 包含 ， false 不包含
     * @author huajie <banhuajie@163.com>
     */
    function check_position(int $pos = 0, int $contain = 0)
    {
        if (empty($pos) === true || empty($contain) === true) {
            return false;
        }
        $res = $pos & $contain; //将两个参数进行按位与运算，不为0则表示$contain属于$pos
        return ($res !== 0) ? true : false;
    }
}

if (!function_exists('check_category')) {
    /**
     * 检查该分类是否允许发布内容
     * @param int        $id 分类id
     * @param int|string $field 字段
     * @param bool|int   $direct 直接返回
     * @return bool|int|mixed
     * @author static7 <static7@qq.com>
     */
    function check_category(int $id = 0, string $field = 'id', bool $direct = false)
    {
        $category = Db::name('Category')
            ->where('id', '=',(int)$id)
            ->where('status','=', 1)
            ->value($field);
        if ($direct === true) {
            return (int)$category;
        }
        return (int)$category === 0 ? false :true;
    }
}

if (!function_exists('ucenter_md5')) {
    /**
     * 系统非常规MD5加密方法
     * @param string $str 要加密的字符串
     * @param string $key 默认密钥
     * @return string
     */
    function ucenter_md5($str, $key = '')
    {
        $key = empty($key) ? Config::get('key.uc_auth_key') : $key;
        return (string)$str === '' ? '' : md5(sha1($str) . $key);
    }
}

if (!function_exists('data_auth_sign')) {
    /**
     * 数据签名认证
     * @param array $data 被认证的数据
     * @return string       签名
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    function data_auth_sign(array $data)
    {
        ksort($data); //排序
        $code = http_build_query($data); //url编码并生成query字符串
        return sha1($code);//生成签名
    }
}

if (!function_exists('list_to_tree')) {
    /**
     * 把返回的数据集转换成Tree
     * @param array  $list 要转换的数据集
     * @param string $pk
     * @param string $pid parent标记字段
     * @param string $child level标记字段
     * @param int    $root 根
     * @return array
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
    {
        if (empty($list) === true) {
            return [];
        }
        // 创建Tree
        $tree = [];
        if (is_array($list) === true) {
            //检测是否是对象
            foreach ($list as $k => $v) {
                if (is_object($v) === false) {
                    continue;
                }
                $list[ $k ] = $v->toArray();
            }

            // 创建基于主键的数组引用
            $refer = [];
            foreach ($list as $key => $data) {
                $refer[ $data[ $pk ] ] = &$list[ $key ];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[ $pid ];
                if ($root == $parentId) {
                    $tree[] = &$list[ $key ];
                } else {
                    if (isset($refer[ $parentId ])) {
                        $parent             = &$refer[ $parentId ];
                        $parent[ $child ][] = &$list[ $key ];
                    }
                }
            }
        }
        return $tree;
    }
}

if (!function_exists('change_status')) {
    /**
     * 从数组中取出索引项
     * @param type       $arg 参数
     * @param array|type $list 数组
     * @return string
     */
    function change_status($arg, $list = ['-1' => '删除', '0' => '禁用', '1' => '正常'])
    {
        if (array_key_exists($arg, $list)) {
            $value = $list[ $arg ];
        }
        return $value ?? '未知';
    }
}

if (!function_exists('get_nickname')) {
    /**
     * 根据用户ID获取用户昵称
     * @param integer $user_id 用户ID
     * @return string       用户昵称
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    function get_nickname(?int $user_id = 0)
    {
        return app\facade\UserInfo::getNickName($user_id);
    }
}

if (!function_exists('time_format')) {
    /**
     * 时间戳格式化
     * @param int|string $time 时间戳或时间
     * @param string     $format 时间格式
     * @return string 完整的时间显示
     * @author huajie <banhuajie163.com>
     */
    function time_format($time = null, $format = 'Y-m-d H:i:s')
    {
        if (empty($time) === true) {
            return '';
        }
        if (is_numeric($time)) {
            return date($format, $time);
        }
        return date($format, strtotime($time));
    }
}

if (!function_exists('format_bytes')) {
    /**
     * 格式化字节大小
     * @param number $size 字节数
     * @param string $delimiter 数字和单位分隔符
     * @return string            格式化后的带单位的大小
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }
        return (floor($size * 100) / 100) . $delimiter . $units[ $i ];
    }
}

if (!function_exists('in_the_array')) {

    /**
     * 提高效率的的函数 in_array() 函数
     * @param int|string $item 值
     * @param array      $array 数组
     * @return bool
     */
    function in_the_array($item = null, $array = [])
    {
        $flipArray = array_flip($array);
        return isset($flipArray[ $item ]);
    }
}

if (!function_exists('array_sole')) {
    /**
     * 提高效率的的函数 array_unique
     * @param array $array
     * @return array|null
     */
    function array_sole(array $array = [])
    {
        return array_values(array_flip(array_flip($array)));
    }
}

if (!function_exists('get_files')) {
    /**
     * 获取用户文件
     * @param int    $id
     * @param string $field
     * @return string
     */
    function get_files(int $id = 0, string $field = 'url')
    {
        if ($id < 1) {
            return '';
        }
        return Db::name('File')->where('status', '=', 1)->where('id', '=', $id)->value($field ?? 'url');
    }
}

if (!function_exists('is_wechat')) {
    /**
     * 判断是否微信
     */
    function is_wechat()
    {
        if (preg_match('/MicroMessenger/', Request::header('user_agent')) > 0) {
            return true;
        }
        return false;
    }
}

if (!function_exists('get_picture_url')) {
    /**
     * 获取图片
     * @param int|null $id
     * @return string
     */
    function get_picture_url(?int $id = 0)
    {
        $defaultPath = Config::get('app.default_images', '/static/images/null.gif');
        if ((int)$id < 1) {
            return $defaultPath; //返回默认图片
        }else{
            return get_files($id, 'url');
        }
    }
}


if (!function_exists('get_file_url')) {
    /**
     * 获取图片
     * @param int|null $id
     * @return string
     */
    function get_file_url(?int $id = 0)
    {
        $defaultPath = '';
        if ((int)$id < 1) {
            return $defaultPath; //返回默认图片
        }else{
            return get_files($id, 'url');
        }
    }
}


if (!function_exists('thought_decrypt')) {
    /**
     * openssl 解密
     * @param $string
     * @return string
     */
    function thought_decrypt(?string $string = null)
    {
        if (empty($string) === true) {
            return '';
        }
        $mothod  = Config::get('key.mothod', 'AES-256-CBC');
        $key     = Config::get('key.data_auth_key', 's1234567890');
        $data    = json_decode(base64_decode($string), true);
        $decrypt = openssl_decrypt($data['value'], $mothod, $key, 0, hex2bin($data['iv']));
        return $decrypt;
    }
}

if (!function_exists('thought_encrypt')) {
    /**
     * openssl 加密
     * @param $string
     * @return string
     */
    function thought_encrypt(?string $string = null)
    {
        if (empty($string) === true) {
            return '';
        }
        $mothod           = Config::get('key.mothod', 'AES-256-CBC');
        $iv               = openssl_random_pseudo_bytes(openssl_cipher_iv_length($mothod));
        $key              = Config::get('key.data_auth_key', 's1234567890');
        $encrypt['value'] = openssl_encrypt($string, $mothod, $key, 0, $iv);
        $encrypt['iv']    = bin2hex($iv);
        return base64_encode(json_encode($encrypt));
    }
}

if (!function_exists('system_encrypt')) {
    /**
     * 系统加密
     * @param string $string
     * @return bool|string
     */
    function system_encrypt(string $string = '')
    {
        if (empty($string) === true) {
            return '';
        }
        return password_hash($string,PASSWORD_DEFAULT);
    }

}

