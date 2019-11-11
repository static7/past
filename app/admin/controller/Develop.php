<?php
/**
 * Description of Develop.php.
 * User: static7 <static7@qq.com>
 * Date: 2017/11/7 17:05
 */

namespace app\admin\controller;

use app\admin\traits\{
    Admin,Jump
};
use think\facade\Db;
use calm7\builder\Builder;

class Develop
{
    use Jump,Admin;


    /**
     * 表单生成
     * @return mixed
     * @throws \think\Exception
     * @author staitc7 <static7@qq.com>
     */
    public function formGeneration()
    {
        $Builder=new Builder();
        $data=$Builder->baseTemp('base');
        return $this->setView(['temp'=>$data],'form_generation');
    }

    /**
     * 表单模板生成
     * @author staitc7 <static7@qq.com>
     * @return mixed
     * @throws \think\Exception
     */
    public function formTemplate()
    {
        $data=$this->app->request->param();
        $Builder=new Builder();
        $info=$Builder->formTemplate($data);
        $info===true && $this->success('生成成功!');
    }

    /**
     * 数据列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function dataGeneration()
    {
        $name=$this->app->http->getName();
        $database=$this->app->env->get("database_{$name}.database",'');
        $table=Db::table("information_schema.tables")
            ->where('table_schema' ,'=', $database)
            ->field(['table_comment'=>'title','table_name'=>'name'])
            ->select();
        $Builder=new Builder();
        $data=$Builder->baseTemp('collection');
        return $this->setView(['temp'=>$data,'table'=>$table],'data_generation');
    }

    /**
     * 数据列表生成
     * @author staitc7 <static7@qq.com>
     * @return mixed
     * @throws \think\Exception
     */
    public function dataTemplate()
    {
        $data = $this->app->request->param();
        if (!isset($data['collection']) && empty($data['collection'])) {
            return $this->error('数据列表必须填写!');
        }
        $Builder=new Builder();
        $javascript=[
            'collection'=>'{@collection}',
            'container'=>'{@container}',
            'url'=>'{@url}',
            '{@javascript}'
        ];
        $info=$Builder->dataTemplate($data,'{@search}',$javascript);
        $info===true && $this->success('生成成功!');
    }

    /**
     * 数据表格生成
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    public function tableBuild()
    {
        $param=$this->app->request->param();
        if (empty($param['table']) === true){
            return $this->error('表不能为空');
        }
        $data=Db::table("information_schema.columns")
            ->where('table_schema' ,'=', $this->app->config->get('database.database','past'))
            ->where('table_name' ,'=', $param['table'] )
            ->field(['column_comment'=>'title','column_name'=>'name'])
            ->select();
        return $this->result($data->isEmpty() ? []:$data->toArray(),1,'生成完成','json');
    }


    /**
     * 接口调用
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function interface()
    {
        return $this->setView();
    }

    /**
     * 模拟调用接口
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function simulationTransfer()
    {
        $param = $this->app->request->only([
            'url'    => '',
            'method' => '',
            'header' => '',
            'body'   => '',
            'cookie' => '',
        ]);
        if (is_array($param['header']) === true) {
            $param['header'] = array_merge($param['header']);
            $header          = [];
            foreach ($param['header'] as $k => $v) {
                $header[ $k ] = $v['name'] . ":" . $v['value'];
            }
            $param['header'] = $header;
            unset($header);
        } else {
            $param['header'] = explode(PHP_EOL, $param['header']);
        }
        if (empty($param['body']) === false) {
            if (is_array($param['body']) === true) {
                $param['body'] = array_merge($param['body']);
                $body          = [];
                foreach ($param['body'] as $k => $v) {
                    $body[ $v['name'] ] = $v['value'];
                }
                $param['body'] = $body;
                unset($body);
            } else {
                $param['body'] = json_decode($param['body'], true);
            }
        }

        $data = $this->sendRequest($param['body'], $param['method'], $param['url'], $param['cookie'], $param['header']);
        return $this->result($data,0,'请求成功');
    }


    /**
     * 请求
     * @param array  $param
     * @param string $method
     * @param string $url
     * @param string $cookie
     * @param array  $header
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    public function sendRequest($param = [], $method = 'get', $url = null, $cookie = '', $header = [])
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header ?? []);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie ?? []);
        curl_setopt($ch, CURLOPT_USERAGENT, \think\facade\Request::header('user-agent'));
        $data = empty($param) === true ? '' : http_build_query($param);
        if (strtolower($method) == 'get') {
            curl_setopt($ch, CURLOPT_URL, $data ? $url . '?' . $data : $url);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $response = curl_exec($ch);
        $httpInfo = curl_getinfo($ch);
        if (curl_errno($ch)) {
            $error = sprintf("curl[%s] error[%s]", $url, curl_errno($ch) . ':' . curl_error($ch));
            curl_close($ch);
            throw new \Exception($error);
        }
        curl_close($ch);
        list($responseHeader, $responseBody) = explode("\r\n\r\n", $response, 2);
        $httpInfo['response_header']=$responseHeader;
        $httpInfo['response_body']=$responseBody;
        return $httpInfo;
    }

}