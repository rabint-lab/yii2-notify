<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\notify\models\search\NotificationSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
//'action' => ['index'],
'method' => 'get',
]); ?>
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Yii::t('app', 'Search') ?></h3>
    </div>
    <div class="panel-body">

        <div class="search_box notification-search">

            <div class="row">

                        <div class="col-sm-4"><?= $form->field($model, 'id') ?></div>

        <div class="col-sm-4"><?= $form->field($model, 'user_id') ?></div>

        <div class="col-sm-4"><?= $form->field($model, 'created_at') ?></div>

        <div class="col-sm-4"><?= $form->field($model, 'updated_at') ?></div>

        <div class="col-sm-4"><?= $form->field($model, 'expire_at') ?></div>

        <!--<div class="col-sm-4"><?php // echo $form->field($model, 'link') ?></div>-->

        <!--<div class="col-sm-4"><?php // echo $form->field($model, 'content') ?></div>-->

        <!--<div class="col-sm-4"><?php // echo $form->field($model, 'seen') ?></div>-->

        <!--<div class="col-sm-4"><?php // echo $form->field($model, 'media') ?></div>-->


            </div>

        </div>
    </div>
    <div class="panel-footer">
        <?php // echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default pull-left']) ?>
        <?= Html::a(Yii::t('app', 'Reset'), ['index'], ['class' => 'btn btn-default pull-left']) ?>
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary pull-left']) ?>
        <div class="clearfix"></div>
    </div>
</div>
<?php ActiveForm::end(); ?>