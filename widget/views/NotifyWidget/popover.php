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
<?php
$icon = ($notReadCount == 0) ? 'fa-bell-o' : 'fa-bell';
$label = '<i class="fa ' . $icon . '"></i>
    <span class="unseen-count" ';
$label .= ($notReadCount == 0) ? 'style="display:none"' : '';
$label .= '>' . \rabint\locality::faNumber($notReadCount) . '</span>';
?>

<?php
WebUIPopover::begin([
    'label' => $label,
    'tagName' => 'a',
    'tagOptions' => [
        'class' => 'login-link',
        'title' => \Yii::t('app', 'اعلان ها'),
        'data-placement' => "auto",
//        'data-toggle' => "tooltip"
    ],
    'pluginOptions' => [
        'placement' => 'bottom-left',
        'animation' => 'pop', //fade
        'title' => \Yii::t('app', 'اعلان های شما') . Html::a(\Yii::t('app', 'همه اعلان ها').' <i class="fas fa-chevron-circle-left"></i>', ['/notify/default/index'], ['class' => 'popoverMoreLink', 'title' => \Yii::t('app', 'همه اعلان ها')]),
//        'onShow' => 'function($element) {console.log($element);}',
        'direction' => 'rtl',
        'container' => '.master-header',
//        'backdrop' => true,
        'width' => '300',
        'padding' => false,
    ],
]);
?>

<div class="notificationpopOverContent popoverContent">
    <?php
    if (empty($items)) {
        echo \Yii::t('app', 'تا کنون اعلانی برای شما ارسال نشده است.');
    }
    foreach ($items as $item) {
        $notReadCount += ($item->read == 0) ? 1 : 0;
        ?>
        <div class="messageItem noImg <?= ($item->read == 0) ? 'notRead' : ''; ?>">
            <div class="pmDetial">
                <a href="<?= Url::to(['/notify/default/view', 'id' => $item->id]); ?>" class="pmTitle">
                    <?= $item->content; ?>
                </a>    
                <span class="pmTime">
                    <?= rabint\locality::jdate('j F Y H:i', $item->created_at); ?>
                </span>
            </div>
        </div>
    <?php } ?>
</div>
<?php WebUIPopover::end(); ?>