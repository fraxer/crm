<?php

namespace console\controllers;

use yii\console\Controller;
use backend\components\UisDataApi;
use backend\components\UisDataAdapter;
use yii\base\Exception;

class CallController extends Controller {

    public function actionLastFiveMin()
    {
        try {
            $accessToken = \Yii::$app->params['UisApiToken'];

            $uisDataApi = new UisDataApi($accessToken);

            $lastCalls = $uisDataApi->getCallsReportLastFiveMin();

            $lastCalls = json_decode($lastCalls, true);

            $callSessionIds = UisDataAdapter::fetchEntityField($lastCalls['result']['data'], function ($item) {
                return $item['id'];
            });

            $cdrs = $uisDataApi->getCallLegsReportByIds($callSessionIds);

            $cdrs = json_decode($cdrs, true);

            $mergedData = UisDataAdapter::mergeCallsWithCdrs($lastCalls['result']['data'], $cdrs['result']['data']);

            $preparedCalls = UisDataAdapter::parseCallsReport($mergedData);

            \Yii::debug($preparedCalls);

        } catch(Exception $e) {
            \Yii::error($e->getMessage());
        }
    }
}