<?php

namespace app\listener\admin;

use think\facade\{
    Config, Cache, Log,Db
};

/**
 * Description of SystemConfig
 * 系统配置初始化
 * @author static7
 */
class SystemConfig
{
    /**
     * 系统配置读取并缓存
     * @author staitc7 <static7@qq.com>
     */

    public function handle()
    {
        $config = Cache::get('admin_config',[]);
        if (empty($config) === false) {
            return Config::set($config, 'admin_config');
        }
        $data   = Db::name('configuration')
            ->where('area', 'in', [0,2])
            ->where('status', '=', 1)
            ->field(['type', 'name', 'value'])
            ->select();
        $config = [];
        if (empty($data) === false) {
            foreach ($data as $value) {
                $config[ strtolower($value['name']) ] = $this->parse($value['type'], $value['value']);
            }
        }
        unset($data);
        Cache::tag(['system_config'])->set('admin_config', $config);
        Log::record("系统配置初始化成功", 'system');
        return;
    }


    /**
     * 根据配置类型解析配置
     * @param  integer $type 配置类型
     * @param  string  $value 配置值
     * @return array|string
     */
    private function parse($type, $value)
    {
        switch ($type) {
            case 2:
                $value = htmlspecialchars($value);
                break;
            case 3: //解析数组
                $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
                if (strpos($value, ':')) {
                    $value = [];
                    foreach ($array as $val) {
                        list($k, $v) = explode(':', $val);
                        $value[ $k ] = $v;
                    }
                } else {
                    $value = $array;
                }
                break;
            case 5:
                $value = (int)$value;
                break;
        }
        return $value;
    }
}
