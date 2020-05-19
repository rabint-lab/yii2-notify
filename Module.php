<?php

# THIS FILE ISDISABLED 

//EXIT;
namespace rabint\notify;

/**
 * notify module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'rabint\notify\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function registerEvent()
    {
//        \yii\base\Event::on(\yii\web\Controller::className(), \yii\web\Controller::EVENT_AFTER_ACTION, function ($event) {
//            die('ma kheyli bahalim');
//        });
//            \yii\base\Event::on(\yii\web\Response::className(), \yii\web\Response::EVENT_AFTER_SEND, function ($event) {
//                \sahifedp\stats\stats::stat();
//            });
//        \Yii::$app->view->on(\yii\web\View::EVENT_END_PAGE, function () {
//            \sahifedp\stats\stats::stat();
//        });
//        \Yii::$app->on(\app\modules\payment\controllers\PaymentController::EVENT_AFTER_PAY_SUCCESS,
//            [controllers\TranslateController::className(), 'afterPay']
//        );
    }

    public static function adminMenu()
    {

        return [
            [
                'label' => \Yii::t('app', 'اعلانات'),
                'icon' => '<i class="fas fa-bell"></i>',
                'url' => '#',
                'options' => ['class' => 'treeview'],
                'visible' => \rabint\helpers\user::can('manager'),
                'items' => [
                    ['label' => \Yii::t('app', 'مدیریت اعلانات'), 'url' => ['/notify/admin'], 'icon' => '<i class="far fa-circle"></i>'],
                ],
            ],
        ];
    }
}
