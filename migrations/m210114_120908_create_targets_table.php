<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%targets}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%products}}`
 */
class m210114_120908_create_targets_table extends Migration
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
        $this->createTable('{{%targets}}', [
            'id' => $this->primaryKey(),
            'konf_id' => $this->integer()->notNull(),
            'target' => $this->string()->notNull(),
        ], $tableOptions);

        // creates index for column `konf_id`
        $this->createIndex(
            '{{%idx-targets-konf_id}}',
            '{{%targets}}',
            'konf_id'
        );

        // add foreign key for table `{{%products}}`
        $this->addForeignKey(
            '{{%fk-targets-konf_id}}',
            '{{%targets}}',
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
        // drops foreign key for table `{{%products}}`
        $this->dropForeignKey(
            '{{%fk-targets-konf_id}}',
            '{{%targets}}'
        );

        // drops index for column `konf_id`
        $this->dropIndex(
            '{{%idx-targets-konf_id}}',
            '{{%targets}}'
        );

        $this->dropTable('{{%targets}}');
    }
}
