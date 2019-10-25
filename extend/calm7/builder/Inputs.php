<?php
/**
 * Description of Text.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/10/27 15:51
 */

namespace calm7\builder;

use think\Exception;

class Inputs
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
     * text文本框
     * @author staitc7 <static7@qq.com>
     * @param string $label 标题
     * @param string $name input名称
     * @param string $verify 验证
     * @return mixed
     * @throws Exception
     */
    public function input($label = '', $name = '', $verify = '')
    {
        return $this->readTemp(__FUNCTION__, $label, $name, $verify);
    }

    /**
     * 文本区域
     * @author staitc7 <static7@qq.com>
     * @param string $label
     * @param string $name
     * @param string $verify
     * @param int    $height
     * @return mixed
     * @throws Exception
     */
    public function textarea($label = '', $name = '', $verify = '',$height=6)
    {
        return $this->readTemp(__FUNCTION__, $label, $name, $verify,$height);
    }

    /**
     * 隐藏域
     * @author staitc7 <static7@qq.com>
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function hidden( $name = '')
    {
        return $this->readTemp(__FUNCTION__,  $name);
    }

    /**
     * 单选框
     * @author staitc7 <static7@qq.com>
     * @param string $label
     * @param string $name
     * @param string $number
     * @return mixed
     * @throws Exception
     */
    public function radio($label = '', $name = '', $number = '')
    {
        $html = PHP_EOL;
        if ($number > 0) {
            for ($i = 0; $i <= $number; $i++) {
                $html .= "<input type='radio' name='{$name}[{$i}]' lay-skin='primary' title='{$name}{$i}'>".PHP_EOL;
            }
        }
        $path = $this->tempPath . '/Html/' . __FUNCTION__ . '.html';
        if (!is_file($path)) {
            throw new Exception($path . ' 不存在!');
        }
        $content = file_get_contents($path);
        $content = sprintf($content, $label);
        return str_replace('{@radio}', $html, $content);
    }

    /**
     * 复选框
     * @author staitc7 <static7@qq.com>
     * @param string $label
     * @param string $name
     * @param int    $number 个数
     * @return mixed
     * @throws Exception
     */
    public function checkbox($label = '', $name = '', $number = 0)
    {
        $html = PHP_EOL;
        if ($number > 0) {
            for ($i = 0; $i <= $number; $i++) {
                $html .= "<input type='checkbox' name='{$name}[{$i}]' lay-skin='primary' title='{$name}{$i}'>".PHP_EOL;
            }
        }
        $path = $this->tempPath . '/Html/' . __FUNCTION__ . '.html';
        if (!is_file($path)) {
            throw new Exception($path . ' 不存在!');
        }
        $content = file_get_contents($path);
        $content = sprintf($content, $label);
        return str_replace('{@checkbox}', $html, $content);
    }

    /**
     * 下拉框
     * @author staitc7 <static7@qq.com>
     * @param string $label
     * @param string $name
     * @param int    $number
     * @return mixed
     * @throws Exception
     */
    public function select($label = '', $name = '', $number = 0)
    {
        $html = PHP_EOL;
        if ($number > 0) {
            for ($i = 0; $i <= $number; $i++) {
                $html .= "<option value='{$i}'>选择{$i}</option>".PHP_EOL;
            }
        }
        $path = $this->tempPath . '/Html/' . __FUNCTION__ . '.html';
        if (!is_file($path)) {
            throw new Exception($path . ' 不存在!');
        }
        $content = file_get_contents($path);
        $content = sprintf($content, $label,$name);
        return str_replace('{@select}', $html, $content);
    }

    /**
     * 密码框
     * @author staitc7 <static7@qq.com>
     * @param string $label
     * @param string $name
     * @param string $verify
     * @return mixed
     * @throws Exception
     */
    public function password($label = '', $name = '', $verify = '')
    {
        return $this->readTemp(__FUNCTION__, $label, $name, $verify);
    }

    /**
     * 图片上传
     * @author staitc7 <static7@qq.com>
     * @param string $label 图片上传
     * @param string $name
     * @param string $button
     * @return mixed
     * @throws Exception
     */
    public function pictureUpload($label = '',$name = '',$button='')
    {
        $pictureUpload    = $this->readTemp(__FUNCTION__, $label,$button);
        $pictureUploadJs = $this->readTempJs(__FUNCTION__, $name);
        return ['content' => $pictureUpload, 'js' => $pictureUploadJs];
    }

    /**
     * 文件上传
     * @author staitc7 <static7@qq.com>
     * @param string $label
     * @param string $name
     * @param string $button
     * @return mixed
     * @throws Exception
     */
    public function fileUpload($label = '',$name = '',$button='')
    {
        $fileUpload    = $this->readTemp(__FUNCTION__, $label,$button);
        $fileUploadJs = $this->readTempJs(__FUNCTION__, $name);
        return ['content' => $fileUpload, 'js' => $fileUploadJs];
    }

    /**
     * 编辑器
     * @author staitc7 <static7@qq.com>
     * @param string $label
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function layedit($label = '', $name = '')
    {
        $layedit    = $this->readTemp(__FUNCTION__, $label, $name);
        $layeditJs = $this->readTempJs(__FUNCTION__, $name);
        return ['content' => $layedit, 'js' => $layeditJs];
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
        $path = $this->tempPath . '/Html/' . $functionName . '.html';
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