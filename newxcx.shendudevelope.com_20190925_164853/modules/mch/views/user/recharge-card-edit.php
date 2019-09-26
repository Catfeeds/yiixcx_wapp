<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2
 * Time: 9:27
 */
defined('YII_ENV') or exit('Access Denied');
use \app\models\Level;

/* @var \app\models\Level $level */
$urlManager = Yii::$app->urlManager;
$this->title = '充值卡设置';
$this->params['active_nav_group'] = 4;
?>
<style>
    .attr-item .attr-delete {
        padding: .35rem .75rem;
        background: #d4cece;
        color: #fff;
        font-size: 1rem;
        font-weight: bold;
    }

    .attr-item .attr-delete:hover {
        text-decoration: none;
        color: #fff;
        background: #ff4544;
    }
</style>
<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">

        <form method="post" class="auto-form" autocomplete="off"
              return="<?= $urlManager->createUrl(['mch/user/recharge-card-edit']) ?>">
            <div class="form-body">
                <input type="hidden" name="scene" value="edit">




                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">充值卡名称</label>
                    </div>
                    <div class="col-5">
                        <input class="form-control" name="name" value="<?= $rechargecard->name ?>">
                    </div>
                </div>


                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">充值卡状态</label>
                    </div>
                    <div class="col-5">
                        <div class="pt-1">
                            <label class="custom-control custom-radio">
                                <input id="radio1"
                                       value="1" <?= $rechargecard->status == 1 ? "checked" : "" ?>
                                       name="status" type="radio" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">启用</span>
                            </label>
                            <label class="custom-control custom-radio">
                                <input id="radio2"
                                       value="0" <?= $rechargecard->status == 0 ? "checked" : "" ?>
                                       name="status" type="radio" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">禁用</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">充值卡面值</label>
                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <input class="form-control" name="price" value="<?= $rechargecard->price ?>">
                            <span class="input-group-addon bg-white">元</span>
                        </div>
                        <div class="text-muted fs-sm">输入充值卡面值</div>
                    </div>
                </div>





                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                    </div>
                    <div class="col-5">
                        <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                        <input type="button" class="btn btn-default ml-4" 
                               name="Submit" onclick="javascript:history.back(-1);" value="返回">
                    </div>
                </div>
             </div>
        </form>
    </div>
</div>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            title_list: <?=json_encode((array)$level->synopsis, JSON_UNESCAPED_UNICODE)?>,
            index:'',
        },
    });

    $(document).on('click', '.bat', function () {
        app.title_list.push({
                pic: '',
                content: '',
                title: '',
            });

    });
    $(document).on('click', '.delete-title', function () {
        var index = $(this).data('index');
        app.title_list.splice(index,1);
    });
</script>

