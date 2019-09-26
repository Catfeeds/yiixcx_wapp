<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
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
class Cardmi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cardmi}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_use','is_fa', 'user_id','level_type','is_delete'], 'integer'],
            [['card_id', 'card_password'], 'string'],

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
            'level_type' => '等级类型',
            'card_id' => '卡号',
            'user_id' => '使用者id',
            'is_fa' => '发放状态',
            'is_delete' => '是否刪除',
            'card_password' => '卡密',
            'is_use' => '是否使用',
            'addtime' => 'Addtime',
            'usetime' => 'Usetime',
        ];
    }

}
