<?php

use yii\db\Schema;
use yii\db\Migration;

class m150526_040514_document_init extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%document}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'deleted_at' => Schema::TYPE_INTEGER . ' NULL',
        ], $tableOptions);

        $this->createTable('{{%attachment}}', [
            'id' => Schema::TYPE_PK,
            'filename' => Schema::TYPE_STRING . ' NOT NULL',
            'size' => Schema::TYPE_INTEGER . ' NOT NULL',
            'document_id'=> Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'deleted_at' => Schema::TYPE_INTEGER . ' NULL',
        ], $tableOptions);
        $this->createIndex('document_id','{{%attachment}}','document_id');
    }
    
    public function safeDown()
    {
        $this->dropTable('{{%document}}');
        $this->dropTable('{{%attachment}}');

    }
}
