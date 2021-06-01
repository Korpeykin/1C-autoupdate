<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%txt}}`.
 */
class m201210_143900_create_txts_table extends Migration
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
        $this->createTable('{{%txts}}', [
            'txt_name' => $this->string(16),
            'body' => $this->text(),
            'PRIMARY KEY(txt_name)',
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-txt-txt_name}}',
            '{{%txts}}',
            'txt_name',
            '{{%products}}',
            'txt',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%txts}}');
    }
}
