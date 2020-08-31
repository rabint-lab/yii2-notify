<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\notify\models\Notification */

$this->title = \Yii::t('app', 'اعلان شماره ') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="detail_box notify-view">
    <div class="row">
        <div class="main-content">
            <div class="post-content-box ">
                <div class="content-text">

                    <p class="class">
                        <?= $model->content; ?>
                    </p>
                </div>
                <div class="clearfix"></div>
                <div class="postViewDetail">
                    <span class="item_stats stats_date" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?= \Yii::t('app', 'تاریخ ثبت:'); ?><?= \rabint\helpers\locality::jdate('j F Y - H:i', $model->created_at); ?>"><i class="fas fa-calendar"></i><?= \rabint\helpers\locality::jdate('j F Y - H:i', $model->created_at); ?></span>

                    <?= Html::a(\Yii::t('app', 'حذف این اعلان'), ['delete', 'id' => $model->id], ['data-method' => 'POST', 'class' => 'btn btn-danger btn-sm pull-left']); ?>
                    <div class="pull-left float-left">&nbsp;&nbsp;</div>
                    <?= Html::a(\Yii::t('app', 'پیوند مرتبط اعلان'), $model->link, ['class' => 'btn btn-success btn-sm pull-left']); ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
