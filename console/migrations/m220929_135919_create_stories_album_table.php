<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%stories_album}}`.
 */
class m220929_135919_create_stories_album_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%stories_album}}', [
            'id' => $this->bigPrimaryKey(),
            'name' => $this->string()->notNull()->defaultValue(''),
            'image' => $this->string()->notNull()->defaultValue(''),
            'rank' => $this->integer()->notNull()->defaultValue(1),
            'created_at' => $this->datetime()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%stories_album}}');
    }
}
