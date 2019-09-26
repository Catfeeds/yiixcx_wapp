<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 9:36
 */

namespace app\modules\api\models;

use app\models\Level;
use app\models\User;
use app\models\Share;
use app\models\Cardmi;
use app\models\Cardlog;
use app\modules\api\models\wxbdc\WXBizDataCrypt;
use Curl\Curl;
use app\hejiang\ApiResponse;

class CardmiForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $level_type;
    public $card_id;
    public $is_fa;
    public $is_delete;
    public $card_password;
    public $is_use;
    public $addtime;
    public $usetime;
    public $date;


    public function rules()
    {
        return [
            [['user_id','card_id','card_password'], 'required'],
            [['level_type'], 'integer'],

        ];
    }
    public function search()
    {

        //搜索置顶月份的充值记录及余额消费记录
        $date = $this->date?$this->date:date('Y-m');
        $start = strtotime($date);
        $cardlog = Cardlog::tableName();
        $end = strtotime(date('Y-m-t', $start)) + 86400;
        $sql = "FROM {$cardlog}";
        $user_id = $this->user_id;
//        echo $user_id;die;
        $select = "SELECT * ";

        $where = " WHERE  user_id = {$user_id} and use_time >= {$start} AND use_time <= {$end}";

        $list = \Yii::$app->db->createCommand($select . $sql . $where . " ORDER BY use_time DESC")->queryAll();

        foreach ($list as $index => $value) {

            $list[$index]['date'] = date('Y-m-d H:i:s', $value['use_time']);
        }

        return new ApiResponse(0,'success',[
            'list' => $list,
            'date' => $date
        ]);

    }

    public function jihuo()
    {
        $t = \Yii::$app->db->beginTransaction();


        try{
            $user = User::findOne(['id' => $this->user_id]);
            $cardmi = Cardmi::findOne(['card_id' => trim($this->card_id),'card_password' => trim($this->card_password)]);
            if(empty($cardmi)){
                return [
                    'code'=>1,
                    'msg'=>'该卡密信息不存在！'
                ];
            }

            $level = Level::findOne(['id' => $cardmi->level_type,'store_id' => $this->store_id]);

            $user->level = $level->level;

            $user->peisong = intval($user->peisong +$level->cishu);
            if($cardmi['user_id']){
                return [
                    'code'=>1,
                    'msg'=>'该卡密已被使用'
                ];
            }

            if( $user->save()) {
                //更新卡密表
                $cardmi->user_id = $this->user_id;
                $cardmi->is_use = 1;
                $cardmi->is_fa = 1;
                $cardmi->usetime = time();

                $sss = $cardmi->save();

                //更新卡密日志表
                $cardlog = new Cardlog();
                $cardlog->store_id =  $this->store_id;
                $cardlog->user_id = $this->user_id;
                $cardlog->title = $level->name . "会员卡激活，卡号[".$this->card_id."]";
                $cardlog->cishu = $level->cishu;
                $cardlog->type = 0;
                $cardlog->use_time = time();
                $cardlog->open_type = 1;
                $cardlog->use_card_id = $cardmi->id;

                $sst =  $cardlog->save();

                $t->commit();
                $share = Share::findOne(['user_id' => $user->parent_id]);
                $share_user = User::findOne(['id' => $share->user_id]);
                return [
                    'code' => 0,
                    'msg' => 'success',
                    'data' => [
                        'access_token' => $user->access_token,
                        'nickname' => $user->nickname,
                        'avatar_url' => $user->avatar_url,
                        'is_distributor' => $user->is_distributor ? $user->is_distributor : 0,
                        'parent' => $share->id ? ($share->name ? $share->name : $share_user->nickname) : '总店',
                        'id' => $user->id,
                        'is_clerk' => $user->is_clerk === null ? 0 : $user->is_clerk,
                        'integral' => $user->integral === null ? 0 : $user->integral,
                        'money' => $user->money === null ? 0 : $user->money,
                        'binding' => $user->binding,
                        'level' => $user->level,
                        'peisong' => $user->peisong,
                        'blacklist' => $user->blacklist,
                    ]
                ];

            } else {
                $t->rollBack();

                return false;
            }
        } catch(\Exception $e) {
            $t->rollBack();
            $errorInfo = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ];
            return [
                'code'=>1,
                'msg'=>'激活失败',
                'data'=>$errorInfo
            ];
        }


    }

}
