<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%konfs}}`
 */
class m201202_094029_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'konf_id' => $this->integer()->notNull(),
            'konf_version' => $this->string(16),
            'redaction_num' => $this->integer(11),
            'platform_version' => $this->integer(11),
            'txt' => $this->string(16),
            'htm' => $this->string(16),
            'xml' => $this->string(16),
            'zip' => $this->string(16),
        ], $tableOptions);

        $this->createIndex(
            '{{%idx-products-konf_id}}',
            '{{%products}}',
            'konf_id'
        );

        $this->createIndex(
            '{{%idx-products-txt}}',
            '{{%products}}',
            'txt'
        );
        $this->createIndex(
            '{{%idx-products-htm}}',
            '{{%products}}',
            'htm'
        );
        $this->createIndex(
            '{{%idx-products-xml}}',
            '{{%products}}',
            'xml'
        );

        $this->addForeignKey(
            '{{%fk-products-konf_id}}',
            '{{%products}}',
            'konf_id',
            '{{%konfs}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%konfs}}`
        $this->dropForeignKey(
            '{{%fk-products-konf_id}}',
            '{{%products}}'
        );

        // drops index for column `konf_id`
        $this->dropIndex(
            '{{%idx-products-konf_id}}',
            '{{%products}}'
        );

        $this->dropTable('{{%products}}');
    }
}
