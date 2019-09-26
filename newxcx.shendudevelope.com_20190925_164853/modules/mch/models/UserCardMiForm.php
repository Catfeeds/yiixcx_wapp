<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2017/8/3
 * Time: 13:52
 */

namespace app\modules\mch\models;

use app\hejiang\ApiResponse;
use app\models\IntegralOrder;
use app\models\Level;
use app\models\RechargeCard;
use app\models\MsOrder;
use app\models\Order;
use app\models\PtOrder;
use app\models\Share;
use app\models\UserCoupon;
use app\models\Shop;
use app\models\Store;
use app\models\User;
use app\models\Cardmi;
use app\models\YyOrder;
use app\modules\mch\extensions\Export;
use yii\data\Pagination;

class UserCardMiForm extends MchModel
{
    public $store_id;
    public $page;
    public $level_type;
    public $card_id;
    public $card_password;
    public $is_use;
    public $user_id;
    public $addtime;
    public  $is_fa;
    public $usetime;
    public $flag;
    public $fields;
    public function rules()
    {
        return [
            [[ 'card_id', 'user_id', 'card_password', 'flag','card_password'], 'trim'],
            [['page', 'is_use','store_id','level_type','card_password','is_delete','is_fa'], 'integer'],
            [['page'], 'default', 'value' => 1],

        ];
    }

    public function search()
    {
        $query = Cardmi::find()->alias('c')->where([
            'c.store_id' => $this->store_id,
            'c.is_delete' => 0
        ]) ->leftJoin(User::tableName() . ' u', 'u.id=c.user_id ')
            ->leftJoin(RechargeCard::tableName() . ' r', 'r.id = c.level_type' );

        if ($this->level_type) {
            $query->andWhere(['c.level_type' => $this->level_type]);
        }


        if ($this->flag === Export::EXPORT) {
                  $list = $query->select([
                        'u.nickname', 'c.*', 'r.name r_name', 'r.price r_price'
                    ])->orderBy('c.id DESC')->asArray()->all();
            $export = new ExportList();
            $export->fields = $this->fields;
            $export->UsercardExportData($list);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list = $query->select([
            'u.nickname', 'c.*', 'r.name r_name', 'r.price r_price'
        ])->limit($pagination->limit)->offset($pagination->offset)->orderBy('c.id DESC')->asArray()->all();

        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];
    }

    public function getUser()
    {
        $query = User::find()->where([
            'type' => 1,
            'store_id' => $this->store_id,
            'is_clerk' => 0,
            'is_delete' => 0
        ]);
        if ($this->keyword) {
            $query->andWhere([
                'or',
                ['LIKE', 'nickname', $this->keyword],
                ['LIKE', 'wechat_open_id', $this->keyword]
            ]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('addtime DESC')->asArray()->all();
//        $list = $query->orderBy('addtime DESC')->asArray()->all();

        return $list;
    }

    public function getShare()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $query = Share::find()->alias('s')
            ->where(['s.is_delete' => 0, 's.store_id' => $this->store_id, 's.status' => 1])
            ->leftJoin(['u' => User::tableName()], 'u.id=s.user_id')
            ->andWhere(['!=', 'u.id', $this->user_id])
            ->andWhere(['u.is_delete' => 0]);
        if ($this->keyword) {
            $query->andWhere([
                'or',
                ['like', 's.name', $this->keyword],
                ['like', 'u.nickname', $this->keyword]
            ]);
        }
        $list = $query->select('u.id,u.avatar_url,u.nickname,s.name')->limit(10)->asArray()->all();
        return new ApiResponse(0, '', [
            'list' => $list
        ]);
    }

    public function getClerk()
    {
        $query = User::find()->where([
            'type' => 1,
            'store_id' => $this->store_id,
            'is_clerk' => 1,
            'is_delete' => 0
        ]);
        if ($this->keyword) {
            $query->andWhere([
                'or',
                ['LIKE', 'nickname', $this->keyword],
                ['LIKE', 'wechat_open_id', $this->keyword]
            ]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('addtime DESC')->all();

        $newList = [];
        foreach ($list as &$item) {
            $newItem = [
                'id' => $item->id,
                'nickname' => $item->nickname,
                'shop' => $item->shop->name,
            ];
            $newList[] = $newItem;
        }
        unset($item);
        return $newList;
    }

    public function excelFields()
    {
        $list = [
            [
                'key' => 'id',
                'value' => 'ID',
                'selected' => 0,
            ],
            [
                'key' => 'level_name',
                'value' => '类型',
                'selected' => 0,
            ],
            [
                'key' => 'level_price',
                'value' => '面值',
                'selected' => 0,
            ],

            [
                'key' => 'card_id',
                'value' => '卡号',
                'selected' => 0,
            ],
            [
                'key' => 'card_password',
                'value' => '激活码',
                'selected' => 0,
            ],
            [
                'key' => 'is_fa',
                'value' => '发放状态',
                'selected' => 0,
            ],
            [
                'key' => 'is_use',
                'value' => '使用状态',
                'selected' => 0,
            ],
            [
                'key' => 'addtime',
                'value' => '添加时间',
                'selected' => 0,
            ],
//            [
//                'key' => 'usetime',
//                'value' => '使用时间',
//                'selected' => 0,
//            ],

        ];

        return $list;
    }
}
