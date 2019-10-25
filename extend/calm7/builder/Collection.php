<?php
/**
 * Description of Collection.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/11/9 13:41
 */

namespace calm7\builder;


class Collection
{
    //路径
    protected $tempPath = __DIR__;

    /**
     * 初始化
     * Inputs constructor.
     */
    public function __construct()
    {

    }

    /**
     * 下拉框
     * @author staitc7 <static7@qq.com>
     * @param string $label
     * @param string $name
     * @param string $verify
     * @param string $value
     * @return mixed
     */
    public function searchSelect($label='',$name = '', $verify='',$value='')
    {
        $html  = PHP_EOL;
        $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
        if (strpos($value, ':')) {
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $html        .= "<option value='{$k}'>{$v}</option>" . PHP_EOL;
            }
        }
        $path = $this->tempPath . '/Search/' . __FUNCTION__ . '.html';
        if (!is_file($path)) {
            throw new Exception($path . ' 不存在!');
        }
        $content = file_get_contents($path);
        $content = sprintf($content, $label, $name, $verify);
        return str_replace('{@select}', $html, $content);
    }

    /**
     * 隐藏域
     * @author staitc7 <static7@qq.com>
     * @param string $name
     * @return mixed
     */
    public function searchHidden($name='')
    {
        return $this->readTemp(__FUNCTION__,$name);
    }

    /**
     * 搜索input框
     * @author staitc7 <static7@qq.com>
     * @param string $label
     * @param string $name
     * @param string $verify
     * @return mixed
     */
    public function searchInput($label='',$name='',$verify='')
    {
        return $this->readTemp(__FUNCTION__,$label,$name,$verify);
    }

    /**
     * 数据列表生成
     * @author staitc7 <static7@qq.com>
     * @param null $data
     * @return mixed
     */
    public function dataList($data=null)
    {
        if (empty($data) === false){
            return json_encode($data,JSON_UNESCAPED_UNICODE).','.PHP_EOL;
        }
        return PHP_EOL;
    }

    /**
     * 日期
     * @author staitc7 <static7@qq.com>
     * @param string $label
     * @param string $name
     * @return mixed
     */
    public function searchDate($label='',$name='')
    {
        $searchDate    = $this->readTemp(__FUNCTION__,$label, $name);
        $searchDateJs = $this->readTempJs(__FUNCTION__,'');
        return ['content' => $searchDate, 'js' => $searchDateJs];
    }

    /**
     * 联动
     * @author staitc7 <static7@qq.com>
     * @param string $url
     * @return mixed
     */
    public function searchLinkage($url='')
    {
        $searchDate    = $this->readTemp(__FUNCTION__, '');
        $searchDateJs = $this->readTempJs(__FUNCTION__,$url);
        return ['content' => $searchDate, 'js' => $searchDateJs];
    }

    /**
     * 分类联动
     * @author staitc7 <static7@qq.com>
     * @param string $url
     * @return mixed
     */
    public function searchCategory($url='')
    {
        $searchDate    = $this->readTemp(__FUNCTION__,'');
        $searchDateJs = $this->readTempJs(__FUNCTION__,$url);
        return ['content' => $searchDate, 'js' => $searchDateJs];
    }

    /**
     * 读取HTML模板
     * @author staitc7 <static7@qq.com>
     * @param null   $functionName 函数名
     * @param array  $param 替换参数
     * @return mixed
     * @throws Exception
     */
    protected function readTemp($functionName=null,...$param)
    {
        $path = $this->tempPath . '/Search/' . $functionName . '.html';
        if (!is_file($path)) {
            throw new Exception($path . ' 不存在!');
        }
        $content = file_get_contents($path);
        return sprintf($content, ...$param);
    }

    /**
     * 读取HTML模板
     * @author staitc7 <static7@qq.com>
     * @param null   $functionName 函数名
     * @param array  $param 替换参数
     * @return mixed
     * @throws Exception
     */
    protected function readTempJs($functionName=null,...$param)
    {
        $path = $this->tempPath . '/Js/' . $functionName . '.js';
        if (!is_file($path)) {
            throw new Exception($path . ' 不存在!');
        }
        $content = file_get_contents($path);
        return sprintf($content, ...$param);
    }
}