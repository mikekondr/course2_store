<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m240418_180159_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'fullname' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'role' => $this->string()->notNull(),
        ]);

        $this->batchInsert('{{%users}}', ['username', 'fullname', 'password', 'role', 'created_at', 'updated_at'], [
            ['manager', 'Manager', Yii::$app->getSecurity()->generatePasswordHash('manager'), 'manager', time(), time()],
            ['storekeeper', 'Storekeeper', Yii::$app->getSecurity()->generatePasswordHash('storekeeper'), 'storekeeper', time(), time()],
            ['client', 'Client', Yii::$app->getSecurity()->generatePasswordHash('client'), 'client', time(), time()],
            ['guest', 'Guest', Yii::$app->getSecurity()->generatePasswordHash('guest'), 'guest', time(), time()],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
