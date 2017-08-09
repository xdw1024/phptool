<?php
/**
 * Created by PhpStorm.
 * User: xdw
 * Date: 2017/07/15
 * Time: 9:48
 */

namespace app\admin\service;

use think\Model;
use think\Db;
use think\Request;
class Mission extends Model
{
    /**
     * 全部任务信息查询
     *
     * @author  xdw
     * @date  20170715
     *
     */
    public function getMission(){
        $role = Db::view('org_mission', '*')
            ->view('org_type','orgname','org_type.orgcode = org_mission.orgcode')
            ->order('org_mission.id', 'desc')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $role;
    }


    /**
     * 获取任务信息
     *
     * @author  xdw
     * @date  20170715
     *
     */
    public function getMissionInfo($mission_id){
        $role = Db::view('org_mission', '*')->where('id',$mission_id)->find();
        return $role;
    }

    /**
     * 新增任务
     *
     * @author  xdw
     * @date  20170715
     *
     */
    public function addMission($data)
    {
        $result = Db::name('org_mission')->insertGetId($data,false);
        if(!$result){
            $result = Db::name('org_mission')->where($data)->find();
            $result = $result['id'];
        }
        return $result;
    }

    /**
     * 编辑任务信息
     *
     * @author  xdw
     * @date  20170715
     *
     */
    public function editMission($mission_id,$data)
    {
        $result = Db::name('org_mission')->where(['id' => $mission_id])->update($data);
        return $result;
    }

    /**
     * 刪除角色
     *
     * @author  xdw
     * @date  20170715
     *
     */
    public function deleteMission($mission_id)
    {
        $result = Db::name('org_mission')->where(['id' => $mission_id])->delete();
        return $result;
    }

    /**
     * 搜索查看
     *
     * @author  xdw
     * @date  20170715
     *
     */
    public function searchMission($map)
    {
        $result = Db::view('org_mission', '*')
            ->view('org_type','orgname','org_type.orgcode=org_mission.orgcode')
            ->where($map)
            ->order('org_mission.id', 'desc')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $result;
    }

    /**
     * 获取任务状态信息
     *
     * @author  xdw
     * @date  20170715
     *
     */
    public function getStatusMission(){
        $role = Db::view('org_mission', '*')
            ->view('org_type','orgname','org_type.orgcode = org_mission.orgcode','left')
            ->view('jc_result','mission_percent','jc_result.orgcode = org_mission.orgcode','left')
            ->order('org_mission.id', 'desc')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $role;
    }


    /**
     * 根据org_id、orgcode 获取门店
     * @author cx
     * @date  20170718
     */
    public function getOrgcodeByIds($org_ids,$orgcode)
    {
        $result = Db::name('org_type')
            ->where('org_id','in',$org_ids)
            ->where('orgcode','like',$orgcode)
            ->where(['isenable'=>1])
            ->order(['orgcode'])
            ->select();
        return $result;
    }

    /**
     * 根据org_id获取门店
     * @author cx
     * @date  20170718
     */
    public function getOrgcodeBycode($orgcode)
    {
        $result = Db::name('org_type')
            ->where('orgcode','like',$orgcode.'%')
            ->where('type','NOT NULL')
            ->where(['isenable'=>1])
            ->order(['orgcode'])
            ->select();
        return $result;
    }

    /**
     * （记录）获取多条件查询的任务记录
     * @author xdw
     * @date  20170724
     */
    public function searchRecordDetail($orgcode,$month){
        $map = [];
        $month_sql = '';
        if($month){
            $month_sql = ' and org_mission.month = \''.$month.'\'';
        }
        if($orgcode){
            $map['org_type.orgcode'] = ['in',$orgcode];
        }
        $result = Db::view('org_type', 'orgcode,orgname')
            ->view('org_mission','id,month,mission_value','org_mission.orgcode=org_type.orgcode '.$month_sql ,'left')
            ->where($map)
            ->order('org_mission.id desc')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $result;
    }

    /**
     * （状态）获取多条件查询的任务记录
     * @author xdw
     * @date  20170724
     */
    public function searchStatusDetail($orgcode,$month_mission,$month_result){

        $month_mission_sql = '';
        if($month_mission){
            $month_mission_sql = ' and org_mission.month = \''.$month_mission.'\'';
        }
        $month_result_sql = '';
        if($month_result){
            $month_result_sql = ' and jc_result.month = \''.$month_result.'\'';
        }
        $map = [];
        if($orgcode){
            $map['org_type.orgcode'] = ['in',$orgcode];
        }
        $result = Db::view('org_type', 'orgcode,orgname')
            ->view('org_mission','month,mission_value','org_mission.orgcode=org_type.orgcode '.$month_mission_sql,'left')
            ->view('jc_result','mission_percent,sumyye','jc_result.orgcode = org_mission.orgcode '.$month_result_sql,'left')
            ->where($map)
            //cx 20170801，未导入任务数据另外搜索
            ->where('org_mission.mission_value','not null')
            //cx 20170801，未导入任务数据另外搜索
            ->order('jc_result.mission_percent NULLS FIRST ,jc_result.mission_percent asc ,org_mission.id desc')
            ->paginate(15, false, [
                'type'=> 'app\admin\driver\amazeuiPage',
                'var_page' => 'page',
                'query' => Request::instance()->param()
            ]);
        return $result;
    }

    /**
     * 未导入任务数据在此搜索
     * @author cx
     * @date  20170801
     */
    public function getUnsetMission($orgcode,$month_mission,$month_result){

        $month_mission_sql = '';
        if($month_mission){
            $month_mission_sql = ' and org_mission.month = \''.$month_mission.'\'';
        }
        $month_result_sql = '';
        if($month_result){
            $month_result_sql = ' and jc_result.month = \''.$month_result.'\'';
        }
        $map = [];
        if($orgcode){
            $map['org_type.orgcode'] = ['in',$orgcode];
        }
        $result = Db::view('org_type', 'orgcode,orgname')
            ->view('org_mission','month,mission_value','org_mission.orgcode=org_type.orgcode '.$month_mission_sql,'left')
            ->view('jc_result','mission_percent,sumyye','jc_result.orgcode = org_mission.orgcode '.$month_result_sql,'left')
            ->where($map)
            //cx 20170801，未导入任务数据在此搜索
            ->where('org_mission.mission_value','null')
            //cx 20170801，未导入任务数据在此搜索
            ->order('jc_result.mission_percent asc ,org_mission.id desc')
            ->select();
        return $result;
    }


}