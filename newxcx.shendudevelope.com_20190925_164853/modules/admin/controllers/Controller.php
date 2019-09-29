<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/2
 * Time: 13:43
 */

namespace app\modules\admin\controllers;


use app\modules\admin\behaviors\AdminBehavior;
use app\modules\admin\behaviors\LoginBehavior;

class Controller extends \app\controllers\Controller
{
    public $layout = 'main';

    public $auth_info;

    public function init()
    {
        parent::init();
    }
    
    // 行为
    // 行为是 yii\base\Behavior 或其子类的实例。 行为，也称为 mixins， 可以无须改变类继承关系即可增强一个已有的 组件 类功能。 当行为附加到组件后，它将“注入”它的方法和属性到组件， 然后可以像访问组件内定义的方法和属性一样访问它们。 此外，行为通过组件能响应被触发的事件，从而自定义或调整组件正常执行的代码。

    // 附加行为
    // 可以静态或动态地附加行为到组件。
    // 要静态附加行为，覆写行为要附加的组件类的 behaviors() 方法即可。 behaviors() 方法应该返回行为配置列表。 每个行为配置可以是行为类名也可以是配置数组。
    public function behaviors()
    {   
        // print_r(parent::behaviors());
        // die;
        // array_merge() 函数把一个或多个数组合并为一个数组。
        return array_merge(parent::behaviors(), [
            'permission' => [
                'class' => AdminBehavior::className(),
            ],
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
        // 通过指定行为配置数组相应的键可以给行为关联一个名称。这种行为称为命名行为。
    }

    /**
     * 获取当前用户拥有的插件权限
     * @return mixed|null
     */
    public function getUserAuth()
    {
        $userAuth = json_decode(\Yii::$app->admin->identity->permission, true);

        return $userAuth;
    }
}
