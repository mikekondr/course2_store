<?php

use yii\db\Migration;

/**
 * Class m240531_162809_crate_documents_tables
 */
class m240531_162809_crate_documents_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%documents}}', [
            'id' => $this->primaryKey(),
            'doc_type' => $this->integer(1)->notNull()->comment('1-input, 2-output, 3-order'),
            'doc_state' => $this->boolean()->notNull()->defaultValue(0)->comment('True-active, False-draft'),
            'doc_date' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'counterparty' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%document_rows}}', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer()->notNull(),
            'good_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
        ]);

        $this->createIndex('{{%idx-document_rows-document_id}}', '{{%document_rows}}', 'document_id');
        $this->addForeignKey('fk_document_rows-document_id', '{{%document_rows}}', 'document_id', '{{%documents}}', 'id', 'CASCADE');

        $this->addForeignKey('fk_document_author_id', '{{%documents}}', 'author_id', '{{%users}}', 'id', 'CASCADE');

        $this->addColumn('{{%consignments}}', 'document_id', $this->integer()->notNull());
        $this->addForeignKey('fk_consignments-document_id', '{{%consignments}}', 'document_id', '{{%documents}}', 'id', 'CASCADE');

        $this->addColumn('{{%remains}}', 'document_id', $this->integer()->notNull());
        $this->addForeignKey('fk_remains-document_id', '{{%remains}}', 'document_id', '{{%documents}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_remains-document_id', '{{%remains}}');
        $this->dropColumn('{{%remains}}', 'document_id');

        $this->dropForeignKey('fk_consignments-document_id', '{{%consignments}}');
        $this->dropColumn('{{%consignments}}', 'document_id');

        $this->dropForeignKey('fk_document_rows-document_id', '{{%document_rows}}');
        $this->dropForeignKey('fk_document_author_id', '{{%documents}}');

        $this->dropTable('{{%document_rows}}');
        $this->dropTable('{{%documents}}');
    }
}
