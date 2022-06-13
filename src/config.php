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
