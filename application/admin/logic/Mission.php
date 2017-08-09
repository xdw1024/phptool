<?php
/**
 * Created by PhpStorm.
 * User: xdw
 * Date: 2017/07/15
 * Time: 9:45
 */

namespace app\admin\logic;

use think\Model;

class Mission extends Model
{
    /**
     * 获取任务信息
     *
     * @author  xdw
     * @date  20170715
     */
    public function getMission(){
        $org = model('Mission', 'service');
        $result = $org->getMission();
        return $result;
    }

    /**
     * 新增任务信息
     *
     * @author  xdw
     * @date  20170715
     */
    public function addMission($post){
        $model = model('Mission', 'service');
        $result = $model->addMission($post);
        return $result;
    }

    /**
     * 编辑任务信息
     *
     * @author  xdw
     * @date  20170715
     */
    public function editMission($mission_id,$post){
        $org = model('Mission', 'service');
        $result = $org->editMission($mission_id,$post);
        return $result;
    }

    /**
     * 任务信息搜索
     *
     * @author  xdw
     * @date  20170715
     */
    public function searchMission($search_info){
        $map['orgname|org_type.orgcode|month'] = ['like','%'.$search_info.'%'];
        $modle = model('Mission','service');
        $result = $modle->searchMission($map);
        return $result;
    }

    /**
     * 获取任务状态信息
     *
     * @author  xdw
     * @date  20170715
     */
    public function getStatusMission(){
        $org = model('Mission', 'service');
        $result = $org->getStatusMission();
        return $result;
    }

    /**
     * （记录）多条件查询任务记录
     * @author  xdw
     * @date  20170724
     */
    public function searchRecordDetail($post){
//        $month =str_replace('-','',$post['month']);
        $month = $post['month'];
        $service = model('Org', 'service');
        $orgcodes=array();
        if($post['org_id']){//组织编码
            //根据片区查询
            $nodes = $service->getAllLeafNode($post['org_id']);
            $org_ids = '';
            foreach ($nodes as $key => $value){
                $org_ids .= $value['id'].',';
            }
            $org_ids = substr($org_ids,0,strlen($org_ids)-1);
            //门店编码
            $orgcode = '%';
            if($post['orgcode']){
                $orgcode .= $post['orgcode'].'%';
            }
            if($org_ids){
                $service = model('Mission', 'service');
                $orgcodes = $service->getOrgcodeByIds($org_ids,$orgcode);
            }
        }
        else{
            if($post['orgcode']){
                //根据编码查询
                $service = model('mission', 'service');
                $orgcodes = $service->getOrgcodeBycode($post['orgcode']);
            }
        }
        //查询任务完成情况记录
        $orgcode = '';
        foreach ($orgcodes as $value){
            $orgcode .= $value['orgcode'].',';
        }
        $orgcode = substr($orgcode,0,strlen($orgcode)-1);
        $service = model('mission', 'service');
        $result =  $service->searchRecordDetail($orgcode,$month);
        //$result = changeNulltoString($result);
        return $result;
    }

    /**
     * （状态）多条件查询任务记录
     * @author  xdw
     * @date  20170724
     */
    public function searchStatusDetail($post){
        $month_mission = $post['month'];
        $month_result =str_replace('-','',$post['month']);
        $service = model('Org', 'service');
        $orgcodes = [];
        if($post['org_id']){//组织编码
            //根据片区查询
            $nodes = $service->getAllLeafNode($post['org_id']);
            $org_ids = '';
            foreach ($nodes as $key => $value){
                $org_ids .= $value['id'].',';
            }
            $org_ids = substr($org_ids,0,strlen($org_ids)-1);
            //门店编码
            $orgcode = '%';
            if($post['orgcode']){
                $orgcode .= $post['orgcode'].'%';
            }
            if($org_ids){
                $service = model('Mission', 'service');
                $orgcodes = $service->getOrgcodeByIds($org_ids,$orgcode);
            }
        }
        else{
            if($post['orgcode']){
                //根据编码查询
                $service = model('mission', 'service');
                $orgcodes = $service->getOrgcodeBycode($post['orgcode']);
            }
        }
        //查询任务完成情况记录
        $orgcode = '';
        foreach ($orgcodes as $value){
            $orgcode .= $value['orgcode'].',';
        }
        $orgcode = substr($orgcode,0,strlen($orgcode)-1);
        $service = model('mission', 'service');
        $result =  $service->searchStatusDetail($orgcode,$month_mission,$month_result);
        //$result = changeNulltoString($result);
        return $result;
    }

    /**
     * 未导入任务另外搜索
     * @author  cx
     * @date 20170801
     */
    public function getUnsetMission($post){
        $month_mission = $post['month'];
        $month_result =str_replace('-','',$post['month']);
        $service = model('Org', 'service');
        $orgcodes = [];
        if($post['org_id']){//组织编码
            //根据片区查询
            $nodes = $service->getAllLeafNode($post['org_id']);
            $org_ids = '';
            foreach ($nodes as $key => $value){
                $org_ids .= $value['id'].',';
            }
            $org_ids = substr($org_ids,0,strlen($org_ids)-1);
            //门店编码
            $orgcode = '%';
            if($post['orgcode']){
                $orgcode .= $post['orgcode'].'%';
            }
            if($org_ids){
                $service = model('Mission', 'service');
                $orgcodes = $service->getOrgcodeByIds($org_ids,$orgcode);
            }
        }
        else{
            if($post['orgcode']){
                //根据编码查询
                $service = model('mission', 'service');
                $orgcodes = $service->getOrgcodeBycode($post['orgcode']);
            }
        }
        //查询任务完成情况记录
        $orgcode = '';
        foreach ($orgcodes as $value){
            $orgcode .= $value['orgcode'].',';
        }
        $orgcode = substr($orgcode,0,strlen($orgcode)-1);
        $service = model('mission', 'service');
        //未导入任务数据在此搜索
        $result =  $service->getUnsetMission($orgcode,$month_mission,$month_result);
        return $result;
    }

}