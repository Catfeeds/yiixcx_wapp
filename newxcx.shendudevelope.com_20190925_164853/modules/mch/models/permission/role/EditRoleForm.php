<?php
/**
 * @copyright ©2018 云上科技
 * author: wxf
 */

namespace app\modules\mch\models\permission\role;

use app\models\AuthRole;
use app\modules\mch\models\MchModel;

class EditRoleForm extends MchModel
{
    public $roleId;

    public function edit()
    {
        $edit = AuthRole::find()->andWhere(['id' => $this->roleId])->one();

        return $edit;
    }
}
