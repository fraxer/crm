<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%check_site_status}}`.
 */
class m220910_110244_create_check_site_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%check_site_status}}', [
            'id' => $this->bigPrimaryKey(),
            'site_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'checked_at' => $this->datetime()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%check_site_status}}');
    }
}
