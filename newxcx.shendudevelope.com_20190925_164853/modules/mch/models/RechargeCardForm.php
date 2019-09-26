<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2
 * Time: 11:41
 */

namespace app\modules\mch\models;

use app\models\RechargeCard;
use app\models\Store;
use app\models\User;

/**
 * @property \app\models\Level $model;
 */
class RechargeCardForm extends MchModel
{
    public $store_id;
    public $model;
    public $name;
    public $price;
    public $status;

    public function rules()
    {
        return [
            [['name'],'trim'],
            [['name'],'string'],
            [['name','status'],'required','on'=>'edit'],
            [['status'],'in','range'=>[0,1]],
            [['price'],'number','min'=> 0,'max' => 99999999],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'等级名称',
            'status'=>'状态',
            'price'=>'升级所需价格'
        ];
    }
    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        if ($this->model->isNewRecord) {
            $this->model->is_delete = 0;
            $this->model->addtime = time();
        }
        if($this->price <= 0) {
            return [
                'code' => 1,
                'msg' => '面值必须大于0'
            ];
        }

        if ($this->name != $this->model->name) {
            $exit_0 = RechargeCard::find()->where(['name'=>$this->name,'store_id'=>$this->store_id,'is_delete'=>0])->exists();
            if ($exit_0) {
                return [
                    'code'=>1,
                    'msg'=>'名称重复'
                ];
            }
        }
//        if($this->model->id ) {
//            $count = User::find()->where(['store_id' => $this->store->id, 'level' => $this->model->level])->count();
//            if($count > 0) {
//                return [
//                    'code'=>1,
//                    'msg'=>'当前会员等级下有会员，禁止修改会员等级'
//                ];
//            }
//        }


        $this->model->store_id = $this->store_id;
        $this->model->name  = $this->name;
        $this->model->status = $this->status;
        $this->model->price = $this->price;

        if ($this->model->save()) {
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }

//    public function saveContent()
//    {
//        if (!$this->validate()) {
//            return $this->errorResponse;
//        }
//
//        $store = Store::findOne(['id'=>$this->store_id]);
//        $store->member_content = $this->content;
//
//        if ($store->save()) {
//            return [
//                'code'=>0,
//                'msg'=>'成功'
//            ];
//        } else {
//            return $this->getErrorResponse($store);
//        }
//    }
}
