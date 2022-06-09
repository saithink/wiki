<h1 align="center">wiki</h1>
<p align="center">
ThinkPHP6.0 通过注解实现接口文档自动生成功能
</p>

## 环境需求

>  - PHP >= 5.4

## 安装

使用 [composer](http://getcomposer.org/):

```shell
$ composer require saithink/wiki
```

## 项目地址
下载项目

```shell
git clone https://github.com/saithink/wiki.git
```
## 使用方式
### 自定义接口
在config目录下包含一个wiki.php,这个文件是项目的配置文件,可以在api数组里面增加额外的接口
```php
<?php
// +----------------------------------------------------------------------
// | Wiki设置
// +----------------------------------------------------------------------
return [
    // 应用名称
    'app_name' => '项目管理系统',
    // 应用版本
    'app_ver' => 'V1.0.0',
    // 扫描应用
    'scan_app' => 'index',
    // 自定义接口
    'public' => [
        'group' => '公共接口',
        'name' => '全局参数',
        'api' => [
            [
                'title' => '请求Token',
                'description' => '除了系统登录相关接口外，其余的接口必须携带token，才能进行交互，请求时在Header中携带参数 [Authori-zation]',
                'method' => 'GET',
                'rule' => '',
                'mock'  => [
                    'request'   => [
                        ['name' => 'Authori-zation', 'title' => '交互token', 'type' => 'string', 'require' =>'true', 'example' => '', 'description'=>'jwt的token字符串']
                    ],
                    'response' => [
                        ['name' => 'code', 'title' => '状态码', 'type' => 'string', 'example' => '200', 'description'=>'200表示成功，其余的表示失败'],
                        ['name' => 'msg', 'title' => '响应信息', 'type' => 'string', 'example' => 'ok', 'description'=>''],
                        ['name' => 'data', 'title' => '返回数据', 'type' => 'array', 'example' => '', 'description'=>'']
                    ],
                ]
            ],
        ],
    ],
];
```
### 预览效果
![https://raw.githubusercontent.com/xaboy/form-builder/2.0/images/components.png](https://raw.githubusercontent.com/xaboy/form-builder/master/images/components.png)

### 控制器代码 
```php
<?php
/* 注释路由 */
use saithink\wiki\annotations\{WikiMenu,WikiItem,WikiRequest,WikiResponse};

/**
 * @WikiMenu("首页", group="应用接口", description="数据展示相关接口")
 * Class Index
 * @package app\index\controller
 */
class Index
{
    /**
     * @WikiItem("登录", route="index/index", method="POST", description="系统登录接口，通过输入用户名、密码、验证码实现认证登录")
     * @WikiRequest("account", type="string", title="用户名", require="是", example="admin")
     * @WikiRequest("pwd", type="string", title="密码", require="是", example="123456")
     * @WikiRequest("imgcode", type="string", title="验证码", example="ay3n")
     * @WikiResponse("expires_time", type="string", title="过期时间", example="1638941516")
     * @WikiResponse("token", type="string", title="Token信息", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     * @WikiResponse("user_info{account}", type="string", title="账户", example="admin")
     * @WikiResponse("user_info{id}", type="int", title="账户id", example="1")
     * @WikiResponse("user_info{head_pic}", type="string", title="头像URL", example="")
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        return '您好！这是一个[index]示例应用';
    }
}

```

### 预览效果
![https://raw.githubusercontent.com/xaboy/form-builder/2.0/images/components.png](https://raw.githubusercontent.com/xaboy/form-builder/master/images/components.png)