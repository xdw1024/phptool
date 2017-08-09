<?php

use \think\Session;
use \think\Cookie;
use think\Config;
use \think\Db;
use think\Cache;

if (!function_exists('zw_build_url'))
{
    /**
     * 功能：生成url
     * 作者：zzwu
     * 时间：20170413
     * @param string        $url 路由地址
     * @param string|array  $vars 变量
     * @param bool|string   $suffix 生成的URL后缀
     * @param bool|string   $domain 域名
     * @return string
     */
    function zw_build_url($url = '', $vars = '', $suffix = false, $domain = false)
    {
        return url($url, $vars, $suffix, $domain);
    }
}

if (!function_exists('zw_sprint_result'))
{
    /**
     * 作者：zzwu
     * 功能：格式化返回数据
     * @param $data string | array 格式化的数据
     * @param $message string 需要返回的提示信息
     * @param $status  string  需要返回的状态
     * @param $type string 格式化数据类型，默认json
     * @return  string 返回序列化的字符串
     */
    function zw_sprint_result($message, $data = null, $status = SUCCESS_CODE, $type = 'json')
    {
        $result = [
            'message' => $message,
            'data'    => $data
        ];
        switch ($status)
        {
            case SUCCESS_CODE:
                $result['status']  = SUCCESS_CODE;
                break;
            default:
                $result['status']  = FAIL_CODE;
                break;
        }

        switch ($type)
        {
            case 'json':
                $result = json($result);
                break;
            case 'xml':
                $result = xml($result);
                break;
            default:
                break;
        }

        return $result;
    }
}

if(!function_exists('zw_get_user_role_prototype'))
{
    /**
     * 作者   ：zzwu
     * 获取当前登录的管理员帐号的角色等级（true：超级管理员， false：普通管理员）
     * @return bool 返回当前管理员等级（true：超级管理员， false：普通管理员）
     **/
    function zw_get_user_role_prototype()
    {
//        return (Session::has(Config::get('session_setting')['role_prototype'])) ? Session::get(Config::get('session_setting')['role_prototype']) : false;
        if ((Session::has(Config::get('session_setting')['role_prototype'])))
        {
            return (Session::get(Config::get('session_setting')['role_prototype']) == 1) ? true : false;
        }
        return false;
    }
}

if (!function_exists('zw_set_password'))
{
    /**
     * 密码加密方法
     * @param string $password 要加密的字符串
     * @param string $auth_code 干扰字符串
     * @return string
     */
    function zw_set_password($password, $auth_code = AUTH_CODE){
        if (PHP_VERSION_ID >= 50500) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            if ($hash === false) {
                $hash = hash('sha512', hash('sha512', hash('sha512', $password, $auth_code), $auth_code), $auth_code);
                $hash = base64_encode($hash);
            }
        } else {
            $hash = hash('sha512', hash('sha512', hash('sha512', $password, $auth_code), $auth_code), $auth_code);
            $hash = base64_encode($hash);
        }
        return $hash;
    }
}

if (!function_exists('zw_get_admin_user_name'))
{
    /**
     * 获取当前登陆用户id 未完成 20170329
     * @return string | boolean 获取成功，返回管理员用户名（即id） 失败false
     */
    function zw_get_admin_user_name()
    {
        return Session::has(Config::get('session_setting')['admin_name']) ? Session::get(Config::get('session_setting')['admin_name']) : '';
    }
}

if (!function_exists('lw_is_login'))
{

    /**
     * 作者   ：nlw
     * 检查用户是否已登录
     * @return boolean
     */
    function lw_is_login()
    {
        return Session::has(Config::get('session_setting')['admin_id']);
    }
}

if (!function_exists('zw_compare_password'))
{
    /**
     * 密码比较方法,所有涉及密码比较的地方都用这个方法
     * @param string $password 要比较的密码
     * @param string $password_in_db 数据库保存的已经加密过的密码
     * @return boolean 密码相同，返回true
     */
    function zw_compare_password($password, $password_in_db){
        if (PHP_VERSION_ID >= 50500) {
            if (password_verify($password, $password_in_db)) {
                return true;
            }
        }
        return zw_set_password($password) == $password_in_db;
    }
}

if (!function_exists('zw_clear_session_in_logout'))
{
    /**
     * 用户退出成功，清除Session和Cookie
     * @return boolean 清除成功，返回true
     */
    function zw_clear_session_in_logout()
    {
        Session::clear();
        Cookie::clear();
        return true;
    }
}

if (!function_exists('zw_get_admin_user_name'))
{
    /**
     * 获取当前登陆用户id 未完成 20170329
     * @return string | boolean 获取成功，返回管理员用户名（即id） 失败false
     */
    function zw_get_admin_user_name()
    {
        return Session::has(Config::get('session_setting')['admin_name']) ? Session::get(Config::get('session_setting')['admin_name']) : '';
    }
}

if (!function_exists('zw_set_session_in_login'))
{
    /**
     * 管理员登陆成功，设置session
     * @param array $user 要设置session的用户
     * @return boolean 设置成功，返回true
     */
    function zw_set_session_in_login($user){
        if (is_array($user) && (count($user) != 0))
        {
            //存session
            if (!empty($user['id']))
            {
                Session::set(Config::get('session_setting')['admin_id'],$user['id']);
                Session::set(Config::get('session_setting')['admin_name'],$user['user_name']);
            }
            if (!empty($user['account']))
            {
                $user['account'] = empty($user['account']) ? $user['account'] : $user['account'];
                Session::set(Config::get('session_setting')['admin_account'],$user['account']);
                Cookie::set(Config::get('session_setting')['admin_account'], $user['account'], 3600 * 24 * 7);
            }
            return true;
        }
        return false;
    }
}

if (!function_exists('sy_get_user_org_id'))
{
    /**
     * 获取当前登陆用户组织ID未完成 20170329
     * @return string | boolean 获取成功org_id 失败false
     */
    function sy_get_user_org_id($user_id)
    {
        if (!isset($user_id)) return '';
        $org_result = Db::name('org_user')->where('user_id',$user_id)->find();
        return (is_array($org_result) && count($org_result) != 0) ? $org_result['org_id'] : '';
    }
}

if (!function_exists('zw_get_admin_user_name'))
{
    /**
     * 获取当前登陆用户名 未完成 20170329
     * @return string | boolean 获取成功，返回管理员用户名（即id） 失败false
     */
    function zw_get_admin_user_name()
    {
        return Session::has(Config::get('session_setting')['admin_name']) ? Session::get(Config::get('session_setting')['admin_name']) : '';
    }
}

if (!function_exists('zw_get_admin_user_id'))
{
    /**
     * 获取当前登陆用户id 未完成 20170329
     * @return string | boolean 获取成功，返回管理员用户id 失败false
     */
    function zw_get_admin_user_id()
    {
        return Session::has(Config::get('session_setting')['admin_id']) ? Session::get(Config::get('session_setting')['admin_id']) : '';
    }
}

if (!function_exists('zw_get_admin_menu'))
{
    /**
     * 功能：生成后台菜单
     * 作者：zzwu
     * 时间：20170413
     * @return string
     */
    function zw_get_admin_menu()
    {
        $html = '';
        $result = Db::view('menu', '*')
            ->view('menu_role', ['menu_id' => 'm_id'], 'menu_role.menu_id=menu.id')
            ->view('role_user', 'user_id', 'role_user.role_id=menu_role.role_id')
            ->where(['role_user.user_id' => lw_get_current_admin_id(), 'menu.status' => 1])
            ->order('menu.sort', 'desc')
            ->select();
        if (is_array($result) && count($result) != 0)
        {
            foreach ($result as $item) {
                $html .= '<li class="sidebar-nav-link"><a href="' . zw_build_url($item['controller'] . '/' . $item['method']) . '"><i class="am-icon-home sidebar-nav-link-logo ' . $item['icon'] . '"></i>' . $item['menu_name'] . '</a></li>';
            }
        }
        return $html;
    }
}

if(!function_exists('lw_get_current_admin_id'))
{
    /**
     * 作者   ：nlw
     * 获取当前登录的管理员帐号id
     * @return int | false  如果当前有管理员帐号处于登录状态，返回当前管理员帐号id，否则返回false
     */
    function lw_get_current_admin_id()
    {
        return (Session::has(Config::get('session_setting')['admin_id'])) ? (int)Session::get(Config::get('session_setting')['admin_id']) : false;
    }
}

if (!function_exists('get_role_menu'))
{
    /**
     * 功能：生成后台菜单
     * 作者：zzwu
     * 时间：20170413
     * @return string
     */
    function get_role_menu()
    {
        $html = '';
        //$a= lw_get_current_admin_id();
        $result = Db::view('menu', '*')
            ->view('menu_role', ['menu_id' => 'm_id'], 'menu_role.menu_id=menu.id')
            ->view('role_user', 'user_id', 'role_user.role_id=menu_role.role_id')
            ->where(['role_user.user_id' => lw_get_current_admin_id(), 'menu.status' => 1,'menu.view' => 1])
            ->order(['menu.level','menu.sort'=>'desc'])
            ->select();
        if (is_array($result) && count($result) != 0)
        {
            foreach ($result as $menu) {
                if($menu['level']==1){
                    if($menu['controller']){
                        //无二级菜单的情况
                        $html .= '<li class="sidebar-nav-link"><a href="' . zw_build_url($menu['controller'] . '/' . $menu['method']) . '"><i class="am-icon-home sidebar-nav-link-logo ' . $menu['icon'] . '"></i>' . $menu['menu_name'] . '</a></li>';
                    }
                    else{
                        //有二级菜单的情况
                        $html .='<li class="sidebar-nav-link">
                                    <a href="javascript:;" class="sidebar-nav-sub-title">
                                        <i class="am-icon-table sidebar-nav-link-logo"></i> '.$menu['menu_name'].'
                                        <span class="am-icon-chevron-down am-fr am-margin-right-sm sidebar-nav-sub-ico"></span>
                                    </a>
                                    <ul class="sidebar-nav sidebar-nav-sub">';
                        foreach ($result as $menu2){
                            if($menu2['parent_id']==$menu['id']){
                                $html .= '<li class="sidebar-nav-link"><a href="' . zw_build_url($menu2['controller'] . '/' . $menu2['method']) . '"><i class="am-icon-home sidebar-nav-link-logo ' . $menu2['icon'] . '"></i>' . $menu2['menu_name'] . '</a></li>';
                            }
                        }
                        $html .='</ul></li>';
                    }
                }
                else{
                    return $html;
                }
            }
        }
        return $html;
    }
}


if (!function_exists('dw_getOracleConnection')) {
    /**
     * 连接oracle数据库
     * 作者：xdw
     * 时间：20170715
     */
    function dw_getOracleConnection()
    {
        $config = array (
            'dbconfig' =>
                array (
                    'db_host_name' => '10.192.1.154/gxcvsdb',
                    'db_user_name' => 'read_bldtc',
                    'db_password' => 'oracle',
                ),
        );
        //取数据库参数
        $db_host_name=$config['dbconfig']['db_host_name']; //'localhost/ORCL''
        $db_user_name=$config['dbconfig']['db_user_name'];//'asgr'
        $db_pwd=$config['dbconfig']['db_password']; //'asgr'
        //连接Oracle
        $conn = oci_connect($db_user_name,$db_pwd,$db_host_name,'zhs16gbk');//oci_connect('asgr','asgr','localhost/ORCL');
        return (!$conn) ? false : $conn;
    }
}

if (!function_exists('dw_isTableExist')) {
    /**
     * 判读数据表是否存在
     * 作者：xdw
     * 时间：20170715
     */
    function dw_isTableExist($table_name)
    {
        $sql = "select count(*) from information_schema.tables WHERE table_schema='public' and  table_name ='".$table_name."'";
        $result = Db::query($sql);
        return $result[0]['count'];
    }
}

if (!function_exists('dw_createTable')) {
    /**
     * 创建数据表
     * 作者：xdw
     * 时间：20170715
     * @param $table_name 表名称
     * @param array $field_array 要指定的字段类型
     */
    function dw_createTable($table_name,$field_array=array()){
        $table_prefix = 'fyjc_';
        $conn = dw_getOracleConnection();

        if($conn) {
            //获取表结构，创建数据表
            $select = 'select COLUMN_NAME,DATA_TYPE,DATA_LENGTH
                        from dba_tab_columns
                        where table_name like upper(\''.$table_name .'\')
                        order by COLUMN_NAME
                        ';
            $select = iconv("utf-8", "gb2312", $select);
            $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
            $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
            if ($row_count) { //没有记录
                $fields_sql = '';
                while ($row = oci_fetch_array($result_rows, OCI_RETURN_NULLS)) {
                    if(array_key_exists(strtolower($row['COLUMN_NAME']),$field_array)){//特殊字段，可以指定类型
                        $fields_sql .= strtolower($row['COLUMN_NAME']) . ' '.$field_array[strtolower($row['COLUMN_NAME'])].', ';
                    }else{
                        $fields_sql .= strtolower($row['COLUMN_NAME']) . ' varchar, ';
                    }
                }
                //删除原数据表
                $result = Db::query( "DROP TABLE IF EXISTS  ".$table_prefix.$table_name );
                $fields_sql = rtrim($fields_sql,', ');
                $table_sql = "CREATE TABLE  ".$table_prefix.$table_name." ( ".$fields_sql." )";
                //echo $table_sql;
                $result = Db::query($table_sql);
            }
            else{
                echo '本月数据表生成失败';
            }
        }
        return;
    }
}

if (!function_exists('dw_cloneTable')) {
    /**
     * 克隆数据表
     * 作者：xdw
     * 时间：20170715
     * @param $new_table_name 新表名
     * @param $old_table_name 模板表名
     */
    function dw_cloneTable($new_table_name,$old_table_name){
        $sql = 'CREATE TABLE '.$new_table_name.' AS (SELECT * FROM '.$old_table_name.')';
        $result = Db::execute($sql);
        return $result;
    }
}

if (!function_exists('dw_synTableData')) {
    /**
     * 同步数据库数据
     * 作者：xdw
     * 时间：20170715
     */
    function dw_synTableData($orc_table,$pg_table)
    {
        $table_prefix = 'fyjc_';
        $conn = dw_getOracleConnection();

        //获取表结构，创建数据表
        //$has_table = dw_isTableExist('fyjc_'.$pg_table);
        dw_createTable($pg_table);

        //获取数据
        $select =  "SELECT count(*) as count FROM " .$orc_table ;
        $select = iconv("utf-8", "gb2312", $select);
        $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
        $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
        $count = 0;
        while ($row = oci_fetch_assoc($result_rows)) {
            foreach ($row as $key => $value) {
                $count = $value;
            }
        }

        if($count > 0) {
            $index = 0;
            $add = 1000;
            while($index <= $count) {
                $select = "select * from
                    ( SELECT t.*,rownum as my_rm FROM  " . $orc_table . " t  where  ROWNUM < ".($index + $add) ." ) temp
                    where temp.my_rm >= ".$index ;
                $select = iconv("utf-8", "gb2312", $select);
                $result_rows = oci_parse($conn, $select); // 配置SQL语句，执行SQL
                $row_count = oci_execute($result_rows, OCI_DEFAULT); // 行数  OCI_DEFAULT表示不要自动commit
                if (!$row_count) { //没有行
                    $e = oci_error($result_rows);
                    echo htmlentities($e['message']);
                }
                set_time_limit(0);
                //数据格式转换，转存
                $records = array();
                $row = oci_fetch_all($result_rows, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                if ($row) {
                    $record = array();
                    foreach($res as $item) {
                        foreach ($item as $key => $value) {
                            $value = mb_convert_encoding($value, 'utf-8', 'gb2312');
                            if (strtolower($key) == 'my_rm') {//去掉分页查询的编号字段
                                continue;
                            }
//                            switch(strtolower($key)){
//                                case 'date': $value = strtotime($value);break;
//                            }
                            $record[strtolower($key)] = $value;
                        }
                        $records[] = $record;
                    }
                }
                $index += $add;
                $result = Db::name($pg_table)->insertAll($records);
            }
        }
    }
}


if (!function_exists('changeNulltoString')) {
    function changeNulltoString($result)
    {
        foreach ($result as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if ($value2 == null) {
                    $result[$key][$key2] = '';
                }
            }
        }
        return $result;
    }
}


if (!function_exists('get_user_msg'))
{
    /**
     * 功能：获取消息
     * 作者：cx
     * 时间：201700804
     * @return string
     */
    function get_user_msg()
    {
        $html = '';
        $user_id = lw_get_current_admin_id();
        $result = Db::view('user_msg', '*')
            ->where('user_id' , $user_id)
            ->where('read_status' , 1)
            ->order('msg_time','desc')
            ->select();
        if (is_array($result) && count($result) != 0)
        {
           $count = count($result);
            $html .=' <li class="am-dropdown" data-am-dropdown>
                        <a href="javascript:;" class="am-dropdown-toggle" data-am-dropdown-toggle>
                            <i class="am-icon-bell"></i>
                            <span class="am-badge am-badge-warning am-round item-feed-badge">'.$count.'</span>
                        </a>
                         <!-- 弹出列表 -->
                        <ul class="am-dropdown-content tpl-dropdown-content">';
            foreach($result as $value){
                $html .= '<li class="tpl-dropdown-menu-notifications">
                                <a href="'.zw_build_url('Newplu/redirectUrl').'?msg_id='.$value['id'].'&link_url='.$value['link_url'].'" class="tpl-dropdown-menu-notifications-item am-cf">
                                    <div class="tpl-dropdown-menu-notifications-title">
                                        <i class="am-icon-star"></i>
                                        <span>'.$value['msg_content'].'</span>
                                    </div>
                                    <div class="tpl-dropdown-menu-notifications-time">
                                        '.date("Y-m-d",$value['msg_time']).'
                                    </div>
                                </a>
                            </li>';
            }
            $html .= ' <li class="tpl-dropdown-menu-notifications">
                            </li></ul></li>';
        }
        return $html;
    }
}