<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2017/6/19
 * Time: 16:52
 */

use \app\models\User;
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '卡号管理';
$this->params['active_nav_group'] = 4;
$urlPlatform = Yii::$app->controller->route;
?>

<style>
    .table tbody tr td{
        vertical-align: middle;
    }

    .badge{
        font-size: 100%;
    }

    .openid{
        display: none;
    }

    .show{
        float: right;
    }

    .toggle{
        display: none;
    }
</style>

<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="dropdown float-left">


            <div class="dropdown float-right ml-2">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    批量设置
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                     style="max-height: 200px;overflow-y: auto">
                    <a href="javascript:void(0)" class="btn btn-secondary batch dropdown-item"
                       data-url="<?= $urlManager->createUrl(['mch/user/recharge-card-batch']) ?>" data-content="是否批量发放"
                       data-type="0">批量发放</a>
                    <a href="javascript:void(0)" class="btn btn-warning batch dropdown-item"
                       data-url="<?= $urlManager->createUrl(['mch/user/recharge-card-batch']) ?>" data-content="是否批量激活"
                       data-type="1">批量激活</a>
                    <a href="javascript:void(0)" class="btn btn-secondary batch dropdown-item"
                       data-url="<?= $urlManager->createUrl(['mch/user/recharge-card-batch']) ?>" data-content="是否批量不发放"
                       data-type="4">批量不发放</a>
                    <a href="javascript:void(0)" class="btn btn-warning batch dropdown-item"
                       data-url="<?= $urlManager->createUrl(['mch/user/recharge-card-batch']) ?>" data-content="是否批量不激活"
                       data-type="3">批量不激活</a>
                    <a href="javascript:void(0)" class="btn btn-danger batch dropdown-item"
                       data-url="<?= $urlManager->createUrl(['mch/user/recharge-card-batch']) ?>" data-content="是否批量删除"
                       data-type="2">批量删除</a>
                </div>
            </div>

            <div class="dropdown float-right ml-2">

                <a class="btn btn-secondary export-btn"
                   href="javascript:">批量导出</a>
            </div>
        </div>


<!--        <div class="float-right mb-4">-->
<!--            <form method="get">-->
<!--                --><?php //$_s = ['keyword', 'page', 'per-page'] ?>
<!--                --><?php //foreach ($_GET as $_gi => $_gv) :
//                    if (in_array($_gi, $_s)) {
//                        continue;
//                    } ?>
<!--                    <input type="hidden" name="--><?//= $_gi ?><!--" value="--><?//= $_gv ?><!--">-->
<!--                --><?php //endforeach; ?>
<!---->
<!--                <div class="input-group">-->
<!--                    <input class="form-control mr-2"-->
<!--                           placeholder="手机号或联系方式"-->
<!--                           name="mobile"-->
<!--                           autocomplete="off"-->
<!--                           value="--><?//= isset($_GET['mobile']) ? trim($_GET['mobile']) : null ?><!--">-->
<!--                    <input class="form-control"-->
<!--                           placeholder="昵称"-->
<!--                           name="keyword"-->
<!--                           autocomplete="off"-->
<!--                           value="--><?//= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?><!--">-->
<!--                    <span class="input-group-btn">-->
<!--                    <button class="btn btn-primary">搜索</button>-->
<!--                </span>-->
<!--                </div>-->
<!--            </form>-->
<!--        </div>-->
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th style="text-align: center">
                    <label class="checkbox-label" style="margin-right: 0px;">
                        <input type="checkbox" class="goods-all">
                        <span class="label-icon"></span>
                    </label>
                </th>
                <th>ID</th>
                <th>卡号</th>
                <th>类型</th>
                <th>面值</th>
                <th>是否发放</th>
                <th>激活状态</th>
                <th>生成时间</th>
                <th>激活人员</th>
                <th>激活时间</th>
                <th>操作</th>

            </tr>
            </thead>
            <?php foreach ($list as $u) : ?>
                <tr>
                    <td class="nowrap" style="text-align: center">
                        <label class="checkbox-label" style="margin-right: 0px;">
                            <input type="checkbox"
                                   class="goods-one"
                                   value="<?= $u['id'] ?>">
                            <span class="label-icon"></span>
                        </label>
                    </td>
                    <td><?= $u['id'] ?></td>

                    <td>
                        <div style="min-width: 18rem;">
                            <?= $u['card_id']; ?>
                            <button class="btn btn-info btn-sm show">显示秘钥</button>
                            <button class="btn btn-info btn-sm show toggle ">隐藏秘钥</button>
                        </div>
                        <div class='openid' style="color: red;"><?= $u['card_password'] ?></div>
                    </td>

                    <td><?= $u['r_name'] ?></td>
                    <td><?= $u['r_price'] ?></td>
                    <td><?= $u['is_fa']?"<b style='color:red;'>已发放</b>":'未发放' ?></td>
                    <td><?= $u['is_use']?"<b style='color:red;'>已使用":'未使用' ?></td>
                    <td><?= date('Y-m-d H:i:s', $u['addtime']) ?></td>
                    <td><?=  $u['nickname'] ?></td>
                    <td><?=$u['usetime'] ?date('Y-m-d H:i:s', $u['usetime']) :'' ?></td>


                    <td>

                        <a class="btn btn-sm btn-success rechangeBtn" href="javascript:"
                           data-url="<?= $urlManager->createUrl(['mch/user/set-cardfa', 'id' => $u['id']]) ?>"
                           data-content="是否发放？">发放</a>
                        <a class="btn btn-sm btn-success rechargeMoney" href="javascript:"
                           data-url="<?= $urlManager->createUrl(['mch/user/set-cardji', 'id' => $u['id']]) ?>"
                           data-content="是否激活？">激活</a>
                        <a class="btn btn-sm btn-danger del" href="javascript:"
                           data-url="<?= $urlManager->createUrl(['mch/user/set-carddel', 'id' => $u['id']]) ?>"
                           data-content="是否删除？">删除</a>
                    </td>



                </tr>
            <?php endforeach; ?>
        </table>
        <div class="text-center">
            <nav aria-label="Page navigation example">
                <?php echo LinkPager::widget([
                    'pagination' => $pagination,
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                    'maxButtonCount' => 5,
                    'options' => [
                        'class' => 'pagination',
                    ],
                    'prevPageCssClass' => 'page-item',
                    'pageCssClass' => "page-item",
                    'nextPageCssClass' => 'page-item',
                    'firstPageCssClass' => 'page-item',
                    'lastPageCssClass' => 'page-item',
                    'linkOptions' => [
                        'class' => 'page-link',
                    ],
                    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                ])
                ?>
            </nav>
            <div class="text-muted">共<?= $row_count ?>条数据</div>
        </div>
    </div>

</div>

<?= $this->render('/layouts/ss', [
    'exportList' => $exportList,
]) ?>

<script>
    $(document).ready(function(){
        $(".show").click(function(){
            $(this).parent().next(".openid").toggle();
            $(this).addClass('toggle');
            $(this).siblings('button').removeClass('toggle');
        });
    });
</script>

<script>
    $(document).on('click', '.goods-all', function () {
        var checked = $(this).prop('checked');
        $('.goods-one').prop('checked', checked);
        if (checked) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
    $(document).on('click', '.goods-one', function () {
        var checked = $(this).prop('checked');
        var all = $('.goods-one');
        var is_all = true;//只要有一个没选中，全选按钮就不选中
        var is_use = false;//只要有一个选中，批量按妞就可以使用
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                is_use = true;
            } else {
                is_all = false;
            }
        });
        if (is_all) {
            $('.goods-all').prop('checked', true);
        } else {
            $('.goods-all').prop('checked', false);
        }
        if (is_use) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
    $(document).on('click', '.batch', function () {
        var all = $('.goods-one');
        var is_all = true;//只要有一个没选中，全选按钮就不选中
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                is_all = false;
            }
        });
        if (is_all) {
            $.myAlert({
                content: "请先勾选卡密"
            });
        }
    });
    $(document).on('click', '.is_use', function () {
        var a = $(this);
        var goods_group = [];
        var all = $('.goods-one');
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                var goods = {};
                id = $(all[i]).val();
                goods_group.push(id);
            }
        });
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    data: {
                        goods_group: goods_group,
                        type: a.data('type'),
                    },
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {

                        }
                    },
                    complete: function () {
                        $.myLoadingHide();
                    }
                });
            }
        })
    });
    $(document).on('click', '.del', function () {
        var a = $(this);
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {
                            $.myAlert({
                                title: res.msg
                            });
                        }
                    }
                });
            }
        });
        return false;
    });
    $(document).on('click', '.rechangeBtn', function () {
        var a = $(this);
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {
                            $.myAlert({
                                title: res.msg
                            });
                        }
                    }
                });
            }
        });
        return false;
    });
    $(document).on('click', '.rechargeMoney', function () {
        var a = $(this);
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {
                            $.myAlert({
                                title: res.msg
                            });
                        }
                    }
                });
            }
        });
        return false;
    });


    var app = new Vue({
        el: '#app',
        data: {
            user_id: -1,
            price: 0,
            type: -1,
            rechargeType: 1,
            money: 0
        }
    });



</script>
