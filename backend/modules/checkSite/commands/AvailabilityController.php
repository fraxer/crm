<?php

namespace backend\modules\checkSite\commands;

use backend\modules\checkSite\models\CheckSite;
use backend\modules\checkSite\models\CheckSiteStatus;
use backend\modules\checkSite\helpers\SiteAvailability;
use yii\console\Controller;
use yii\helpers\Console;

class AvailabilityController extends Controller {

    public function actionCheckAll()
    {
        $this->checkSites(CheckSite::find()->all());
    }

    public function actionCheckOne($domain)
    {
        $this->checkSites(CheckSite::find()->where(['domain' => $domain])->all());
    }

    private function checkSites(Array& $items)
    {
        foreach ($items as $item) {
            if ($item->actualStatus) {
                if ($item->actualStatus->period_diff < $item->period_checking * 60) {
                    continue;
                }
            }

            $sa = new SiteAvailability($item->domain);
            
            $siteStatus = new CheckSiteStatus();
            $siteStatus->site_id = $item->id;
            $siteStatus->status = $sa->check();
            $siteStatus->save();

            $siteStatus = CheckSiteStatus::findOne($siteStatus->id);

            if (!$siteStatus) continue;

            $time = strtotime($siteStatus->checked_at);

            $date = date('Y-m-d H:i:s', $time - 86400 * 7);

            CheckSiteStatus::deleteAll(['<=', 'checked_at', $date]);
        }
    }
}