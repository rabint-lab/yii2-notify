<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\notify\models\Notification */

$this->title = Yii::t('app', 'Update') .  ' ' . Yii::t('app', 'Notification') . ' «' . $model->id .'»';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="box-form notification-update"  id="ajaxCrudDatatable">

    <h2 class="ajaxModalTitle" style="display: none"><?=  $this->title; ?></h2>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
