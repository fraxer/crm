<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%stories_photo}}`.
 */
class m220929_135932_create_stories_photo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%stories_photo}}', [
            'id' => $this->bigPrimaryKey(),
            'album_id' => $this->bigInteger(),
            'image' => $this->string()->notNull()->defaultValue(''),
            'thumb' => $this->string()->notNull()->defaultValue(''),
            'rank' => $this->integer()->notNull()->defaultValue(1),
            'duration' => $this->integer()->notNull()->defaultValue(10),
            'created_at' => $this->datetime()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%stories_photo}}');
    }
}
