<?php

/**
 * notify widget
 * @author Mojtaba Akbarzadeh <ingenious@chmail.com>
 * @copyright (c) rabint, rabint data producers
 */

namespace frontend\modules\notify\widget;

use  rabint\notify\models\Notification;

/**
 * Class login
 */
class NotifyWidget extends \yii\bootstrap\Widget {

    var $style = 'default';
    var $menuClass = 'userNotification';
    var $timeLimit = FALSE;
    var $count = 10;

    public function run() {
        $items = Notification::find()
                ->andWhere([
            'user_id' => \rabint\user::id(),
//            'seen' => Notification::SEEN_STATUS_NO,
        ]);
        if ($this->timeLimit) {
            $items->andWhere(['>=', 'created_at', time() - $this->timeLimit]);
        }
        $items->limit($this->count);
        $items->orderBy(['seen' => SORT_ASC, 'media' => SORT_DESC, 'created_at' => SORT_DESC]);
        $items = $items->all();
        return $this->render('NotifyWidget/' . $this->style, [
                    'menuClass' => $this->menuClass,
                    'items' => $items,
        ]);
    }

}
