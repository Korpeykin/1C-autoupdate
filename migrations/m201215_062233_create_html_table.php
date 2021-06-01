<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%htm}}`.
 */
class m201215_062233_create_html_table extends Migration
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
        $this->createTable('{{%html}}', [
            'htm_name' => $this->string(16),
            'body' => $this->text(),
            'PRIMARY KEY(htm_name)',
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-htm-htm_name}}',
            '{{%html}}',
            'htm_name',
            '{{%products}}',
            'htm',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%html}}');
    }
}
