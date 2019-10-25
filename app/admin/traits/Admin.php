<?php
/**
 * Description of Admin.php.
 * User: static7 <static7@qq.com>
 * Date: 2019/5/22 14:02
 */

namespace app\admin\traits;


use app\admin\model\Menu;
use app\facade\UserInfo;
use think\facade\View;

use app\middleware\admin\{
    Auth,LoginCheck
};
use think\{
    Container,Exception
};


trait Admin
{
    use Entrust;

    //定义控制器中间件
    protected $middleware = [
        LoginCheck::class,
        Auth::class,
    ];

    protected $view;

    /**
     * 初始化模板
     * @param array $param 替换字符参数
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    protected function initView(?array $param = [])
    {
        $viewReplace = $this->app->config->get('app.template',[]);
        View::config(array_merge($viewReplace, $param));
        return $this;
    }

    /**
     * 设置视图并输出
     * @param array  $value 赋值
     * @param string $template 模板名称
     * @param bool   $menus 菜单
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    protected function setView(?array $value = [], ?string $template = '', $menus = true)
    {
        //检测模板初始化
        $this->initView();
        //开启系统菜单
        $menus && $this->getMenu();
        return View::fetch($template ?: '',$value ?: []);
    }

    /**
     * 设置菜单
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    private function getMenu() : ?array
    {
        //ajax 跳过
        if ($this->app->request->isAjax()) {
            return null;
        }
        $userId=UserInfo::getUserId();
        $controller = $this->app->request->controller(true);//当前的控制器名
        //使用菜单缓存
        if ($this->app->config->get('app.menu_cache',false) === true){
            if ($this->app->cache->has("menu_{$controller}_{$userId}") === true){
                View::assign($this->app->cache->get("menu_{$controller}_{$userId}"));
                return null;
            }
        }
        $app = strtolower($this->app->http->getName());//当前的模块名
        $action = $this->app->request->action(true);//当前的操作名
        $where = [
            'pid'=> ['pid' ,'=', 0],
            'hide'=>['hide','=',0],
            'status'=>['status','=', 1],
            'module'=>['module','=', $app]
        ];
        (int)$this->app->config->get('admin_config.develop_mode') === 0 && $where['is_dev']=['is_dev','=',0];
        $Menu=new Menu();
        $main =$Menu->where(array_values($where))->field(['id','title','url'])->order(['sort'=>'asc'])->select();
        $mainId=0;
        $mainRule=$this->app->config->get('app.auth_rule.rule_main',2);
        foreach ($main as $k => $v) {
            $url=(string)strtolower($v['url']);
            $rule = $this->checkRule($url, $mainRule);
            // 判断主菜单权限
            if ($rule === false) {
                unset($main[ $k ]);continue;
            }
            if ($url === (string)strtolower("{$controller}/{$action}")){
                $main[$k]['class'] = 'layui-this';
                $mainId=(int)$v['id'];
            }
        }

        $where['pid']=['pid','>', 0];
        if ($mainId===0){
            $mainId= $Menu->where(array_values($where))->where('url' ,'=', "{$controller}/{$action}")->value('main_id');
            foreach ($main as $k=>&$v){
                (int)$v['id'] === $mainId && $v['class'] = 'layui-this';
            }
        }

        $where['main_id']=['main_id' ,'=', $mainId ?? 0];
        // 查找当前子菜单
        $subset=$Menu->where(array_values($where))->field(['id','title','pid','main_id','url','group'])->select();
        if ($subset->isEmpty() === true){
            View::assign(['mainMenu'=>$main->toArray(),'childMenu'=>null]);
            return null;
        }
        $subset=$this->toCheckUrl($subset->toArray());

        unset($where['pid'],$where['is_dev']);
        $where['main_id']=['main_id' ,'=',$mainId];
        $where['group']=['group','<>', ""];
        //获取分组信息
        $groups = $Menu->where(array_values($where))->distinct(true)->column("group");
        if (empty($groups)){
            View::assign(['mainMenu'=>$main->toArray(),'childMenu'=>null]);
            return null;
        }
        $child=list_to_tree($subset, 'id', 'pid', 'operater', $mainId);
        $subset=[];
        foreach ($groups as $key => $value) {
            foreach ($child as $k => $v) {
                (string)$value === (string)$v['group'] && $subset[ $value ][] = $v;
            }
        }
        unset($child);
        $menu=['mainMenu'=>$main->toArray(),'childMenu'=>$subset];
        $this->app->cache->tag(['admin_menu'])->set("menu_{$controller}_{$userId}",$menu,600);
        View::assign($menu);
        return null;
    }


    /**
     * 权限检测
     * @param string $rule 检测的规则
     * @param string $ruleType 规则类型
     * @param string $mode check模式
     * @return bool
     * @throws \Exception
     * @author 朱亚杰  <xcoolccgmail.com>
     */
    final private function checkRule(?string $rule, ?string $ruleType = null, $mode = 'node'): bool
    {
        //检测容器是否绑定
        if (Container::getInstance()->has('auth') === false) {
            throw new \Exception('权限类没有注入到容器');
        };
        //超级管理员直接返回true
        $userId = UserInfo::getUserId();
        if ($userId === (int)$this->app->config->get('app.user_administrator', 1)) {
            return true;
        }
        //当前的模块名
        $app = strtolower($this->app->http->getName());
        if ((bool)stripos($rule, $app) === false) {
            $rule = $app .'/'. $rule;
        }
        if (empty($ruleType) === true) {
            $type = $this->app->config->get('app.auth_rule', [1, 2]);
        } else {
            $type = [$ruleType];
        }
        unset($ruleType);
        return Container::getInstance()->make('auth')->check($rule, $userId, $type, $mode);
    }

    /**
     * 非超级管理员的权限检测
     * @param array $subsetUrl
     * @return mixed
     * @throws \Exception
     * @author staitc7 <static7@qq.com>
     */
    final private function toCheckUrl(array $subsetUrl = []): array
    {
        $type  = $this->app->config->get('app.auth_rule.rule_url', 1);
        $array = [];
        foreach ($subsetUrl as $key => $value) {
            if ($this->checkRule($value['url'],$type,'url') === true){
                $array[ $key ] = $value;
            }
        }
        unset($subsetUrl, $type);
        return $array;
    }
}