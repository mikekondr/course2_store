<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%goods}}`.
 */
class m240419_195122_create_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%goods}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull()->defaultValue(''),
            'vendor' => $this->string(50)->notNull()->defaultValue(''),
            'category_id' => $this->integer()->defaultValue(null),
            'expiry' => $this->integer()->notNull()->defaultValue(0)->comment('in days'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_goods_category_id', '{{%goods}}', 'category_id', '{{%categories}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%goods}}');
    }
}
