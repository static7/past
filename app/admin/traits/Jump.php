<?php
/**
 * Description of Jump.php.
 * User: static7 <static7@qq.com>
 * Date: 2019/5/22 11:51
 */

namespace app\admin\traits;


use think\exception\HttpResponseException;
use think\facade\{Request, Config, Route};
use think\Response;

trait Jump
{

    /**
     * 获取当前的response 输出类型
     * @return string
     * @author staitc7 <static7@qq.com>
     */
    protected function getResponseType()
    {
        return (Request::isJson() || Request::isAjax()) ? 'json' : 'view';
    }

    /**
     * 操作错误跳转
     * @param mixed       $msg 提示信息
     * @param string|null $url 跳转的URL地址
     * @param array       $data 返回的数据
     * @param int|null    $wait 跳转等待时间
     * @param array       $header 发送的Header信息
     * @return Response
     */
    protected function error(?string $msg = '', ?string $url = '', ?array $data = [], ?int $wait = 3, ?array $header = []): Response
    {
        if (is_null($url) === true) {
            $url = Request::isAjax() ? '' : 'javascript:history.back(-1);';
        } else if ($url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : Route::buildUrl($url);
        }

        $result = [
            'code' => 0,
            'msg'  => $msg,
            'data' => $data,
            'url'  => $url,
            'wait' => $wait,
        ];

        return $this->viewTemplate($result,$header);
    }

    /**
     * 操作成功跳转
     * @param mixed       $msg 提示信息
     * @param string|null $url 跳转的URL地址
     * @param mixed       $data 返回的数据
     * @param int|null    $wait 跳转等待时间
     * @param array       $header 发送的Header信息
     * @return Response
     */
    protected function success(?string $msg = '', ?string $url = null, ?array $data = [], ?int $wait = 3, ?array $header = []): Response
    {
        $referer = Request::header('referer');
        if (is_null($url) && $referer) {
            $url = $referer;
        } else if ($url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : Route::buildUrl($url);
        }

        $result = [
            'code' => 1,
            'msg'  => $msg,
            'data' => $data,
            'url'  => (string)$url,
            'wait' => $wait,
        ];

        return $this->viewTemplate($result,$header);
    }

    /**
     * 返回封装后的API数据到客户端
     * @param mixed   $data 要返回的数据
     * @param integer $code 返回的code
     * @param mixed   $msg 提示信息
     * @param string  $type 返回数据格式
     * @param array   $header 发送的Header信息
     * @return Response
     */
    protected function result(?array $data = [], ?int $code = 0, ?string $msg = '', ?string $type = 'json', ?array $header = []): Response
    {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data,
        ];

        $response = Response::create($result, $type)->header($header);

        throw new HttpResponseException($response);
    }


    /**
     * 返回渲染后的API模板到客户端
     * @param string  $string
     * @param integer $code 返回的code
     * @param mixed   $msg 提示信息
     * @param string  $type 返回数据格式
     * @param array   $header 发送的Header信息
     * @return Response
     */
    protected function template(?string $string = '', ?int $code = 0, ?string $msg = '', ?string $type = 'json', ?array $header = []): Response
    {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $string,
        ];

        $response = Response::create($result, $type ?: 'json')->header($header);

        throw new HttpResponseException($response);
    }


    /**
     * URL重定向
     * @access protected
     * @param string  $url 跳转的URL表达式
     * @param integer $code http code
     * @return void
     */
    protected function redirect(?string $url = '', ?int $code = 302)
    {
        $response = Response::create((string)Route::buildUrl($url)->domain(true), 'redirect', $code);

        throw new HttpResponseException($response);
    }

    /**
     * ajax返回格式
     * @param mixed   $data 返回的数据
     * @param integer $code 状态码
     * @param array   $header 头部
     * @param array   $options 参数
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    protected function json(?array $data = [], ?int $code = 200, array $header = [], array $options = [])
    {
        return Response::create($data, 'json', $code)->header($header)->options($options);
    }

    /**
     * layui 专用返回数据格式
     * @param array|null  $data 返回的数据
     * @param array|null  $extra 扩展数据
     * @param int         $code 状态码
     * @param null|string $msg 状态信息
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    protected function layuiJson(?array $data = null, int $code = 0, ?string $msg = '', ?array $extra = null)
    {
        $result = [
            'code'  => $code,
            'msg'   => $msg,
            'total' => 0,
            'data'  => [],
            'extra' => $extra
        ];
        if (empty($data) || !isset($data['total']) || empty($data['data'])) {
            $result['code'] = 1;
            $result['msg']  = '暂时没有数据';
        } else {
            $result['data']  = $data['data'];
            $result['total'] = $data['total'];
        }
        return $this->json($result);
    }

    /**
     * 视图跳转方法
     * @param array|null $data
     * @param array|null $header
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    protected function viewTemplate(?array $data = [], ?array $header = [])
    {
        $type = $this->getResponseType();
        if ($type == 'view') {
            $response = Response::create(Config::get('app.dispatch_success_tmpl'), $type)->assign($data);
        } else {
            $response = Response::create($data, $type);
        }
        $response->header($header);
        throw new HttpResponseException($response);
    }

}