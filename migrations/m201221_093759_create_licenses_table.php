<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%licenses}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%konfs}}`
 */
class m201221_093759_create_licenses_table extends Migration
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
        $this->createTable('{{%licenses}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'konf_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'life_time' => $this->integer()->notNull(),
            'login' => $this->string()->notNull(),
            'password_hash' => $this->string()->notNull(),
        ], $tableOptions);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-licenses-user_id}}',
            '{{%licenses}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-licenses-user_id}}',
            '{{%licenses}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `konf_id`
        $this->createIndex(
            '{{%idx-licenses-konf_id}}',
            '{{%licenses}}',
            'konf_id'
        );

        // add foreign key for table `{{%konfs}}`
        $this->addForeignKey(
            '{{%fk-licenses-konf_id}}',
            '{{%licenses}}',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-licenses-user_id}}',
            '{{%licenses}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-licenses-user_id}}',
            '{{%licenses}}'
        );

        // drops foreign key for table `{{%konfs}}`
        $this->dropForeignKey(
            '{{%fk-licenses-konf_id}}',
            '{{%licenses}}'
        );

        // drops index for column `konf_id`
        $this->dropIndex(
            '{{%idx-licenses-konf_id}}',
            '{{%licenses}}'
        );

        $this->dropTable('{{%licenses}}');
    }
}
