<?php

namespace app\command;

use think\console\{
    Command,Input,Output,input\Option
};
use think\facade\{App};


class Key extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('key:build');
        // 设置参数
        $this->addOption('KeyName','k', Option::VALUE_REQUIRED,"key.php Configured key name");
        $this->setDescription("Generate key,Optional replacement file key.php replacement key");

    }

    protected function execute(Input $input, Output $output)
    {
        $key=$this->build_key();

        if ($input->hasOption('KeyName') === true) {
            $name = $input->getOption('KeyName');
            $filePath=App::getConfigPath().'key.php';
            if (is_file($filePath) === false){
                $output->writeln("<error>{$filePath} does not exist</error>");
                return false;
            }
            $content=file_get_contents($filePath);
            preg_match("/[\'|\"]{1}{$name}[\'|\"]{1}\=\>[\'|\"]{1}.*[\'|\"]{1}/",$content,$matches);
            if (empty($matches) === true){
                $output->writeln("<error>Can't find the corresponding match value</error>");
                return false;
            }
            $content=str_replace($matches[0],"'{$name}'=>'{$key}'",$content);

            $result=file_put_contents($filePath,$content);
            if ($result === false){
                $output->writeln("<error>File write failed, please check permissions</error>");
                return false;
            }
            $output->writeln("<info>Configuration replacement succeeded</info>");
            unset($filePath,$matches,$content,$result);
        }
    	// 指令输出
    	$output->writeln("Your current key is: \n\r".$key);
    }

    /**
     * 生成系统AUTH_KEY
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     * @param string|null $string
     * @return bool|string
     */
    protected function build_key(?string $string=null)
    {
        $str=$string ?: 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ`~!@#$%^&*()_+-=[]{};:|,.<>/?';
        $chars = str_shuffle($str);
        return substr($chars, 0, 64);
    }
}
