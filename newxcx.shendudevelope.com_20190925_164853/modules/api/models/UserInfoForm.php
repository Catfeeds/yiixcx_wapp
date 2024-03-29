<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/7/31
 * Time: 10:31
 * @copyright ©2018 云上科技
 */

namespace app\modules\api\models;


use app\hejiang\ApiCode;
use app\models\Level;
use app\models\Option;
use app\models\Setting;
use app\models\Share;
use app\models\User;
use app\models\UserCenterForm;
use app\models\UserCenterMenu;

/**
 * Class UserInfoForm
 * @package app\modules\api\models
 * @property User|bool $user
 */
class UserInfoForm extends ApiModel
{
    private $user;

    public function search()
    {
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => $this->getData()
        ];
    }

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->user = \Yii::$app->user->isGuest ? false : \Yii::$app->user->identity;
    }

    public function getData()
    {
        $order_count = $this->getOrderCount();
        $share_setting = Setting::find()->where(['store_id' => $this->store->id])->asArray()->one();
        $user = User::findOne(['id' => \Yii::$app->user->identity->id]);
        $level = $user->level;
        $user_info = $this->getUserInfo();
        $next_level = Level::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'status' => 1])
            ->andWhere(['>', 'level', $level])->orderBy(['level' => SORT_ASC, 'id' => SORT_DESC])->asArray()->one();

        //余额功能配置
        $balance = Option::get('re_setting', $this->store->id, 'app');
        $balance = json_decode($balance, true);
        //我的钱包 选项
        $wallet['integral'] = 1;
        if ($balance) {
            $wallet['re'] = $balance['status'];
        }

        /* 旧版的菜单列表先保留以兼容旧版，后期去掉 */
        $user_center_menu = new UserCenterMenu();
        $user_center_menu->store_id = $this->store->id;

        $user_center_form = new UserCenterForm();
        $user_center_form->store_id = $this->store->id;
        $user_center_form->user_id = $user->id;
        $user_center_form->_platform = \Yii::$app->request->get('_platform');
        $user_center_data = $user_center_form->getData();
        $user_center_data = $user_center_data['data'];
        $wallet['is_wallet'] = $user_center_data['is_wallet'];
        $wallet['is_menu'] = $user_center_data['is_menu'];
        if ($user_center_data['copyright']['open_type'] == 'wxapp') {
            $url = $user_center_data['copyright']['url'];
            preg_match('/^[^\?+]\?([\w|\W]+)=([\w|\W]*?)&([\w|\W]+)=([\w|\W]*?)$/', $url, $res);
            $user_center_data['copyright']['appId'] = $res[2];
            $user_center_data['copyright']['path'] = urldecode($res[4]);
        }
        return [
            'order_count' => $order_count,
            'show_customer_service' => $this->store->show_customer_service,
            'contact_tel' => $this->store->contact_tel,
            'share_setting' => $share_setting,
            'user_info' => $user_info,
            'next_level' => $next_level,
            'menu_list' => $user_center_menu->getMenuList(),
            'user_center_bg' => $user_center_data['user_center_bg'],
            'orders' => $user_center_data['orders'],
            'wallets' => $user_center_data['wallets'],
            'menus' => $user_center_data['menus'],
            'copyright' => $user_center_data['copyright'],
            'wallet' => $wallet,
            'style' => [
                'menu' => $user_center_data['menu_style'],
                'top' => $user_center_data['top_style'],
            ],
            'setting' => [
                'is_order' => $user_center_data['is_order']
            ]
        ];
    }

    protected function getOrderCount()
    {
        if (!$this->user) {
            return [];
        }
        $order_count = OrderListForm::getCountData($this->store->id, $this->user->id);
        return $order_count;
    }

    protected function getUserInfo()
    {
        if (!$this->user) {
            return User::getDefaultUser();
        }

        $parent = User::findOne($this->user->parent_id);
        $share = Share::findOne(['user_id' => $this->user->parent_id, 'status' => 1, 'is_delete' => 0]);
        $level = $this->user->level;
        $nowLevel = Level::findOne(['store_id' => $this->store->id, 'level' => $level, 'is_delete' => 0]);
        $userInfo = [
            'nickname' => $this->user->nickname,
            'binding' => $this->user->binding,
            'avatar_url' => $this->user->avatar_url,
            'is_distributor' => $this->user->is_distributor,
            'parent' => $share ? ($share->name ? $share->name : $parent->nickname) : "总店",
            'id' => $this->user->id,
            'is_clerk' => $this->user->is_clerk,
            'level' => $level,
            'level_name' => $nowLevel ? $nowLevel->name : "普通用户",
            'integral' => $this->user->integral,
            'money' => $this->user->money,
            'blacklist' => $this->user->blacklist,
            'access_token' => $this->user->access_token
        ];
        return $userInfo;
    }
}
