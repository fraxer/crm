<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%stories_salon}}`.
 */
class m221010_132910_create_stories_salon_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%stories_salon}}', [
            'id' => $this->bigPrimaryKey(),
            'name' => $this->string()->notNull()->defaultValue(''),
            'created_at' => $this->datetime()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%stories_salon}}');
    }
}
