<?php

use yii\db\Migration;

class m190101_111234_create_table_notify extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_notification}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY')->comment('شناسه'),
            'user_id' => $this->integer(11)->comment('کاربر'),
            'created_at' => $this->integer(4)->unsigned()->comment('تاریخ ایجاد'),
            'updated_at' => $this->integer(4)->unsigned()->comment('تاریخ بروزرسانی'),
            'expire_at' => $this->integer(4)->unsigned()->comment('تاریخ انقضاء'),
            'link' => $this->string(190)->comment('پیوند مرتبط'),
            'content' => $this->text()->comment('متن'),
            'seen' => $this->tinyInteger(1)->comment('خونده شده'),
            'media' => $this->integer(11)->unsigned()->comment('نحوه ارسال'),
            'send_status' => $this->integer(11)->comment('وضعیت ارسال'),
            'meta' => $this->text()->comment('متا'),
        ], $tableOptions);

        $this->createIndex('fk_notify_user_idx', '{{%user_notification}}', 'user_id');
        $this->addForeignKey('fk_notify_user1', '{{%user_notification}}', 'user_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%user_notification}}');
    }
}
