<?php
/**
 * Created by PhpStorm.
 * User: xdw
 * Date: 2017/7/15
 * Time: 9:42
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;

require ROOT_PATH . 'extend/PHPExcel/PHPExcel.php';
//require EXTEND_PATH . 'PHPExcel/PHPExcel.php';

class Excel extends Controller{

    /**
     * 数据表导入
     * @author  xdw
     * @date  20170807
     */
    public function index()
    {
        return $this->fetch('import');
    }

    /**
     * 保存
     * @author  xdw
     * @date  20170807
     */
    public function saveImport()
    {
        set_time_limit(0);
        //获取上传文件
//        $fullName = $this->getUploadFilePath();
        $fullName = ROOT_PATH.'public/upload/excel/20170802.xls';
        //获取excel表中的数据
        $table_params = [//设置表格条件
            'startRow'=>3,
        ];
        $excel_file = [//制定表格列数据
            'time'=>'A',
            'name'=>'B',
            'value'=>'C',
            'uuuuu'=>'D',
        ];
        $result = $this->getExcelFileData($fullName,$table_params,$excel_file);
        if(!empty($result) && count($result)!=0){
            foreach ($result as $v){
                var_dump($v);
                echo '<br/>';
            }
        }else{
            echo 1111;
        }

    }

    /**
     * 获取上传文件路径
     */
    function getUploadFilePath(){
        // 获取表单上传文件
        $file = request()->file('excelFile');
        if(empty($_FILES['excelFile']['tmp_name'])){
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
        return $fullName;
    }

    /**
     * 获取excel表中的数据
     * @param $fullName 文件路径
     * @param array $table_params 表格定位参数：如，起始读取行、截止读取行、起始读取列、截止读取列等
     * @param array $field_datas 数据列字段（表头）
     *
     * @return string
     */
    function getExcelFileData($fullName,$table_params=[],$field_datas){
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

        $startRow = ($table_params['startRow']) ? $table_params['startRow'] : 0;//起始行
        $successRowCount = 0;//正常的行
        $failRowCount = 0;//不正常的行
        $result = [];//记录每行读取情况
        $excelDatas = array();//返回读取到的数据
        for ($i = $startRow; $i <= $allRow; $i++) {//每行
            try {
                $rowIndex = $i;
                foreach ($field_datas as $k=>$v) {
                    $excelDatas[][$k] = $this->get_cell_value($currentSheet, $v . $i);//每行中的每个单元格
                    $successRowCount++;
                    $result['success'][] = ['message' => '第' . $rowIndex . '行读取成功。'];
                }
            }
            catch (Exception $e)
            {
                $failRowCount ++;
                $result['false'][] = ['message' => '第' . $rowIndex . '行读取失败，'. $e->getMessage()];
            }
        }

        return $excelDatas;
    }

    /**
     * excel单位格中的数据格式处理
     * @param $sheet
     * @param $addr
     *
     * @return string
     */
    private function get_cell_value($sheet, $addr)
    {
        $cell =$sheet->getCell($addr)->getValue();
        if($cell instanceof \PHPExcel_RichText){ //富文本转换字符串
            $cell = $cell->__toString();
        }
        return $cell;
    }

    /**
     * 导出数据表
     */
    function exportExcel(){
        error_reporting(0);
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                    ->setLastModifiedBy("Maarten Balliauw")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet(0)->getDefaultColumnDimension()->setWidth(15);

        //获取数据
        $res = Db::view('fyjc_org_type')
            ->order('orgcode')
            ->select();

        $sheet=$objPHPExcel->getActiveSheet();
        $sheet->getColumnDimension('M')->setWidth(50);
        $sheet->setTitle("表格导出测试")
              ->mergeCells('A1:F1')->setCellValue('A1', "表格导出测试");
        //单元格合并举例 ------start------------------
        //$sheet->mergeCells('A2:A3')->setCellValue('A2', "序号");
        //$sheet->mergeCells('B2:B3')->setCellValue('B2', "查获单位");
        //$sheet->mergeCells('C2:C3')->setCellValue('C2', "查获时间");
        //$sheet->setCellValue('E3', "总数");
        //$sheet->setCellValue('F3', "男");
        //$sheet->setCellValue('G3', "女");
        //单元格合并举例 -----end-------------------
        $sheet->setCellValue('A2', "序号");
        $sheet->setCellValue('B2', "门店编码");
        $sheet->setCellValue('C2', "门店名称");
        $sheet->setCellValue('D2', "所属片区");
        $sheet->setCellValue('E2', "门店类型");
        $sheet->setCellValue('F2', "门店状态");
        $i=3;//行号（除表头外，从第几行开始是记录）
        $title_row=2;//行号（除表头外，从第几行开始是记录）
        $res = $res ? $res : array();
        foreach ($res as $rows) {

            $type = '';
            switch ($rows['type']){
                case 1:
                    $type = '自营店';break;
                case 2:
                    $type = '承包店';break;
                case 3:
                    $type = '高速店';break;
                case 4:
                    $type = '虚拟店';break;
                case 5:
                    $type = '内部店';break;
                case 6:
                    $type = '商客门店';break;
                default:
                    break;
            }

            $sheet->setCellValue('A'.$i,($i-$title_row));
            $sheet->setCellValue('B'.$i, $rows['orgcode']);
            $sheet->setCellValue('C'.$i, $rows['orgname']);
            $sheet->setCellValue('D'.$i, $rows['parent_org_name']);
            $sheet->setCellValue('E'.$i, $type);
            $sheet->setCellValue('F'.$i, ($rows['isenable']==1)?'启用':'禁用（不参与计酬）');
            $i++;
        }

        $fileName = "表格导出测试.xlsx";
        $writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }


}