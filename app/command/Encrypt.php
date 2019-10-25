<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\{
    Argument,Option
};
use think\console\Output;

class Encrypt extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('encrypt')
            ->addArgument('type', Argument::REQUIRED, "'encrypt' or 'decode' default is encrypt")
            ->addArgument('string', Argument::REQUIRED, "Encrypted string")
            ->addArgument('keys', Argument::OPTIONAL, "Encryption keys",'1234567890')
            ->addOption('mothod', 'm', Option::VALUE_REQUIRED, 'Encryption method')
            ->setDescription('Encrypted string, default is AES-256-CBC');

    }

    protected function execute(Input $input, Output $output)
    {
        $type = trim($input->getArgument('type'));

        $string = trim($input->getArgument('string'));
        if (empty($string) === true){
            $output->writeln('<error>String is required</error>');
            return false;
        }

        $key = trim($input->getArgument('keys'));
        if (empty($key) === true){
            $output->writeln('<error>key is required</error>');
            return false;
        }

        if ($input->hasOption('mothod') === true) {
            $mothod =$input->getOption('mothod');
        } else {
            $mothod = 'AES-256-CBC';
        }

        switch ($type){
            case "encrypt":
                $result=$this->thoughtEncrypt($string,$key,$mothod);
                break;
            case "decode":
                $result=$this->thoughtDecrypt($string,$key,$mothod);
                break;
            default:
                $output->writeln('<error>type is required</error>');
                return false;
        }

    	// 指令输出
        $output->writeln("key:{$key} \r\n {$type} The result of the encryption is: \n{$result}");
    }


    /**
     * openssl 解密
     * @param string|null $string
     * @param string|null $key
     * @param string|null $mothod
     * @return string
     */
    protected function thoughtDecrypt(?string $string=null,?string $key=null,?string $mothod=null)
    {
        $data    = json_decode(base64_decode($string), true);
        $decrypt = openssl_decrypt($data['value'], $mothod, $key, 0, hex2bin($data['iv']));
        return $decrypt;
    }


    /**
     * openssl 加密
     * @param string|null $string
     * @param string|null $key
     * @param string|null $mothod
     * @return string
     */
    protected function thoughtEncrypt(?string $string=null,?string $key=null,?string $mothod=null)
    {
        if (empty($string) === true) {
            return '';
        }
        $iv               = openssl_random_pseudo_bytes(openssl_cipher_iv_length($mothod));
        $encrypt['value'] = openssl_encrypt($string, $mothod, $key, 0, $iv);
        $encrypt['iv']    = bin2hex($iv);
        return base64_encode(json_encode($encrypt));
    }

}
