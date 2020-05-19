<?php

use yii\bootstrap4\Html;


/* @var $this yii\web\View */
/* @var $model rabint\notify\models\Notification */


$this->title = Yii::t('app', 'Create') .  ' ' . Yii::t('app', 'Notification') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box-form notification-create"  id="ajaxCrudDatatable">

    <h2 class="ajaxModalTitle" style="display: none"><?=  $this->title; ?></h2>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
