<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%remains}}`.
 */
class m240531_153212_create_remains_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%consignments}}', [
            'id' => $this->primaryKey(),
            'good_id' => $this->integer()->notNull(),
            'price' => $this->decimal(10,2)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%remains}}', [
            'id' => $this->primaryKey(),
            'good_id' => $this->integer()->notNull(),
            'consignment_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull(),
        ]);

        $this->createIndex('uk_good_id_remains', '{{%remains}}', ['good_id', 'consignment_id'], true);

        $this->addForeignKey('fk_remains_consignment_id', '{{%remains}}', 'consignment_id', '{{%consignments}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_remains_consignment_id', '{{%remains}}');
        $this->dropTable('{{%remains}}');
        $this->dropTable('{{%consignments}}');
    }
}
