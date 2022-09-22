<?php

namespace backend\modules\checkSite\models;

use Yii;
use backend\modules\checkSite\models\CheckSiteStatus;

/**
 * This is the model class for table "check_site".
 *
 * @property int $id
 * @property string $domain
 * @property string $parent_id
 * @property string $created_at
 */
class CheckSite extends \yii\db\ActiveRecord
{
    public $childs = [];

    public $sectionStatus = 200;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'check_site';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['domain'], 'required'],
            [['parent_id', 'period_checking'], 'integer'],
            [['id', 'parent_id'], 'default', 'value' => 0],
            [['created_at'], 'safe'],
            [['domain'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain' => 'Domain',
            'parent_id' => 'Parent Id',
            'period_checking' => 'Period of checking (min)',
            'created_at' => 'Created At',
        ];
    }

    public function getStatuses()
    {
        return $this->hasMany(CheckSiteStatus::class, ['site_id' => 'id'])->orderBy('id desc')->limit(24);
    }

    public function getActualStatus()
    {
        return $this->hasOne(CheckSiteStatus::class, ['site_id' => 'id'])->orderBy('id desc')->select([
            '{{check_site_status}}.*',
            'period_diff' => '(UNIX_TIMESTAMP() - UNIX_TIMESTAMP(checked_at))',
        ]);
    }
}
