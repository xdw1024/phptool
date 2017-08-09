<?php
/**
 * Created by PhpStorm.
 * User: xdw
 * Date: 2017/7/15
 * Time: 9:42
 */

namespace app\admin\controller;

use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\Exception;

require ROOT_PATH . 'extend/PHPExcel/PHPExcel.php';

class Mission extends Adminbase{
    /**
     * 任务管理
     *
     * @author  xdw
     * @date  20170715
     */
    public function index()
    {
        $org = model('Org','logic');
        $tree = $org->getTree(3);
        $this->assign('orgTree', json_encode($tree));
        $this->assign('mission', NULL);
//        $model = model('Mission','logic');
//        $mission = $model->getMission();
//        $this->assign('mission', $mission);
//        if (Request::instance()->isAjax())
//        {
//            $page = $mission->render();
//            $data = $mission->all();
//            return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page]);
//        }
        return $this->fetch('index');
    }

    /**
     * 新增任务
     *
     * @author  xdw
     * @date  20170715
     */
    public function addMission()
    {
        //获取菜单等级为1的信息
        $model = model('Orgtype','service');
        $org_data = $model->getOrgCode();
        $this->assign('org_data', $org_data);
        return $this->fetch();
    }

    /**
     * 编辑任务
     *
     * @author  xwd
     * @date  20170715
     */
    public function editMission()
    {
        if (!input('id')){
            $this->error('参数错误');
        }
        //获取门店组织
        $model = model('orgtype','service');
        $org_data = $model->getOrgCode();
        $this->assign('org_data',$org_data);
        //获取任务信息
        $model = model('Mission','service');
        $mission_id = input('id');
        $mission_data = $model->getMissionInfo($mission_id);
        $this->assign('mission_data',$mission_data);
        return $this->fetch('editMission');
    }

    /**
     * 任务保存(新增、编辑)
     *
     * @author  xdw
     * @date  20170715
     */
    public function save(){
        $post = [
            'orgcode'       => Request::instance()->post('orgcode'),
            'mission_value'       => Request::instance()->post('mission_value'),
            'month'             => Request::instance()->post('month'),
        ];
        //新增
        if(!input('post.id')){
            //验证器判断
            $validate = $this->validate($post,'mission.add');
            if($validate !== true)
            {
                return zw_sprint_result('提交失败，'.$validate, '', FAIL_CODE);
            }
            $model = model('Mission','logic');
            $result = $model->addMission($post);
            $result? $this->success('新增成功', 'Mission/index') : $this->error('新增失败');
        }
        //编辑
        $mission_id = input('post.id');
        $validate = $this->validate($post,'mission.edit');
        if($validate !== true)
        {
            return zw_sprint_result('提交失败，'.$validate, '', FAIL_CODE);
        }
        $model = model('Mission','logic');
        $result = $model->editMission($mission_id,$post);
        $result? $this->success('编辑成功', 'Mission/index') : $this->error('编辑失败');
    }

    /**
     * 刪除任务
     *
     * @author  xdw
     * @date  20170715
     */
    public function deleteMission()
    {
        if (!input('id'))
        {
            return zw_sprint_result('请选择删除任务', '', FAIL_CODE);
        }
        $mission_id = Request::instance()->post('id');
        $model = model('Mission','service');
        $result = $model->deleteMission($mission_id);
        return $result ? zw_sprint_result('删除成功', $result) : zw_sprint_result('删除失败', '', FAIL_CODE);
    }

    /**
     * 搜索查看
     *
     * @author  xdw
     * @date  20170715
     *
     */
    public function searchMission(){
        $search_info = Request::instance()->get('search_info');
        $model = model('Mission','logic');
        $result = $model->searchMission($search_info);
        $page = $result->render();
        $data = $result->all();
        return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page]);
    }

    /**
     * 任务完成查询
     *
     * @author  xdw
     * @date  20170716
     *
     */
    public function getMissionStatus(){
        //获取树结构数据
        $org = model('Org','logic');
        $tree = $org->getTree(3);
        $this->assign('orgTree', json_encode($tree));
        $this->assign('orgs', NULL);
        return $this->fetch('statusMission');
    }


    /**
     * 任务批量导入
     * @author  xdw
     * @date  20170721
     */
    public function importMission(){
        return $this->fetch();
    }

    /**
     * 保存批量导入
     * @author  xdw
     * @date  20170721
     */
    public function saveImportMission(){

        set_time_limit(0);

        // 获取表单上传文件
        $file = request()->file('missionFile');
        if(empty($_FILES['missionFile']['tmp_name'])){
            // 上传失败获取错误信息
            $this->error('请选择上传的文件');
        }
        $info = $file->move(UPLOAD_FOLDER . 'upload');

        // 成功上传后 获取上传信息
        $fullName = $info->getRealPath();
        $ext = $info->getExtension(); //文件扩展名

        if (!in_array($ext, ['xls', 'xlsx', 'XLS', 'XLSX'])) {
            if(is_file($fullName))
                @unlink($fullName);
            return zw_sprint_result('导入失败：请上传正确格式的Excel表格（可下载参考模板）','', FAIL_CODE);
        }

        if(empty($fullName) or !file_exists($fullName)){ return '文件不存在';}
        $PHPReader = new \PHPExcel_Reader_Excel2007();        //建立reader对象
        if(!$PHPReader->canRead($fullName)){
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if(!$PHPReader->canRead($fullName)){
                return zw_sprint_result('上传失败，无法识别的excel格式，请上传正确格式的Excel表格（可下载参考模板）','', FAIL_CODE);
            }
        }
        $PHPExcel       = $PHPReader->load($fullName);        //建立excel对象
        $currentSheet   = $PHPExcel->getSheet(0);        //**读取excel文件中的指定工作表*/
        $allRow         = $currentSheet->getHighestRow();        //**取得一共有多少行*/
        //$allRow = 5000; //测试，只导200行
        $startRow = 3; //从第2行开始
        $rowIndex = 0;
        $successRowCount = 0;
        $failRowCount = 0;

        $result = [];
        $result[0] = '';
        $result[1] = '';
        $new_mission_array = array();
        $data_flag = true;
        for ($i = $startRow; $i <= $allRow; $i++) {
            try {
                $rowIndex = $i;
                $new_mission['month'] = $this->get_cell_value($currentSheet, 'A' . $i);
                $new_mission['orgcode'] = $this->get_cell_value($currentSheet, 'B' . $i);
                $integral = $this->get_cell_value($currentSheet, 'C' . $i);
                $new_mission['mission_value'] = (empty($integral)) ? 0 : round($integral,2);
                if($new_mission['month']=='' || $new_mission['orgcode']=='' ){
                    $data_flag = false;
                    echo '<h2>导入失败！导入到<span style="color: red;">第' . $rowIndex . '行</span>时失败。‘时间’或‘门店编码’不能为空！</h2>'; break;
                }
                else if(strlen($new_mission['month']) != 7 || substr($new_mission['month'],4,1) != '-'){
                    $data_flag = false;
                    echo '<h2>导入失败！导入到<span style="color: red;">第' . $rowIndex . '行</span>时失败。时间格式不正确!</h2>'; break;
                }
                $map['month'] = ['eq',$new_mission['month']] ;
                $map['orgcode'] = ['eq',$new_mission['orgcode']] ;
                $has_recode = Db::view('org_mission','id')->where($map)->find();
                if($has_recode){
                    Db::name('org_mission')->where($map)->update($new_mission);
                }else {
                    //Db::name('org_mission')->insert($new_mission);
                    $new_mission_array[] = $new_mission;//批量插入
                }
                $successRowCount ++;
            }
            catch (Exception $e)
            {
                $failRowCount ++;
                $result[] = ['message' => '第' . $rowIndex . '行导入失败，'. $e->getMessage()];
            }
        }
        if ($data_flag) {
            $result = Db::name('org_mission')->insertAll($new_mission_array);
            $this->success('导入成功', 'mission/index');
        }
    }

    private function get_cell_value($sheet, $addr)
    {
        $cell =$sheet->getCell($addr)->getValue();
        if($cell instanceof \PHPExcel_RichText){ //富文本转换字符串
            $cell = $cell->__toString();
        }
        return $cell;
    }

    /**
     * 记录 多条件搜索
     * @author  xdw
     * @date  20170724
     */
    public  function searchRecordDetail(){
        $post = [
            'month'              => Request::instance()->get('month'),
            'orgcode'            => Request::instance()->get('orgcode'),
            'org_id'             => Request::instance()->get('org_id'),
        ];
        $validate = $this->validate($post,'Mission.search');
        if($validate !== true)
        {
            return zw_sprint_result('查询失败，'.$validate, '', FAIL_CODE);
        }
        //获取树结构数据
        $logic = model('Mission','logic');
        $res = $logic->searchRecordDetail($post);
        $page = $res->render();
        $data = $res->all();
        return  zw_sprint_result('获取成功', ['data' => $data, 'page' => $page]);
        //渲染界面
    }

    /**
     * 状态 多条件搜索
     * @author  xdw
     * @date  20170724
     */
    public  function searchStatusDetail(){
        $post = [
            'month'              => Request::instance()->get('month'),
            'orgcode'            => Request::instance()->get('orgcode'),
            'org_id'             => Request::instance()->get('org_id'),
        ];
        $validate = $this->validate($post,'Mission.search');
        if($validate !== true)
        {
            return zw_sprint_result('查询失败，'.$validate, '', FAIL_CODE);
        }
        //获取树结构数据
        $logic = model('Mission','logic');
        $res = $logic->searchStatusDetail($post);
        $page = $res->render();
        $data = $res->all();

        //cx 第一页则显示未导入任务另外搜索 20170801
        if($res->currentPage() == 1){
            $unset_mission = $logic->getUnsetMission($post);
        }
        else{
            $unset_mission = array();
        }
        //cx 未导入任务另外搜索20170801

        return  zw_sprint_result('获取成功', ['data' => $data, 'unset_mission' =>$unset_mission,  'page' => $page]);
//        return  zw_sprint_result('获取成功', ['data' => $data,   'page' => $page]);
        //渲染界面
    }


    //计算页面
    public function calculatePage(){
        $post = [
            'org_id'             => Request::instance()->get('org_id'),
            'month'             => Request::instance()->get('month'),
            'method'             => Request::instance()->get('method')
        ];
        //验证器判断
        //获取所有的片区
        $service = model('Org','service');
        $orgs = $service->getNodeByParentIdAndLevelId($post['org_id']);
        //渲染界面
        $this->assign('orgs', json_encode($orgs));
        $this->assign('month', $post['month']);
        $this->assign('method', $post['method']);
        return $this->fetch('calculatepage');
    }
}