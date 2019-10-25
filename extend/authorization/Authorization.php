<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: luofei614 <weibo.com/luofei614>　
// +----------------------------------------------------------------------

namespace authorization;

use think\Exception;
use think\facade\{Db, Config, Request, Session};

/**
 * 权限认证类
 * 功能特性：
 * 1，是对规则进行认证，不是对节点进行认证。用户可以把节点当作规则名称实现对节点进行认证。
 *      $auth=new Auth();  $auth->check('规则名称','用户id')
 * 2，可以同时对多条规则进行认证，并设置多条规则的关系（or或者and）
 *      $auth=new Auth();  $auth->check('规则1,规则2','用户id','and')
 *      第三个参数为and时表示，用户需要同时具有规则1和规则2的权限。 当第三个参数为or时，表示用户值需要具备其中一个条件即可。默认为or
 * 3，一个用户可以属于多个用户组(think_auth_group_access表 定义了用户所属用户组)。我们需要设置每个用户组拥有哪些规则(think_auth_group 定义了用户组权限)
 * 4，支持规则表达式。
 *      在think_auth_rule 表中定义一条规则时，如果type为1， condition字段就可以定义规则表达式。 如定义{score}>5  and {score}<100  表示用户的分数在5-100之间时这条规则才会通过。
 */
//数据库
/*
  -- ----------------------------
  -- think_auth_rule，规则表，
  -- id:主键，name：规则唯一标识, title：规则中文名称 status 状态：为1正常，为0禁用，condition：规则表达式，为空表示存在就验证，不为空表示按照条件验证
  -- ----------------------------
    DROP TABLE IF EXISTS `think_auth_rule`;
    CREATE TABLE `think_auth_rule` (
      `id` int(100) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
      `module` varchar(20) NOT NULL COMMENT '规则所属module',
      `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
      `name` char(200) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
      `title` char(50) NOT NULL DEFAULT '' COMMENT '规则中文描述',
      `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
      `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
      PRIMARY KEY (`id`),
      KEY `module` (`module`,`status`,`type`) USING BTREE
    ) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='规则表';
  -- ----------------------------
  -- think_auth_group 用户组表，
  -- id：主键， title:用户组中文名称， rules：用户组拥有的规则id， 多个规则","隔开，status 状态：为1正常，为0禁用
  -- ----------------------------
    DROP TABLE IF EXISTS `think_auth_group`;
      CREATE TABLE `think_auth_group` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
      `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
      `main_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主节点ID',
      `module` char(50) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
      `type` tinyint(10) NOT NULL DEFAULT '0' COMMENT '组类型',
      `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
      `description` char(255) NOT NULL DEFAULT '' COMMENT '描述信息',
      `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
      `rules` text COMMENT '用户组拥有的规则id，多个规则 , 隔开',
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`) USING BTREE
    ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='用户组表';
  -- ----------------------------
  -- think_auth_group_access 用户组明细表
  -- user_id:用户id，group_id：用户组id
  -- ----------------------------
    DROP TABLE IF EXISTS `tp5_auth_group_access`;
     CREATE TABLE `tp5_auth_group_access` (
      `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
      `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户组id',
      `main_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主节点ID',
      UNIQUE KEY `user_id_group_id` (`user_id`,`group_id`) USING BTREE,
      KEY `user_id` (`user_id`) USING BTREE,
      KEY `group_id` (`group_id`) USING BTREE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户组明细表';
 */

class Authorization
{

    //默认配置
    protected $config = [
        // 认证开关
        'auth_on'           => true,
        // 认证方式，1为实时认证；2为登录认证。
        'auth_type'         => 1,
        // 用户组数据表名
        'auth_group'        => 'auth_group',
        // 用户-用户组关系表
        'auth_group_access' => 'auth_group_access',
        // 权限规则表
        'auth_rule'         => 'auth_rule',
        // 用户信息表
        'auth_user'         => [
            //用户表
            'table'      => '',
            //用户主键
            'primaryKey' => ''
        ],
        //需要验证规则表达式的字段
        'auth_user_field'   => []
    ];

    public function __construct()
    {
        //可设置配置项 config.auth_config, 此配置项为数组。
        if (Config::has('app.auth_config', false)) {
            $this->config = array_merge($this->config, Config::get('app.auth_config', []));
        } else if (empty($this->config['auth_user']) === true) {
            throw new Exception('用户表不能为空');
        } else {
            throw new Exception('权限配置不能为空');
        }
    }

    /**
     * 检查单规则权限
     * @param string $name 需要验证的规则
     * @param int          $userId 认证用户的id
     * @param array        $type
     * @param string       $mode 执行check的模式 url包括参数 node只验证节点
     * @param string       $relation 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return boolean           通过验证返回true;失败返回false
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function check(?string $name = null, ?int $userId = null, ?array $type = null, ?string $mode = 'url', ?string $relation = 'or')
    {
        if ($this->config['auth_on'] === false) {
            return true;
        }
        if (empty($name) === true) {
            return false;
        }
        //获取用户需要验证的所有有效规则列表
        $authList = $this->getAuthList($userId, $type);
        if (empty($authList) ===true) {
            return false;
        }
        $name = strtolower($name);
        $list = []; //保存验证通过的规则名
        foreach ($authList as $k => $v) {
            switch ($mode){
                case 'node':
                    ((string)$v === (string)$name) && $list[] = $v;
                    break;
                case 'url':
                    $this->parameterAnalysis($v,$name) && $list[] = $v;
                    break;
                default:
                    break;
            }
        }
        if (empty($list) === true){
            return false;
        }
        return true;
    }

    /**
     * 获得权限列表
     * @param integer $user_id 用户id
     * @param array   $type 类型
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\db\exception\DbException
     */
    private function getAuthList(?int $user_id = 0, ?array $type = null)
    {
        static $_auth = null;
        if (isset($_auth[ $user_id ])) {
            return $_auth[ $user_id ];
        }
        $typeString = implode('_', $type);
        if ($this->config['auth_type'] == 2 && Session::has("_auth_{$user_id}_{$typeString}")) {
            $authRuleList      = Session::get("_auth_{$user_id}_{$typeString}");
            $_auth[ $user_id ] = $authRuleList[ $user_id . '_' . $typeString ];
            unset($authRuleList);
            return $_auth[ $user_id ];
        }
        //读取用户所属用户组
        $groups = $this->getGroups($user_id);
        if (empty($groups) === true) {
            return false;
        }
        $ruleIds = []; //保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $k => $v) {
            $array   = explode(',', trim($v, ','));
            $ruleIds = array_merge($ruleIds, array_filter($array));
            unset($array);
        }
        $ruleIds = $this->arraySole($ruleIds);
        if (empty($ruleIds) === true) {
            return false;
        }
        $rules        = $this->getAuthRule($user_id, $ruleIds);
        $authRuleList = [];
        foreach ($rules as $k => $v) {
            //根据condition进行验证
            if (empty($v['condition']) === true) {
                $authRuleList[] = strtolower($v['name']); //只要存在就记录
                continue;
            }
            //获取用户信息,一维数组
            $user = $this->getUserInfo($user_id);
            if (empty($user) === true) {
                continue;
            }
            $command   = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $v['condition']);
            $condition = null;
            eval('$condition=(' . $command . ');');
            $condition === true && $authRuleLis[] = strtolower($v['name']);
        }
        $authList[ $user_id . '_' . $typeString ] = $authRuleList;
        //规则列表结果保存到session
        (int)$this->config['auth_type'] === 2 && Session::set("_auth_{$user_id}_{$typeString}", $authList);
        $_auth[ $user_id ] = $authRuleList;
        unset($authList, $user, $ruleIds, $authRuleList);
        return $_auth[ $user_id ];
    }

    /**
     * 根据用户id获取用户组,返回值为数组
     * @param int $userId 用户id
     * @return array       用户所属的用户组 [
     *     ['user_id'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     *     ...)
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\db\exception\DbException
     */
    public function getGroups(?int $userId = null)
    {
        static $_group = null;
        if (isset($_group[ $userId ])) {
            return $_group[ $userId ];
        }
        $authGroupAccess = $this->config['auth_group_access'];
        $authGroup       = $this->config['auth_group'];
        $data       = Db::view($authGroupAccess, 'user_id,group_id')
            ->view($authGroup, 'title,rules', "{$authGroupAccess}.group_id={$authGroup}.id")
            ->where('user_id', '=', $userId)
            ->where('status', '=', 1)
            ->select();
        if ($data->isEmpty() === true) {
            return null;
        }
        $_group[ $userId ] = $data->column('rules');
        unset($data);
        return $_group[ $userId ];
    }

    /**
     * 读取用户组所有权限规则
     * @param int|null   $userId
     * @param array|null $ruleIds
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author staitc7 <static7@qq.com>
     */
    private function getAuthRule(?int $userId = null, ?array $ruleIds = null)
    {
        static $_rule = null;
        if (isset($_rule[ $userId ])) {
            return $_rule[ $userId ];
        }
        $rules = Db::name($this->config['auth_rule'])
            ->where('menu_id', 'in', $ruleIds)
            ->where('status', '=', 1)
            ->field(['id', 'name', 'menu_id', 'condition',])
            ->select();
        if ($rules->isEmpty() === true) {
            return null;
        }
        $_rule[ $userId ] = $rules->toArray();
        unset($rules);
        return $_rule[ $userId ];
    }

    /**
     * 获得用户资料,根据自己的情况读取数据库
     * @param int|null $userId 用户ID
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\db\exception\DbException
     */
    private function getUserInfo(?int $userId = null)
    {
        static $_info = null;
        if (isset($_info[ $userId ])) {
            return $_info[ $userId ];
        }
        $userInfo = Db::name($this->config['auth_user']['table'])
            ->where($this->config['auth_user']['primaryKey'], '=', $userId)
            ->field($this->config['auth_user_field'] ?? null)
            ->find();
        if (empty($userInfo) === true) {
            return null;
        }
        $_info[ $userId ] = $userInfo;
        unset($userInfo);
        return $_info[ $userId ];
    }

    /**
     * 分析参数
     * @param string|null $ruleUrl 规则url
     * @param array|null  $name 被检测的规则
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function parameterAnalysis(?string $ruleUrl=null,?string $name=null)
    {
        if (empty($ruleUrl) === true || empty($name) === true){
            return false;
        }
        $request = unserialize(strtolower(serialize(Request::param())));
        $query = preg_replace('/^.+\?/U', '', $ruleUrl);
        if ((string)$query === $ruleUrl){
            return ($ruleUrl===$name) ? true :false;
        };
        parse_str($query, $param); //解析规则中的param
        try {
            $intersect = array_intersect_assoc($request, $param);
        } catch (\Exception $e) {
            exit('非法参数,错误信息:' . $e);
        }
        $rule = preg_replace('/\?.*$/U', '', $ruleUrl);
        if (($rule===$name) && $intersect == $param) {  //如果节点相符且url参数满足
            return true;
        }
        return false;
    }

    /**
     * 提高效率的的函数 in_array() 函数
     * @param int|string $item 值
     * @param array      $array 数组
     * @return bool
     */
    /*private function inTheArray($item = null, ?array $array = [])
    {
        $flipArray = array_flip($array);
        return isset($flipArray[ $item ]);
    }*/

    /**
     * 提高效率的的函数 array_unique
     * @param array $array
     * @return array|null
     */
    private function arraySole(array $array = [])
    {
        return array_values(array_flip(array_flip($array)));;
    }


    /**
     * 批量检查单规则权限
     * @param string|array $name 需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param int          $userId 认证用户的id
     * @param array        $type
     * @param string       $mode 执行check的模式 url包括参数 node只验证节点
     * @return boolean           通过验证返回true;失败返回false
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function batchCheck(?array $name = null, ?int $userId = null, ?array $type = null, ?string $mode = 'url')
    {
        if ($this->config['auth_on'] === false) {
            return true;
        }
        if (empty($name) === true) {
            return false;
        }
        //获取用户需要验证的所有有效规则列表
        $authList = $this->getAuthList($userId, $type);
        if (empty($authList) ===true) {
            return false;
        }
        $name = array_map('strtolower', $name);
        $list = []; //保存验证通过的规则名
        foreach ($authList as $k => $v) {
            switch ($mode){
                case 'node':
                    ((string)$v === (string)$name) && $list[] = $v;
                    break;
                case 'url':
                    $this->parameterAnalysis($v,$name) && $list[] = $v;
                    break;
                default:
                    break;
            }
        }
        return $list;
    }
}
