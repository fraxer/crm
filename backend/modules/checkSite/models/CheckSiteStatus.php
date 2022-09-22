<?php

namespace backend\modules\checkSite\models;

use Yii;

/**
 * This is the model class for table "check_site_status".
 *
 * @property int $id
 * @property int $site_id
 * @property int $status
 * @property string $checked_at
 */
class CheckSiteStatus extends \yii\db\ActiveRecord
{
    public $period_diff;

    public function fields()
    {
        $fields = parent::fields();
        $fields['period_diff'] = 'period_diff';

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'check_site_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['site_id'], 'required'],
            [['site_id', 'status'], 'integer'],
            [['checked_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_id' => 'Site ID',
            'status' => 'Status',
            'checked_at' => 'Checked At',
        ];
    }
}
