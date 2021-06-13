<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use rabint\notify\models\Notification;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\notify\models\search\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'اعلانات');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list_box notification-index">

    <h3><?= Html::encode($this->title) ?>
        <div class="toolbar pull-left float-left">
            <?php if ($dataProvider->getTotalCount()) { ?>
                <?php // Html::a('<i class="fas fa-times"></i>' . \Yii::t('app', 'حذف همه'), ['remove-all'], ['data-method' => 'POST', 'class' => 'btn btn-danger btn-xs pull-left']); ?>
                <?=  Html::a('<i class="fas fa-eye"></i> ' . \Yii::t('app', 'تبدیل همه به خوانده شده'), ['read-all'], ['data-method' => 'POST', 'class' => 'btn btn-info btn-xs pull-left']); ?>
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
                [
                'attribute' => 'content',
                'format' => 'raw',
            ],
            [
                'class' =>\kartik\grid\DataColumn::className(),
                'attribute' => 'created_at',
                'value' => function ($model){
                    return \rabint\helpers\locality::anyToJalali($model->created_at,"Y-m-d  h:i:s");
                }
            ],
            [
                'class' => \kartik\grid\DataColumn::class,
                'label' => \Yii::t('app', 'وضعیت'),
                'attribute' => 'seen',
                'value' => function($model){
                    if(isset($model->seen)){
                        if($model->seen == Notification::SEEN_STATUS_YES){
                            return \yii\bootstrap4\Html::a(Notification::readStatuses()[$model->seen]['title'],['view','id'=>$model->id],['class'=>'btn btn-flat btn-success']); 
                        }else{
                            return \yii\bootstrap4\Html::a(Notification::readStatuses()[$model->seen]['title'],['view','id'=>$model->id],['class'=>'btn btn-flat btn-warning']); 
                        }

                    }
                },
                'format' => 'html'
            ],
//                [
//                'class' => 'yii\grid\ActionColumn',
//                'template' => '{view}',
//            ],
        ],
    ]);
    ?>

</div>
