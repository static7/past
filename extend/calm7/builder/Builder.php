<?php
/**
 * Description of Builder.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/10/27 15:31
 */

namespace calm7\builder;

use calm7\builder\Inputs;
use think\Exception;
use think\facade\App;
use think\facade\Log;
use think\facade\Env;

class Builder
{
    //模板替换配置标识
    protected $config = [
        'html' => '{@html}',
        'javascript' => '{@javascript}'
    ];

    //替换正则表达式
    protected $expression = [
        '/\{@script\}/',
        '/\{\/@script\}/',
        '/\{@scriptHtml\}/',
        '/\{@scriptHtml\#operating\}/',
        '/\{@scriptHtml\#toolbarTpl}/',
        '/\{@scriptHtml\#statusTpl}/',

    ];

    //标签组
    protected $label = [
        '<script type="text/javascript">',
        '</script>',
        '<script type="text/html" id="">',
        '<script type="text/html" id="operating">',
        '<script type="text/html" id="toolbarTpl">',
        '<script type="text/html" id="statusTpl">',

    ];

    //表单内容
    protected $html;

    //js模板
    protected $js;

    //替换的内容
    protected $content;

    //替换的js脚本内容
    protected $js_content;

    //保存路径
    protected $path;

    //基础模板
    protected $temp;

    protected $templatePath = '';


    /**
     * 初始化
     * Builder constructor.
     * * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        $this->templatePath = App::getRootPath() . '/template';
    }

    /**
     * 获取基础模板
     * @author staitc7 <static7@qq.com>
     * @param string $name 模板名称或者路径
     * @return mixed
     * @throws Exception
     */
    public function baseTemp($name = '')
    {
        if (is_file($name)) {
            $file_path = $name;
        } else {
            $file_path = __DIR__ . '/Base/' . $name . '.html';
        }
        if (!file_exists($file_path)) {
            throw new Exception($file_path . ' 文件不存在');
        }
        return file_get_contents($file_path);//将整个文件内容读入到一个字符串中
    }

    /**
     * 设置内容(一般为开头)
     * @author staitc7 <static7@qq.com>
     * @param string $value
     * @return mixed
     */
    public function setContent($value = '')
    {
        $this->content = $value;
        return $this;
    }

    /**
     * 获取内容
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 当前节点追加内容
     * @author staitc7 <static7@qq.com>
     * @param string $type
     * @param string $value
     * @return mixed
     */
    public function appendContent($type = '', $value = '')
    {
        if (empty($type)  === false) {
            return $this->content .= $value;
        }
        return $this->content;
    }

    /**
     * js脚本设置内容
     * @author staitc7 <static7@qq.com>
     * @param string $value
     * @return mixed
     */
    public function setJsContent($value = '')
    {
        $this->js_content = $value;
        return $this;
    }

    /**
     * 获取js脚本内容
     * @author staitc7 <static7@qq.com>
     * @return mixed
     */
    public function getJsContent()
    {
        return $this->js_content;
    }

    /**
     * 内容替换
     * @author staitc7 <static7@qq.com>
     * @param string $mark html替换标识
     * @param null   $data
     * @return mixed
     */
    private function replaceContent($mark = '', $data = null)
    {
        $search = $mark ?: $this->config['html'];
        if (is_array($search) && is_array($data)) {
            foreach ($search as $k => $v) {
                if (is_numeric($k) && empty($data[ $k ])) {
                    $this->temp = str_replace($v, $this->content, $this->temp);
                } else {
                    $this->temp = str_replace($v, $data[ $k ], $this->temp);
                }
            }
        } else {
            $this->temp = str_replace($search, $this->content, $this->temp);
        }
        return $this;
    }

    /**
     * js脚本内容替换
     * @author staitc7 <static7@qq.com>
     * @param string $mark js脚本替换标识
     * @param null   $data
     * @return mixed
     */
    private function replaceJsContent($mark = '', $data = null)
    {
        $search = $mark ?: $this->config['javascript'];
        if (is_array($search) && is_array($data)) {
            foreach ($search as $k => $v) {
                if (is_numeric($k) && empty($data[ $k ])) {
                    $this->temp = str_replace($v, $this->js_content, $this->temp);
                } else {
                    $this->temp = str_replace($v, $data[ $k ], $this->temp);
                }
            }
        } else {
            $this->temp = str_replace($search, $this->js_content, $this->temp);
        }
        $this->temp = preg_replace($this->expression, $this->label, $this->temp);
        return $this;
    }


    /**
     * 生成检测
     * @author staitc7 <static7@qq.com>
     * @param string $base_temp 基础模板
     * @param string $htmlMark
     * @param string $jsMark
     * @return mixed
     * @throws Exception
     */
    public function generate($base_temp = '', $htmlMark = '', $jsMark = '')
    {
        if (empty($base_temp) && empty($this->temp)) {
            throw new Exception('基础模板不能为空');
        }
        $htmlMark = $htmlMark ?: $this->config['html'];
        if (is_array($htmlMark)) {
            foreach ($htmlMark as $key => $value) {
                if (strpos($this->temp, $value) === false) {
                    throw new Exception('html内的标识不能为空');
                }
            }
        } else {
            if (strpos($this->temp, $htmlMark) === false) {
                throw new Exception('html内的标识不能为空');
            }
        }
        $jsMark = $jsMark ?: $this->config['javascript'];
        if (is_array($jsMark)) {
            foreach ($jsMark as $key => $value) {
                if (strpos($this->temp, $value) === false) {
                    throw new Exception('javascript内的标识不能为空');
                }
            }
        } else {
            if (strpos($this->temp, $jsMark) === false) {
                throw new Exception('javascript内的标识不能为空');
            }
        }
        return true;
    }

    /**
     * 文件写入
     * @access public
     * @param string $filename 文件名
     * @param string $content 文件内容
     * @return bool
     * @throws \Exception
     */
    private function put($filename, $content)
    {
        $dir = dirname(str_replace('//' , '/' , $filename));
        if (is_dir($dir) === false) {
            mkdir($dir, 0755, true);
        }
        try{
            $fptr = fopen(str_replace('\\' , '/' , $filename) , 'w');
            fwrite($fptr , $content);
            fclose($fptr);
        }catch (\Exception $e){
            throw new \Exception('存储器写入错误:' . $filename  . $e->getMessage());
        }
        return true;
    }

    /**
     * 解析模板
     * @author staitc7 <static7@qq.com>
     * @param array  $data 数据
     * @param string $htmlMark html标识
     * @param string $jsMark js脚本标识
     * @return mixed
     * @throws Exception
     */
    public function formTemplate($data = [], $htmlMark = '', $jsMark = '')
    {
        if (isset($data['base_temp']) && $data['base_temp']) {
            $this->temp = $data['base_temp'];
        }
        if (isset($data['temp_path']) && $data['temp_path']) {
            $this->path = $this->templatePath. '/' . $data['temp_path'];
        }
        $this->generate($data['base_temp'], $htmlMark, $jsMark);
        if (isset($data['data']) && $data['data']) {
            $list   = array_merge($data['data']);
            $Inputs = new Inputs();
            foreach ($list as $k => $v) {
                switch ($v['type']) {
                    case 1:
                        $this->content .= $Inputs->password($v['label'], $v['name']);
                        break;
                    case 2:
                        $this->content .= $Inputs->checkbox($v['label'], $v['name'], $v['number']);
                        break;
                    case 3:
                        $this->content .= $Inputs->radio($v['label'], $v['name'], $v['number']);
                        break;
                    case 4:
                        $this->content .= $Inputs->hidden($v['name']);
                        break;
                    case 5:
                        $this->content .= $Inputs->textarea($v['label'], $v['name'], $v['verify'], $v['height']);
                        break;
                    case 6:
                        $pictureUpload    = $Inputs->pictureUpload($v['label'], $v['name'], $v['button']);
                        $this->content    .= $pictureUpload['content'];
                        $this->js_content .= $pictureUpload['js'];
                        break;
                    case 7:
                        $fileUpload       = $Inputs->fileUpload($v['label'], $v['name'], $v['button']);
                        $this->content    .= $fileUpload['content'];
                        $this->js_content .= $fileUpload['js'];
                        break;
                    case 8:
                        $layedit          = $Inputs->layedit($v['label'], $v['name']);
                        $this->content    .= $layedit['content'];
                        $this->js_content .= $layedit['js'];
                        break;
                    case 9:
                        $this->content .= $Inputs->select($v['label'], $v['name'], $v['number']);
                        break;
                    default:
                        $this->content .= $Inputs->input($v['label'], $v['name'], $v['verify']);
                        break;
                }
            }
        }
        $this->replaceContent($htmlMark);
        $this->replaceJsContent($jsMark);
        return $this->put($this->path, $this->temp);
    }


    /**
     * 解析数据
     * @author staitc7 <static7@qq.com>
     * @param array  $data 数据
     * @param string $htmlMark html标识
     * @param string $jsMark js脚本标识
     * @return mixed
     * @throws Exception
     */
    public function dataTemplate($data = [], $htmlMark = '', $jsMark = '')
    {
        if (isset($data['base_temp']) && $data['base_temp']) {
            $this->temp = $data['base_temp'];
        }
        if (isset($data['temp_path']) && $data['temp_path']) {
            $this->path = $this->templatePath. '/' . $data['temp_path'];
        }
        $this->generate($data['base_temp'], $htmlMark, $jsMark);
        //解析搜索框
        if (isset($data['search']) && $data['search']) {
            $search = array_merge($data['search']);
            foreach ($search as $k => $v) {
                $Collection = new Collection();
                switch ($v['type']) {
                    case 1:
                        $this->content .= $Collection->searchHidden($v['name']);
                        break;
                    case 2:
                        $this->content .= $Collection->searchSelect($v['label'],$v['name'], $v['verify'],$v['select']);
                        break;
                    case 3:
                        $searchDate       = $Collection->searchDate($v['label'],$v['name']);
                        $this->content    .= $searchDate['content'];
                        $this->js_content .= $searchDate['js'];
                        break;
                    case 4:
                        $searchDate       = $Collection->searchLinkage($v['url']);
                        $this->content    .= $searchDate['content'];
                        $this->js_content .= $searchDate['js'];
                        break;
                    case 5:
                        $searchDate       = $Collection->searchCategory($v['url']);
                        $this->content    .= $searchDate['content'];
                        $this->js_content .= $searchDate['js'];
                        break;
                    default:
                        $this->content .= $Collection->searchInput($v['label'],$v['name'], $v['verify']);
                        break;
                }
            }
        }
        //解析数据
        if (isset($data['collection']) && $data['collection']) {
            $dataList   = array_merge($data['collection']);
            $string     = '';
            $Collection = new Collection();
            foreach ($dataList as $k => $v) {
                $string .= $Collection->dataList($v);
            }
            $data['collection'] = $string;
        }
        $this->replaceContent($htmlMark, $data);
        $this->replaceJsContent($jsMark, $data);
        return $this->put($this->path, $this->temp);
    }


}