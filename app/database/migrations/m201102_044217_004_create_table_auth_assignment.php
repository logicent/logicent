<?php

use yii\db\Migration;

class m201102_044217_004_create_table_auth_assignment extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%auth_assignment}}',
            [
                'item_name' => $this->string(64)->notNull(),
                'user_id' => $this->string(64)->notNull(),
                'created_at' => $this->integer(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%auth_assignment}}', ['item_name', 'user_id']);
    }

    public function down()
    {
        $this->dropTable('{{%auth_assignment}}');
    }
}
