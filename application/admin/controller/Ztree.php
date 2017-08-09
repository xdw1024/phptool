<?php
/**
 * Created by PhpStorm.
 * User: xdw
 * Date: 2017/7/15
 * Time: 9:42
 */

namespace app\admin\controller;

use think\Controller;

require ROOT_PATH . 'extend/PHPExcel/PHPExcel.php';

class Ztree extends Controller{

    /**
     * 任务管理
     * @author  xdw
     * @date  20170715
     */
    public function index()
    {
        $org = model('Org','logic');
        $tree = $org->getTree(3);
        $this->assign('orgTree', json_encode($tree));
        $this->assign('mission', NULL);
        return $this->fetch('index');
    }

    /**
     * ztree
     * @author  xdw
     * @date  20170807
     */
    public function ztreeTest()
    {
        $notes = [
            'name'=>'top',
            'children'=>[
                'name'=>'test1',
                'children'=>[
                    'name'=>'test1_1',
                    'name'=>'test1_2'
                ],
                'name'=>'test2',
                'children'=>[
                    'name'=>'test2_1',
                    'name'=>'test2_2'
                ]
            ]
        ];
        $this->assign('notes',json_encode($notes));
        return $this->fetch('ztree/demo');
    }


}