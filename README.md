# Static7 基础管理系统  


### ThinkPHP 6.0 正式版+Layui 2.5.5 

演示传送门: [https://demo.calm7.com](https://demo.calm7.com "点击一下就飞过去咯") 帐号密码均为:test001

`项目内的超级管理员 帐号:admin 密码:admin123`

### 特点  

1.使用仓储(Repository)模式    
2.使用trait,提高代码复用、减少复杂性      
3.封装使用率高的方法到trait类中     
4.命令一键生成仓储模式 类名 `php think depot [应用名/]类名`      
5.模板生成器,生成数据列表页面和表单页面 (高可扩展性和衍生性,开发效率可提升60%)   
6.Request类 加入参数名映射 控制器中方法代码有示例 源码在`app\Request`类中查看     
7.组权限控制,用户组权限控制 (展示菜单树节点)     
8.加入了内容模块和网站模块 (可用作CMS内容管理系统,其实模块可以删掉)


## 开发要求
* 请遵循thinkphp开发规范章节 传送门:[开发规范](https://www.kancloud.cn/manual/thinkphp6_0/1037482)
* 请使用强类型入参   
~~~php
    /**
     * 代码示例
     * @param string|null $a 字符串类型
     * @param array|null  $arr 数组类型
     * @param int|null    $int 整型
     * @param float|null  $float 浮点型
     * @return mixed
     * @author staitc7 <static7@qq.com>
     */
    public function demo(?string $a='demo',?array $arr=[],?int $int=0,?float $float=2.3)
    {
        //TODO 你的代码...
        return ;
    }
~~~    
  


运行环境(严格要求) 
===============

> ***static7的运行环境必须要求在 PHP7.1+ 及以上。***

> ***mysql5.7+ 需要关闭严格模式***

> ***强制通过虚拟域名访问***

 
window系统 WampServer Version 3.0.6 64bit 配置示例

配置如下通过虚拟域名访问

配置apache下的httpd-vhosts.conf文件 路径X:\wamp64\bin\apache\apache2.4.23\conf\extra

增加以下代码
~~~
<VirtualHost *:80>
    DocumentRoot "X:/xxx/tp6/public/"
    ServerName www.tp6.com
    ErrorLog "logs/dummy-host.example.com-error.log"
    CustomLog "logs/dummy-host.example.com-access.log" common
    <Directory "X:/xxx/tp6/public/">
    Options +Indexes +FollowSymLinks +MultiViews
    AllowOverride all
    Require all granted
</Directory>
</VirtualHost>
~~~
然后重启wamp

再打开自己本地的C:\Windows\System32\drivers\etchosts文件,配置如下：
~~~
127.0.0.1 www.tp6.com
~~~

在框架中的.env(.example.env重命名为.env)文件中 添加如下域名(顶级域名即可)

~~~dotenv
host=tp6.com
~~~

最后在配置文件`app/config/app.php`中,添加绑定域名
~~~php
    // 域名绑定（自动多应用模式有效）
    'domain_bind'           => [
        'www' => 'admin',
    ],
~~~

### mysql数据库    

data目录下的`past.sql`文件    
请自行导入数据库默认库名为`past`        
字符集为`utf8mb4`   

关闭mysql 的严格模式,并修改my.ini
~~~sql
sql-mode="STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"   
~~~
修改为
~~~sql
sql-mode="NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"tp6
~~~     


### 感谢开源贡献者

* thinkphp 官方
* layui 前端框架


### 个人说明
```
Static7基础管理系统为本人一个人开发,精力有限.可能更新比较慢. 如有BUG, 请在issues反馈.
```
提醒：和项目相关的问题最好在 issues 中反馈，这样方便其他有类似问题的人可以快速查找解决方法。
