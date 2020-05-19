<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\notify\models\search\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list_box notification-index">

    <h3><?= Html::encode($this->title) ?>
        <div class="toolbar pull-left float-left">
            <?php if ($dataProvider->getTotalCount()) { ?>
                <?= Html::a('<i class="fas fa-times"></i>' . \Yii::t('app', 'حذف همه'), ['remove-all'], ['data-method' => 'POST', 'class' => 'btn btn-danger btn-xs pull-left']); ?>
            <?php } ?>
        </div>
    </h3>
    <div class="clearfix"></div>
    <?=
    GridView::widget([
        //'layout' => "{items}\n{pager}",
        'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
        'columns' => [
                [
                'attribute' => 'id',
                'filterOptions' => ['style' => 'max-width:100px;'],
                'format' => 'raw',
            ],
//                [
//                'attribute' => 'content',
//                'format' => 'raw',
//            ],
//                [
//                'class' =>\kartik\grid\DataColumn::className(),
//                'attribute' => 'created_at',
//            ],
//            [
//                'class' => \kartik\grid\DataColumn::className(),
//                'label' => \Yii::t('app', 'وضعیت'),
//                'attribute' => 'read',
//                'enum' => ArrayHelper::getColumn(\rabint\notify\models\Notification::readStatuses(), 'title')
//            ],
//                [
//                'class' => 'yii\grid\ActionColumn',
//                'template' => '{delete} {view}',
//            ],
        ],
    ]);
    ?>

</div>
