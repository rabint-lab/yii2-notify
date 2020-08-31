<?php

namespace  rabint\notify\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "user_notification".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $expire_at
 * @property string $link
 * @property string $content
 * @property integer $seen
 * @property integer $media
 * @property integer $send_status
 *
 * @property User $user
 */
class Notification extends \yii\db\ActiveRecord
{

    const SCENARIO_SEEN = 'seen';
    /* seen status */
    const SEEN_STATUS_NO = 0;
    const SEEN_STATUS_YES = 1;
    /* Preority */
    const MEDIA_MUTE = 0;
    const MEDIA_EMAIL = 1;
    const MEDIA_SMS = 2;
    const MEDIA_NOTIFICATION = 4;
    const MEDIA_EMAIL_AND_SMS = 3;
    const MEDIA_EMAIL_AND_NOTIFICATION = 5;
    const MEDIA_SMS_AND_NOTIFICATION = 6;
    const MEDIA_ALL = 7;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_notification';
    }

    public static function seenStatuses()
    {
        return [
            static::SEEN_STATUS_NO => ['title' => \Yii::t('app', 'خوانده نشده'), 'class' => 'info'],
            static::SEEN_STATUS_YES => ['title' => \Yii::t('app', 'خوانده شده'), 'class' => 'warning'],
        ];
    }

    public static function priorities()
    {
        return [
            static::MEDIA_MUTE => ['title' => \Yii::t('app', 'فقط اعلان'), 'class' => 'default'],
            static::MEDIA_EMAIL => ['title' => \Yii::t('app', 'ایمیل'), 'class' => 'success'],
            static::MEDIA_SMS => ['title' => \Yii::t('app', 'پیامک'), 'class' => 'warning'],
            static::MEDIA_NOTIFICATION => ['title' => \Yii::t('app', 'نمایش در پنل کاربری'), 'class' => 'primary'],
            static::MEDIA_EMAIL_AND_SMS => ['title' => \Yii::t('app', 'ایمیل و پیامک'), 'class' => 'danger'],
            static::MEDIA_EMAIL_AND_NOTIFICATION => ['title' => \Yii::t('app', 'ایمیل و نمایش در پنل کاربری'), 'class' => 'success'],
            static::MEDIA_SMS_AND_NOTIFICATION => ['title' => \Yii::t('app', 'پیامک و نمایش در پنل کاربری '), 'class' => 'warning'],
            static::MEDIA_ALL => ['title' => \Yii::t('app', 'همه موارد'), 'class' => 'danger'],
        ];
    }

    /* ====================================================================== */

    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => FALSE,
                'value' => time(),
            ],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEEN] = ['seen', 'updated_at'];
        return $scenarios;
    }

    /* ====================================================================== */

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['user_id', 'created_at', 'updated_at', 'expire_at', 'seen', 'media', 'send_status'], 'integer'],
            [['content'], 'string'],
            [['link'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' =>  User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['content', 'link'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'شناسه'),
            'user_id' => Yii::t('app', 'کاربر'),
            'created_at' => Yii::t('app', 'تاریخ ایجاد'),
            'updated_at' => Yii::t('app', 'تاریخ خواندن'),
            'expire_at' => Yii::t('app', 'تاریخ انقضاء'),
            'link' => Yii::t('app', 'لینک'),
            'content' => Yii::t('app', 'متن'),
            'seen' => Yii::t('app', 'خوانده شده'),
            'media' => Yii::t('app', 'نحوه ارسال'),
            'send_status' => Yii::t('app', 'وضعیت ارسال'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
