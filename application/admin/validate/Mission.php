<?php
/**
 * Created by PhpStorm.
 * User: sy
 * Date: 2017/6/20
 * Time: 10:29
 */

namespace app\admin\validate;

use think\Validate;


class Mission extends Validate
{

    protected $rule = [
        ['orgcode','require','门店组织不能为空'],
        ['mission_value','require','任务值不能为空'],
        ['month','require','月份不能为空'],
    ];
    protected $scene = [
        'add' => ['org_id','mission_value'],
        'edit' => ['org_id','mission_value'],
        'search' => ['']
    ];

}