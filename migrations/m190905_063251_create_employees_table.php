<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employees}}`.
 */
class m190905_063251_create_employees_table extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->createTable('{{%employees}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'middle_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'birthday' => $this->date()->notNull(),
            'job_id' => $this->integer()->notNull(),
            'education' => $this->json()
        ]);

        $this->createIndex(
            'pk_employees',
            'employees',
            'id'
        );

        $this->addForeignKey(
            'fk_job_id',
            'employees',
            'job_id',
            'jobs',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employees}}');
    }
}
