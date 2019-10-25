<?php

namespace app\command\depot;

use think\facade\{App, Config, Env, Request};
use think\console\{Command, Output, Input, input\Argument, input\Option};

class Depot extends Command
{

    /**
     * 获取模板
     * @var $template
     */
    protected $template;

    /**
     * 文件夹
     * @var $folder
     */
    protected $folder;

    /**
     * 后缀
     * @var $suffix
     */
    protected $suffix;

    /**
     * 需要用的名称
     * @var $use
     */
    protected $use;

    /**
     * @var 应用类
     */
    protected $app;

    /**
     * 仓库模式所需的文件
     * 格式是 '文件夹'=>['后缀名','实例化类(必须是相同的名字)' ],
     * 例如:'service'=>['Service','Repository'],
     * @var array
     */
    protected $array = [
        'repository' => 'Repository',  //知识库
        'service' => ['Service','Repository'], //服务类
        'validate' => '', //验证器
        'model' => '', //模型类
        'controller' => '', //控制器
    ];

    /**
     * 配置
     */
    protected function configure()
    {
        // 指令配置
        $this->setName('depot')
            ->addArgument('name', Argument::REQUIRED, "The name of the class")
            ->setDescription('Generate warehouse model');
    }

    /**
     * 执行生成
     * @param Input  $input
     * @param Output $output
     * @return bool
     */
    protected function execute(Input $input, Output $output)
    {

        $name = trim($input->getArgument('name'));

        foreach ($this->array as $folder => $suffix) {
            if (is_array($suffix) === true){
                foreach ($suffix as $k=>$v){
                    switch ($v){
                        case 'Service':
                            $this->suffix=$v;
                            break;
                        case 'Repository':
                            $this->use=$v;
                            break;
                        default:
                            break;
                    }
                }
            }else{
                $this->suffix=$suffix;
            }

            $this->folder=$folder;

            //获取模板
            $this->template = $this->getTemplate($folder);
            if ($this->template === false) {
                $output->writeln('<error>' . $this->folder . ' not exists!</error>');
                return false;
            }
            //获取命名空间
            $className = $this->getClassName($name);

            //获取引入的命名空间
            if (empty($this->use) === false){
                $useClassName = $this->getClassName($name);
                $useClassName=str_replace($folder,strtolower($this->use),$useClassName);
                $this->use="\\{$useClassName}{$this->use}()";
            }

            //获取文件路径
            $pathName = $this->getPathName($className);
            if (is_file($pathName) === true) {
                $output->writeln('<error>' . $this->folder . ' already exists!</error>');
                continue;
            }
            if (is_dir(dirname($pathName)) === false) {
                mkdir(dirname($pathName), 0755, true);
            }

            file_put_contents($pathName, $this->buildClass($className, $this->suffix));

            $output->writeln('<info>' . $this->folder . ' created successfully.</info>');
        }
    }

    /**
     * 获取类名
     * @param string|null $name 模块/类名
     * @return mixed|string
     */
    protected function getClassName(?string $name = null)
    {
        $appNamespace = App::getNamespace();
        if (strpos($name, $appNamespace . '\\') !== false) {
            return $name;
        }

        if (Config::get('app.auto_multi_app') === true) {
            if (strpos($name, '/')) {
                list($this->app, $name) = explode('/', $name, 2);
            } else {
                $this->app = 'common';
            }
        } else {
            $this->app = '';
        }

        if (strpos($name, '/') !== false) {
            $name = str_replace('/', '\\', $name);
        }
        return $this->getNamespace($appNamespace, $this->app) . "\\{$this->folder}\\{$name}";
    }

    /**
     * 获取命名空间
     * @param $appNamespace
     * @param $app
     * @return string
     */
    protected function getNamespace($appNamespace, $app)
    {
        return $app ? ($appNamespace . '\\' . $app) : $appNamespace;
    }

    /**
     * 文件路径
     * @param $name
     * @return string
     */
    protected function getPathName($name)
    {
        $name = str_replace(App::getNamespace() . '\\', '', $name);
        $name= $name.$this->suffix;
        return App::getAppPath() . ltrim(str_replace('\\', '/', $name), '/') . '.php';
    }

    /**
     * 生成模板
     * @param string|null $name
     * @param string|null $suffix
     * @return mixed
     */
    protected function buildClass(?string $name = null, ?string $suffix = '')
    {
        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
        $class = str_replace($namespace . '\\', '', $name);

        return str_replace([
            '{%className%}',
            '{%actionSuffix%}',
            '{%namespace%}',
            '{%app_namespace%}',
            '{%model%}',
            "{%newClass%}",
            "{%app%}"
        ], [
            $class . $suffix,
            Config::get('route.action_suffix'),
            $namespace,
            App::getNamespace(),
            $class,
            $this->use,
            $this->app,
        ], $this->template);
    }

    /**
     * 获取模板
     * @param string|null $folder
     * @return string
     */
    protected function getTemplate(?string $folder = null)
    {
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $folder . '.tpl';
        if (is_file($filePath) === false) {
            return false;
        }
        return file_get_contents($filePath);
    }

}
