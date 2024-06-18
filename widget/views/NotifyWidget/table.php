<?php

use yii\helpers\Url;
use kartik\popover\PopoverX;
use yii\helpers\Html;
use kittools\webuipopover\WebUIPopover;

/* @var $items[] app\modules\notify\models\Notification */
/* @var $this yii\web\View */
$notReadCount = 0;
foreach ($items as $item) {
    $notReadCount += ($item->seen == 0) ? 1 : 0;
}
?>


<div class="notificationpopOverContent">
    <?php
    if (empty($items)) {
        echo \Yii::t('app', 'تا کنون اعلانی برای شما ارسال نشده است.');
    }
    foreach ($items as $item) {
        $notReadCount += ($item->seen == 0) ? 1 : 0;
        ?>
        <div class="messageItem noImg <?= ($item->seen == 0) ? 'notRead' : ''; ?>">
            <div class="pmDetial">
                <a href="<?= Url::to(['/notify/default/view', 'id' => $item->id]); ?>" class="pmTitle modalButton">
                    <?= $item->content; ?>
                </a>
                <br/>
                <span class="pmTime text-muted" >
                    <?= \rabint\helpers\locality::jdate('j F Y - H:i', $item->created_at); ?>
                </span>
            </div>
        </div>
    <?php } ?>
</div>
<style>
    .pmDetial {
        width: 100%;
        border-bottom: 1px dotted #ccc;
        padding: 10px 0;
    }
</style>
<?php
\Yii::$app->view->on(\yii\web\View::EVENT_END_BODY, function () {

    yii\bootstrap5\Modal::begin([
        'id' => 'modal',
        'size' => 'modal-lg',
        'title' => \Yii::t('app','نمایش اعلان'),
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => TRUE]
    ]);
    echo '<div id="modalContent"></div>';
    yii\bootstrap5\Modal::end();

});
?>

<script>
<?php ob_start(); ?>
    $(document).ready(function() {
        $('.modalButton').click(function(e){
            e.preventDefault();
            var self = $(this); // Store the current button for later use
            $('#modalContent', '#modal').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i></div>'); // Replace the loading icon with the fetched data
            $('#modal').modal('show'); // Show the modal with loading icon
            $.get(self.attr('href'), function(data) {
                $('#modalContent', '#modal').html(data); // Replace the loading icon with the fetched data
                //$('#modal').modal('hide'); // Optionally hide the modal after showing the data
            });
            return false;
        });
      });
    <?php
    $script = ob_get_clean();
    $this->registerJs($script, $this::POS_END);
    ?>
</script>
