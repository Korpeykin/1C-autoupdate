<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%konfs}}`.
 */
class m201202_051958_create_konfs_table extends Migration
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
        $this->createTable('{{%konfs}}', [
            'id' => $this->primaryKey(),
            'provider_name' => $this->string(16),
            'conf_name' => $this->string(16),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%konfs}}');
    }
}
