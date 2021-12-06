<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m211204_185416_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->comment('Логин'),
            'first_name' => $this->string(120)->comment('Имя'),
            'last_name' => $this->string(120)->comment('Фамилия'),
            'patronymic' => $this->string(60)->comment('Отчество'),
            'birthday' => $this->date()->comment('День рождения'),
            'photo' => $this->string()->comment('Изображение'),
            'city'  => $this->string(83)->comment('Город'),
            'auth_key' => $this->string(32)->notNull()->comment('Ключ аутентификации cookies'),
            'password' => $this->string()->notNull()->comment('Пароль'),
            'password_reset_token' => $this->string()->unique()->comment('Токен сброса пароля'),
            'verification_token' => $this->string()->unique()->comment('Токен подтверждения почты'),
            'email' => $this->string()->notNull()->unique()->comment('Email'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Статус'),
            'created_at' => $this->dateTime()->comment('Дата создания'),
            'updated_at' => $this->dateTime()->comment('Дата изменения'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
