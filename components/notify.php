<?php

namespace rabint\notify\components;

use common\models\User;
use rabint\helpers\str;
use rabint\notify\models\Notification;
use rabint\services\sms\sms;
use Yii;

//use ElephantIO\Client;
//use ElephantIO\Engine\SocketIO\Version2X;

class notify extends \yii\base\Component
{


    public function send($user_id, $message, $link = '', $params = [])
    {

        /* ------------------------------------------------------ */
        $notify = new Notification();
        $notify->user_id = $user_id;
        if (!isset($params['media']) && isset($params['priority'])) {
            $notify->media = isset($params['priority']) ? $params['priority'] : Notification::MEDIA_MUTE;
        } else {
            $notify->media = isset($params['media']) ? $params['media'] : Notification::MEDIA_MUTE;
        }
        //$notify->media = isset($params['media']) ? $params['media'] : Notification::MEDIA_MUTE;
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


    /**
     * @param $user_id
     * @param $template
     * @param $params
     * @param $link
     * @param $options
     * @return bool
     * @throws \yii\db\Exception
     */
    public function sendTemplate($user_id, $template, $param1, $param2 = null, $param3 = null, $link = '', $options = [])
    {
        $notify = new Notification();
        $notify->user_id = $user_id;
        if (!isset($options['media']) && isset($options['priority'])) {
            $notify->media = isset($options['priority']) ? $options['priority'] : Notification::MEDIA_MUTE;
        } else {
            $notify->media = isset($options['media']) ? $options['media'] : Notification::MEDIA_MUTE;
        }
        $notify->expire_at = !isset($options['expire_time']) ? NULL : time() + $options['expire_time'];
        $notify->link = (!empty($link)) ? \yii\helpers\Url::to($link, TRUE) : NULL;

        $content = config('SERVICE.notify.templates.' . $template, 'کاربر گرامی! کد فعال‌سازی شما {param1} می‌باشد.');

        $notify->content = Yii::t('app', $content, ['param1' => $param1, 'param2' => $param2, 'param3' => $param3]);
        if ($notify->save(false)) {
            $user = User::findOne($notify->user_id);
            if ($notify->media & Notification::MEDIA_SMS) {
                $mobile = !empty($user->mobile) ? $user->mobile : (($user->userProfile) ? $user->userProfile->cell : '');
                if ($mobile) {
                    $res = sms::sendVerify($mobile, $param1, $param2 = null, $param3 = null, $template);
                    $notify->send_status = $res ? ($notify->send_status | Notification::MEDIA_SMS) : $notify->send_status;
                    $notify->save(false);
                }
            }
            if ($notify->media & Notification::MEDIA_EMAIL) {
                $email = ($user->email) ? $user->email : '';
                if ($email) {
                    //todo: send email
                }
            }
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
        if (!isset($params['media']) && isset($params['priority'])) {
            $notify->media = isset($params['priority']) ? $params['priority'] : Notification::MEDIA_MUTE;
        } else {
            $notify->media = isset($params['media']) ? $params['media'] : Notification::MEDIA_MUTE;
        }
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
                $mobile = str::CellphoneSanitise($mobile, "0");
                sms::send($mobile, $notify->content);
                $notify->send_status = $notify->send_status | Notification::MEDIA_SMS;
                $notify->save(false);
            }
        }
        if ($notify->media & Notification::MEDIA_EMAIL) {
            if ($email) {
                //var_dump($email);
                Yii::$app->mailer->compose('@common/mail/notify', [
                    'link' => ($notify->link),
                    'text' => ($notify->content),
                ])
                    ->setFrom(config('noreplyEmail'))
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
