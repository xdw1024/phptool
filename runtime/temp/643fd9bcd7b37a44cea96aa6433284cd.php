<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"D:\phpstudy\WWW\dwtool\public/../application/admin\view\ztree\demo.html";i:1502089300;s:67:"D:\phpstudy\WWW\dwtool\public/../application/admin\view\layout.html";i:1502068137;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>中石化联量计酬系统</title>
    <meta name="description" content="fyjc">
    <meta name="keywords" content="index">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    
    
    <meta name="apple-mobile-web-app-title" content="fyjc" />
    <link rel="stylesheet" type="text/css" href="__static__/css/amazeui.min.css" />
    <link rel="stylesheet" type="text/css" href="__static__/css/amazeui.datatables.min.css" />
    <link rel="stylesheet" type="text/css" href="__static__/css/app.css" />
    <link rel="stylesheet" type="text/css" href="__static__/css/common.css" />
    <script type="text/javascript" src="__static__/js/echarts.min.js"></script>
    <script type="text/javascript" src="__static__/js/jquery.min.js"></script>
    <script type="text/javascript" src="__static__/js/jquery.form.js"></script>

</head>

<!-- 提示框-->
<div class="am-modal am-modal-alert" tabindex="-1" id="info_model">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">中石化联量计酬系统</div>
        <div class="am-modal-bd">
            Hello world！
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn">确定</span>
        </div>
    </div>
</div>
<!-- 提示框 end-->

<body data-type="widgets">
<script type="text/javascript" src="__static__/js/theme.js"></script>
<div class="am-g tpl-g">
    <!-- 头部 -->
    <header>
        <!-- logo -->
        <div class="am-fl tpl-header-logo">
            <a href="javascript:;"><img src="__static__/img/logo.png" alt=""></a>
        </div>
        <!-- 右侧内容 -->
        <div class="tpl-header-fluid">
            <!-- 侧边切换 -->
            <div class="am-fl tpl-header-switch-button am-icon-list">
                    <span>

                </span>
            </div>
            <!-- 搜索 -->
            <!--<div class="am-fl tpl-header-search">-->
                <!--<form class="tpl-header-search-form" action="javascript:;">-->
                    <!--<button class="tpl-header-search-btn am-icon-search"></button>-->
                    <!--<input class="tpl-header-search-box" type="text" placeholder="搜索内容...">-->
                <!--</form>-->
            <!--</div>-->
            <!-- 其它功能-->
            <div class="am-fr tpl-header-navbar">
                <ul>
                    <!-- 欢迎语 -->
                    <li class="am-text-sm tpl-header-navbar-welcome">
                        <a href="javascript:;">欢迎你, <span><?php echo zw_get_admin_user_name(); ?></span> </a>
                    </li>

                    <?php echo get_user_msg(); ?>


                    <!-- 退出 -->
                    <li class="am-text-sm">
                        <a href="<?php echo \think\Config::get('logout_url'); ?>">
                            <span class="am-icon-sign-out"></span> 退出
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </header>
    <!-- 风格切换 -->
    <div class="tpl-skiner">
        <div class="tpl-skiner-toggle am-icon-cog">
        </div>
        <div class="tpl-skiner-content">
            <div class="tpl-skiner-content-title">
                选择主题
            </div>
            <div class="tpl-skiner-content-bar">
                <span class="skiner-color skiner-white" data-color="theme-white"></span>
                <span class="skiner-color skiner-black" data-color="theme-black"></span>
            </div>
        </div>
    </div>
    <!-- 侧边导航栏 -->
    <div class="left-sidebar">
        <!-- 用户信息 -->
        <div class="tpl-sidebar-user-panel">
            <div class="tpl-user-panel-slide-toggleable">
                <div class="tpl-user-panel-profile-picture">
                    <img src="__static__/img/user04.png" alt="">
                </div>
                <span class="user-panel-logged-in-text">
              <i class="am-icon-circle-o am-text-success tpl-user-panel-status-icon"></i>
              <?php echo zw_get_admin_user_name(); ?>
          </span>
                <!--<a href="javascript:;" class="tpl-user-panel-action-link"> <span class="am-icon-pencil"></span> 账号设置</a>-->
            </div>
        </div>

        <!-- 菜单 -->
        <ul class="sidebar-nav">
            <?php echo get_role_menu(); ?>
        </ul>
    </div>


    <!-- 内容区域 -->
    <div class="tpl-content-wrapper" style="z-index: inherit">
            <!DOCTYPE html>
<script type="text/javascript" src="__static__/js/ztree/js/jquery.ztree.core.js"></script><script type="text/javascript" src="__static__/js/ztree/js/jquery.ztree.exedit.js"></script><link rel="stylesheet" type="text/css" href="__static__/js/ztree/css/metroStyle/metroStyle.css" />
<HTML>
<HEAD>
    <TITLE> ZTREE DEMO </TITLE>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <SCRIPT LANGUAGE="JavaScript">
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {};
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = [
            {name:"test1", children:[
                {name:"test1_1"}, {name:"test1_2"}]},
            {name:"test2", children:[
                {name:"test2_1"}, {name:"test2_2"}]}
        ];
        var zNodes = [
            {name:"top",open:true, children:[
                {name:"test1", children:[
                    {name:"test1_1"}, {name:"test1_2"}]},
                {name:"test2", children:[
                    {name:"test2_1"}, {name:"test2_2"}]},
                {name:"test3", children:[
                    {name:"test1_1"}, {name:"test1_2"}]},
                {name:"test4", children:[
                    {name:"test2_1"}, {name:"test2_2"}]}
            ]}
        ];
        $(document).ready(function(){
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            console.log('<?php echo $notes; ?>');
        });
    </SCRIPT>
</HEAD>
<BODY>
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
</BODY>
</HTML>
    </div>



</div>

<script type="text/javascript" src="__static__/js/amazeui.min.js"></script>
<script type="text/javascript" src="__static__/js/amazeui.datatables.min.js"></script>
<script type="text/javascript" src="__static__/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="__static__/js/app.js"></script>
<script>
    var SUCCESS_CODE = "<?php echo \think\Config::get('status_code')['success']; ?>";  // 成功返回值
    var FAIL_CODE    = "<?php echo \think\Config::get('status_code')['fail']; ?>";  // 失败返回值

    // 弹出提示消息，默认弹出成功标识
    function show_notice(massage, status, refresh){
        status = arguments[1] ? arguments[1] : 'success';
        //修改样式
        if(status === 'success')
        {
            $('.status_alert').removeClass('error').addClass('success');
            $('.status_alert_title .glyphicon').removeClass('glyphicon-exclamation-sign').addClass('glyphicon-ok-sign');
            $(".status_alert_title_text").text("");
            $('.status_alert .btn').hide()
        }
        else
        {
            $('.status_alert').removeClass('success').addClass('error');
            $('.status_alert_title .glyphicon').removeClass('glyphicon-ok-sign').addClass('glyphicon-exclamation-sign');
            $(".status_alert_title_text").text("");
            $('.status_alert .btn').show();
            $('.notice_bg').fadeIn(500);
        }
        $('.status_alert .status_alert_info').text(massage);

        $('.success').animate({top: '10px'}, 500);
        $('.error').animate({top: '30%'}, 500);

        $('.status_alert .btn').click(function(){
            $('.status_alert').animate({top:'-500px'},500);
            $('.notice_bg').fadeOut(500);
            if (refresh)
            {
                window.location.reload();
            }
        });

        $('.status_alert .close').click(function(){
            $('.status_alert').animate({top:'-500px'},500);
            $('.notice_bg').fadeOut(500);
        });

        setTimeout(function(){
            $('.success').animate({top:'-500px' },500);
        },3000)
    }
</script>

<script>
    var SUCCESS_CODE = "<?php echo \think\Config::get('status_code')['success']; ?>";  // 成功返回值
    var FAIL_CODE    = "<?php echo \think\Config::get('status_code')['fail']; ?>";  // 失败返回值

    //alert 模拟 提示框
    function alertControl(message,status){
        var alertM = $('#info_model');
        if(status == 'fail')
        {
            alertM.find('.am-modal-hd').html("操作失败");
        }
        else
        {
            alertM.find('.am-modal-hd').html("操作成功");
        }
        alertM.find('.am-modal-bd').html(message);
        alertM.modal();
    }
</script>
</body>

</html>