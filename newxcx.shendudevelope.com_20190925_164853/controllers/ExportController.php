<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/7/18
 * Time: 17:46
 * @copyright ©2018 云上科技
 */

namespace app\controllers;


use app\modules\mch\models\data_export\ExportForm;

class ExportController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $form = new ExportForm();
        $post = \Yii::$app->request->post();
        $form->attributes = $post;
        return $this->asJson($form->export());
    }
}
