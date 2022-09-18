<?php

namespace backend\modules\checkSite\commands;

use backend\modules\checkSite\models\CheckSite;
use backend\modules\checkSite\models\CheckSiteStatus;
use backend\modules\checkSite\helpers\SiteAvailability;
use yii\console\Controller;
use yii\helpers\Console;

class AvailabilityController extends Controller {

    public function actionCheck()
    {
        $items = CheckSite::find()->all();

        foreach ($items as $item) {
            if ($item->actualStatus) {
                $time = strtotime($item->actualStatus->checked_at);

                if ($time % $item->period_checking != 0) {
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