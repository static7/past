<?php
/**
 * Description of Captcha.php.
 * User: static7 <static7@qq.com>
 * Date: 2018/9/20 21:22
 */

namespace calm7\captcha;

class CaptchaService
{

    /**
     * @var string 链接
     */
    private $url='csec.api.qcloud.com/v2/index.php';
    /**
     * @var string 接口名
     */
    private $action='CaptchaIframeQuery';
    /**
     * @var int 验证码类型 1-9
     */
    private $captchaType=9;
    /**
     * @var int 验证码干扰程度
     */
    private $disturbLevel=1;
    /**
     * @var int 返回的JavaScript中是否使用HTTPS
     * 0：HTTP 1：HTTPS
     */
    private $isHttps=1;
    /**
     * @var int 客户端类型
     * 1：手机Web页面2：PCWeb页面 4：APP
     */
    private $clientType=2;

    /**
     * @var int 用户账号类型
     * 0：其他账号 1：QQ开放帐号 2：微信开放帐号 4：手机账号 6：手机动态码 7：邮箱账号
     */
    private $accountType=0;
    /**
     * @var string $secretId
     */
    private $secretId='';
    /**
     * @var string $secretKey
     */
    private $secretKey='';
    /**
     * @var array 其他参数
     */
    private $other=[];
    /**
     * @var string 请求模式
     */
    private $method='GET';
    /**
     * @var string 区域
     */
    private $region='sz';
    /**
     * @var string 错误信息
     */
    private $error='';
    /**
     * @var string 票据
     */
    private $ticket;

    /**
     * Captcha constructor.
     * 构造函数
     * @param string $secretId
     * @param string $secretKey
     * @param array  $param 其他参数
     * @throws \Exception
     */
    public function __construct(string $secretId,string $secretKey,array $param=[])
    {
        if (empty($secretId) ===true || empty($secretKey) === true){
            throw new \Exception('secretId或者secretKey不能为空');
        }
        $this->setSecretId($secretId);
        $this->setSecretKey($secretKey);
        $this->setOther($param);
    }

    /**
     * 生成验证码链接
     * @author staitc7 <static7@qq.com>
     * @return mixed
     * @throws \Exception
     */
    public function getJavaScriptSrc()
    {
        $param=[
              'isHttps'=>$this->isHttps,
              'captchaType'=>$this->captchaType,
              'disturbLevel'=>$this->disturbLevel,
              'clientType'=>$this->clientType,
              'accountType'=>$this->accountType,
        ];
        $data =$this->makeUrl(array_merge($this->other,$param));
        $result=json_decode($this->sendRequest($data),true);
        if ((int)$result['code'] !== 0 && (string)$result['codeDesc'] !== 'Success'){
            $this->setError('错误码:'.$result['code'].',错误信息:'.$result['message']);
            return false;
        }
        return $result['url'];
    }

    /**
     * 验证票据
     * @author staitc7 <static7@qq.com>
     * @return mixed
     * @throws \Exception
     */
    public function captchaCheck()
    {
        $param=[
            'captchaType'=>$this->captchaType,
            'accountType' =>$this->accountType,
            'ticket'=>$this->ticket,
        ];
        $data =$this->makeUrl(array_merge($this->other,$param));
        $result=json_decode($this->sendRequest($data),true);
        if ((int)$result['code'] !== 0 && (int)$result['codeDesc'] !== 'Success'){
            $this->setError('错误码:'.$result['code'].',错误信息:'.$result['message']);
            return false;
        }
        return true;
    }




    /**
     * 生成密钥和签名
     * @author staitc7 <static7@qq.com>
     * @param array  $param 参数
     * @return mixed
     */
    private function makeUrl($param=[])
    {
        $param['Nonce'] = (string)rand(0, 0x7fffffff);
        $param['Action'] = $this->action;
        $param['Region'] = $this->region;
        $param['SecretId'] = $this->secretId;
        $param['Timestamp'] = (string)time();
        ksort($param);
        $queryString=[];
        foreach ($param as $k => $v) {
            $queryString[] = $k.'='.$v;
        }
        $query=implode('&', $queryString);
        unset($queryString);
        $hash=hash_hmac('sha1', $this->method.$this->url.'?'.$query, $this->secretKey, true);
        $param['Signature']=base64_encode($hash);
        return $param;
    }

    /**
     * 请求
     * @author staitc7 <static7@qq.com>
     * @param array  $param
     * @param string $url
     * @param string $method
     * @param bool   $isHttps
     * @return mixed
     */
    public function sendRequest($param = [],$url = '', $method = 'get',$isHttps=true)
    {
        if ($url) {
            $this->url = $url;
        }

        if ($isHttps){
            $this->setUrl('https://'.$this->url);
        }else{
            $this->setUrl('http://'.$this->url);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $data = http_build_query($param ?? '');
        if (strtolower($method) == 'get') {
            curl_setopt($ch, CURLOPT_URL, $this->url . '?' . $data);
        } else {
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = sprintf("curl[%s] error[%s]", $this->url, curl_errno($ch) . ':' . curl_error($ch));
            throw new Exception($error);
        }
        curl_close($ch);
        return $result;
    }



    /**
     * @return string
     * @author staitc7 <static7@qq.com>
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @author staitc7 <static7@qq.com>
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     * @author staitc7 <static7@qq.com>
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @author staitc7 <static7@qq.com>
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return int
     * @author staitc7 <static7@qq.com>
     */
    public function getCaptchaType(): int
    {
        return $this->captchaType;
    }

    /**
     * @param int $captchaType
     * @author staitc7 <static7@qq.com>
     */
    public function setCaptchaType(int $captchaType)
    {
        $this->captchaType = $captchaType;
    }

    /**
     * @return int
     * @author staitc7 <static7@qq.com>
     */
    public function getDisturbLevel(): int
    {
        return $this->disturbLevel;
    }

    /**
     * @param int $disturbLevel
     * @author staitc7 <static7@qq.com>
     */
    public function setDisturbLevel(int $disturbLevel)
    {
        $this->disturbLevel = $disturbLevel;
    }

    /**
     * @return int
     * @author staitc7 <static7@qq.com>
     */
    public function getisHttps(): int
    {
        return $this->isHttps;
    }

    /**
     * @param int $isHttps
     * @author staitc7 <static7@qq.com>
     */
    public function setIsHttps(int $isHttps)
    {
        $this->isHttps = $isHttps;
    }

    /**
     * @return int
     * @author staitc7 <static7@qq.com>
     */
    public function getClientType(): int
    {
        return $this->clientType;
    }

    /**
     * @param int $clientType
     * @author staitc7 <static7@qq.com>
     */
    public function setClientType(int $clientType)
    {
        $this->clientType = $clientType;
    }

    /**
     * @return int
     * @author staitc7 <static7@qq.com>
     */
    public function getAccountType(): int
    {
        return $this->accountType;
    }

    /**
     * @param int $accountType
     * @author staitc7 <static7@qq.com>
     */
    public function setAccountType(int $accountType)
    {
        $this->accountType = $accountType;
    }

    /**
     * @return string
     * @author staitc7 <static7@qq.com>
     */
    public function getSecretId(): string
    {
        return $this->secretId;
    }

    /**
     * @param string $secretId
     * @author staitc7 <static7@qq.com>
     */
    public function setSecretId(string $secretId)
    {
        $this->secretId = $secretId;
    }

    /**
     * @return string
     * @author staitc7 <static7@qq.com>
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * @param string $secretKey
     * @author staitc7 <static7@qq.com>
     */
    public function setSecretKey(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @return array
     * @author staitc7 <static7@qq.com>
     */
    public function getOther(): array
    {
        return $this->other;
    }

    /**
     * @param array $other
     * @author staitc7 <static7@qq.com>
     */
    public function setOther(array $other)
    {
        $this->other = $other;
    }

    /**
     * @return string
     * @author staitc7 <static7@qq.com>
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @author staitc7 <static7@qq.com>
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     * @author staitc7 <static7@qq.com>
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @author staitc7 <static7@qq.com>
     */
    public function setRegion(string $region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     * @author staitc7 <static7@qq.com>
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     * @author staitc7 <static7@qq.com>
     */
    public function setError(string $error)
    {
        $this->error = $error;
    }

    /**
     * @return string
     * @author staitc7 <static7@qq.com>
     */
    public function getTicket(): string
    {
        return $this->ticket;
    }

    /**
     * @param string $ticket
     * @author staitc7 <static7@qq.com>
     */
    public function setTicket(string $ticket)
    {
        $this->ticket = $ticket;
    }

}