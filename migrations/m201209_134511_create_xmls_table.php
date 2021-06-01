<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%xml}}`.
 */
class m201209_134511_create_xmls_table extends Migration
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

        $this->createTable('{{%xmls}}', [
            'xml_name' => $this->string(16),
            'targets' => $this->text(),
            'body' => $this->text(),
            'PRIMARY KEY(xml_name)',
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-xml-xml_name}}',
            '{{%xmls}}',
            'xml_name',
            '{{%products}}',
            'xml',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%xmls}}');
    }
}
