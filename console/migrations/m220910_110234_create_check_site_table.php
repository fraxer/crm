<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%check_site}}`.
 */
class m220910_110234_create_check_site_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%check_site}}', [
            'id' => $this->bigPrimaryKey(),
            'domain' => $this->string()->notNull()->defaultValue(''),
            'parent_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'period_checking' => $this->integer()->notNull()->defaultValue(60),
            'created_at' => $this->datetime()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%check_site}}');
    }
}
