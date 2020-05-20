<?php

namespace rabint\notify\components;

use rabint\services\sms\sms;
use common\models\User;
use rabint\notify\models\Notification;

//use ElephantIO\Client;
//use ElephantIO\Engine\SocketIO\Version2X;

class notify extends \yii\base\Component
{


    public function send($user_id, $message, $link = '', $params = [])
    {

        /* ------------------------------------------------------ */
        $notify = new Notification();
        $notify->user_id = $user_id;
        $notify->media = isset($params['media']) ? $params['media'] : Notification::MEDIA_MUTE;
        $notify->expire_at = !isset($params['expire_time']) ? NULL : time() + $params['expire_time'];
        $notify->link = (!empty($link)) ? \yii\helpers\Url::to($link, TRUE) : NULL;
        $notify->content = $message;
        if ($notify->save(false)) {
            $this->process($notify);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function sendVerify($user_id, $token, $link = '', $params = [])
    {

        /* ------------------------------------------------------ */
        $notify = new Notification();
        $notify->user_id = $user_id;
        $notify->media = isset($params['media']) ? $params['media'] : Notification::MEDIA_MUTE;
        $notify->expire_at = !isset($params['expire_time']) ? NULL : time() + $params['expire_time'];
        $notify->link = (!empty($link)) ? \yii\helpers\Url::to($link, TRUE) : NULL;
        $notify->content = $token;
        if ($notify->save(false)) {


            //todo add meta and processVerify
            //$this->processVerify($notify);

            $user = User::findOne($notify->user_id);
            $mobile = ($user->userProfile) ? $user->userProfile->cell : '';
            $email = ($user->email) ? $user->email : '';
            if ($notify->media & Notification::MEDIA_SMS) {
                if ($mobile) {
                    $res = sms::sendVerify($mobile, $token);
                    $notify->send_status = $notify->send_status | Notification::MEDIA_SMS;
                    $notify->save(false);
                }
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     *
     * @param Notification $notify
     * @return boolean
     */
    protected function process($notify)
    {

        $user = User::findOne($notify->user_id);
        $mobile = ($user->userProfile) ? $user->userProfile->cell : '';
        $email = ($user->email) ? $user->email : '';


        if ($notify->media & Notification::MEDIA_SMS) {
            if ($mobile) {
                sms::send($mobile, $notify->content);
                $notify->send_status = $notify->send_status | Notification::MEDIA_SMS;
                $notify->save(false);
            }
        }
        if ($notify->media & Notification::MEDIA_EMAIL) {
            if ($email) {
                //var_dump($email);
                \Yii::$app->mailer->compose('notify', [
                    'link' => ($notify->link),
                    'text' => ($notify->content),
                ])
                    ->setFrom('info@mail.com')
                    ->setTo($email)
                    ->setSubject(config('app_name'))
                    ->send();

                $notify->send_status = $notify->send_status | Notification::MEDIA_EMAIL;
                $notify->save(false);
            }
        }
        if ($notify->media & Notification::MEDIA_NOTIFICATION) {
            $notify->send_status = $notify->send_status | Notification::MEDIA_NOTIFICATION;
            $notify->save(false);
        }
        return TRUE;
    }
}
