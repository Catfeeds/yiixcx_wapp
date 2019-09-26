<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2
 * Time: 14:01
 */

namespace app\modules\mch\models;

use app\models\RechargeCard;
use app\models\Cardmi;

use app\models\Model;
use yii\data\Pagination;

class RechargeCardListForm extends MchModel
{
    public $store_id;
    public $page;
    public $limit;
    public $keyword;

    public function rules()
    {
        return [
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 20],
            [['keyword'], 'trim'],
            [['keyword'], 'string'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = RechargeCard::find()->where(['store_id' => $this->store_id, 'is_delete' => 0]);

        if ($this->keyword) {
            $query->andWhere(['like', 'name', $this->keyword]);
        }


        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        $list = $query->offset($p->offset)->limit($p->limit)->orderBy(['id' => SORT_ASC])->asArray()->all();
        if(is_array($list) && !empty($list)){
            foreach ($list as &$item) {
                $item['total'] =  Cardmi::find()->where(['level_type'=>$item['id'] , 'store_id' => $this->store_id, 'is_delete' => 0])->count();
                $item['is_fa'] =  Cardmi::find()->where(['level_type'=>$item['id'] , 'store_id' => $this->store_id, 'is_delete' => 0 ,'is_fa' => 1 ])->count();
                $item['is_use'] =  Cardmi::find()->where(['level_type'=>$item['id'] , 'store_id' => $this->store_id, 'is_delete' => 0,'is_use' => 1])->count();

            }

        }
        return [
            'list' => $list,
            'p' => $p,
            'row_count' => $count
        ];
    }

    public function getAllRechargeCard()
    {
        $list = RechargeCard::find()->where(['store_id' => $this->getCurrentStoreId(), 'is_delete' => Model::IS_DELETE_FALSE])
            ->select('id, name')
            ->orderBy('id')
            ->asArray()->all();

        return $list;
    }
}
