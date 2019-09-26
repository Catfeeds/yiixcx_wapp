<?php

namespace app\models;


use Yii;

/**
 * This is the model class for table "{{%card}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $pic_url
 * @property string $content
 * @property integer $is_delete
 * @property integer $addtime
 */
class Cardlog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cardlog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id','open_type', 'use_card_id','use_time','pay_type'], 'integer'],
            [['title'], 'string'],
            [['money'], 'number'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'money' => '价格',
            'title' => '标题',
            'open_type' => '激活类型',
            'pay_type' => '支付类型',
            'user_id' => '使用者id',
            'use_card_id' => '激活卡id',
            'use_time' => 'Usetime',
        ];
    }

}
