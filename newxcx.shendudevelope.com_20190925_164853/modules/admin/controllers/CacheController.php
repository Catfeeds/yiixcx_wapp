<?php
/**
 * @copyright ©2018 云上科技
 * author: wxf
 */

namespace app\modules\admin\controllers;

use app\models\common\admin\cache\CommonClearCache;
use Yii;

class CacheController extends Controller
{
    public function actionIndex()
    {
        //TODO 暂时去除
        // $this->checkIsAdmin();
        if (\Yii::$app->request->isPost) {
            $common = new CommonClearCache();
            $common->attributes = Yii::$app->request->post();
            return $common->clear();
        } else {
            return $this->render('index');
        }
    }
}
