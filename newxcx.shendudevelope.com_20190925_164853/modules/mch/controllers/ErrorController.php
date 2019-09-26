<?php

/**
 * @copyright ©2018 云上科技
 * author: wxf
 */

namespace app\modules\mch\controllers;

use app\controllers\Controller;

class ErrorController extends Controller
{
    public function actionPermissionError()
    {
        return $this->render('permission-error');
    }
}
