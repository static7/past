<?php
/**
 * Description of UeditorService.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/10/16 10:17
 */

namespace app\admin\service;

use think\facade\{
    Request,Log,App
};

class UeditorService
{
    private $config = null;

    public function __construct()
    {
        $file = App::getConfigPath() . 'admin/ueditor.json';
        if (is_file($file) === true) {
            $this->config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($file)), true);
        }
    }

    /**
     * 富文本编辑器 行为
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function action(?array $param = [])
    {
        if (isset($param['action']) === false) {
            return false;
        }
        $data = null;
        switch ($param['action']) {
            case 'config':
                $data = $this->config ?: false;
                break;
            case 'listimage':
                $data=$this->listImage($param);
                break;
            case 'listfile':
                $data=$this->listFile($param);
                break;
            default:
                $data = false;
                break;
        }
        return $data;
    }


    /**
     * 文件(图片)列表
     * @author staitc7 <static7@qq.com>
     * @param array $param
     * @param array $config
     * @return mixed
     */
    private function listManager(array $param=[],array $config=[])
    {
        $start = isset($param['start']) ? (int)$param['start'] : 0;
        $end = $start + $config['size'];

        //获取文件列表
        $path = Request::server('document_root'). (substr($config['path'], 0, 1) == "/" ? "":"/") . $config['path'];
        $files = $this->getfiles($path, $config['allowFiles']);
        if (count($files) === 0) {
            return [
                "state" => "failure",
                "list" => [],
                "start" => $start,
                "total" => count($files)
            ];
        }
        //获取指定范围的列表
        $len = count($files);
        $list = [];
        for ($i = min($end, $len) - 1; $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }
        return [
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        ];
    }

    /**
     * 获取管理文件
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function listImage(?array $param=[])
    {
        $path = $this->config['imageManagerListPath'];
        $allowFiles = substr(str_replace(".", "|", join("", $this->config['imageManagerAllowFiles'])), 1);
        $size = (isset($param['size']) === true && (int)$param['size'] > 0) ? (int)$param['size'] : $this->config['imageManagerListSize'];
        return $this->listManager($param,['path'=>$path,'allowFiles'=>$allowFiles,'size'=>$size]);
    }

    /**
     * 获取管理文件
     * @author staitc7 <static7@qq.com>
     * @param array|null $param
     * @return mixed
     */
    public function listFile(?array $param=[])
    {
        $path = $this->config['fileManagerListPath'];
        $allowFiles = substr(str_replace(".", "|", join("", $this->config['fileManagerAllowFiles'])), 1);
        $size = (isset($param['size']) === true && (int)$param['size'] > 0) ? (int)$param['size'] : $this->config['fileManagerListSize'];
        return $this->listManager($param,['path'=>$path,'allowFiles'=>$allowFiles,'size'=>$size]);
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @param       $path
     * @param       $allowFiles
     * @param array $files
     * @return array
     */
    private function getfiles($path, $allowFiles, &$files = [])
    {
        if (is_dir($path) === false) {
            return [];
        }
        if (substr($path, strlen($path) - 1) != '/') {
            $path .= '/';
        }
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->getfiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(" . $allowFiles . ")$/i", $file)) {
                        $files[] = [
                            'url' => substr($path2, strlen(Request::server('document_root'))),
                            'mtime' => filemtime($path2)
                        ];
                    }
                }
            }
        }
        return $files ?? [];
    }
}