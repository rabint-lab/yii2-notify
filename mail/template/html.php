<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
    <p>
    <?= $text ?>
    </p>
    <a href="<?= isset($link)?$link:'' ?>"><?= isset($link_title)?$link_title:'لینک ' ?></a>


