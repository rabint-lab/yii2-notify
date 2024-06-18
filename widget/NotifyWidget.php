<?php

/**
 * notify widget
 * @author Mojtaba Akbarzadeh <ingenious@chmail.com>
 * @copyright (c) rabint, rabint data producers
 */

namespace rabint\notify\widget;

use rabint\helpers\user;
use  rabint\notify\models\Notification;
use yii\base\Widget;

/**
 * Class login
 */
class NotifyWidget extends Widget {

    var $style = 'default';
    var $menuClass = 'userNotification';
    var $timeLimit = FALSE;
    var $count = 10;

    public function run() {
        $items = Notification::find()
                ->andWhere([
            'user_id' => user::id(),
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
