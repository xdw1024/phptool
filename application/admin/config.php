<?php
//配置文件
define('AUTH_CODE', 'fyjc');
//define('AUTH_CODE', 'WisdomPB');
define('SUCCESS_CODE', '200');
define('FAIL_CODE', '403');
/** 系统状态约定*/
define('TRUE_STATUS',1);

/** 普通文件上传路径文件夹*/
define('UPLOAD_FOLDER', '');

return [
    //模板布局
    'template'  =>  [
        'layout_on'     =>  true,
        'layout_name'   =>  'layout',
    ],

    'view_replace_str' => [
        '__static__'=>dirname($_SERVER['SCRIPT_NAME']).'/static',
        //或者
        //'__static__'=>think\Url::build('/').'public/static',
    ],
    // 后台json数据返回状态码
    'status_code' =>[
        'success' => SUCCESS_CODE, // 成功获取数据状态码
        'fail'    => FAIL_CODE,    // 失败获取数据状态码
//        'web_root'    => WEB_ROOT,    // 失败获取数据状态码
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'app\admin\driver\amazeuiPage',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],

    'session_setting' => [
        'admin_id'          => 'admin_id',  //管理员用户认证session标志
        'admin_name'        => 'admin_name',
        'admin_account'        => 'admin_account',
//        'role_prototype'    => 'role_prototype', //管理员用户角色等级标记（1：超级管理员， 2：普通管理员）
    ],

    // 退出url
    "logout_url"        => '/fyjc/public/index.php/admin/login/logout',
];