<?php

namespace backend\components;

use yii\console\Controller;
use yii\base\Exception;

class UisDataApi {

    private $version = 'v2.0';

    private $host = 'dataapi.uiscom.ru';

    private $accessToken;

    private $payload;

    private $jsonrpc = '2.0';

    private $method;

    private $params;

    const FIVE_MINUTE_IN_SECONDS = 300;

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getCallsReportLastFiveMin()
    {
        $this->setMethod('get.calls_report');

        $this->setParams([
            'date_from' => date('Y-m-d H:i:s', time()),
            'date_till' => date('Y-m-d H:i:s', time() + self::FIVE_MINUTE_IN_SECONDS),
            'sort' => [
                [
                    'field' => 'start_time',
                    'order' => 'desc'
                ]
            ],
            'fields' => [
                'id',
                'source',
                'is_lost',
                'direction',
                'start_time',
                'finish_time',
                'finish_reason',
                'talk_duration',
                'wait_duration',
                'total_duration',
                'clean_talk_duration',
                'total_wait_duration',
                'virtual_phone_number',
                'employees',
                'communication_number',
                'full_record_file_link',
                'scenario_name',
            ]
        ]);

        return $this->sendRequest();
    }

    public function getCallLegsReportByIds($ids)
    {
        $this->setMethod('get.call_legs_report');

        $this->setParams([
            'date_from' => date('Y-m-d H:i:s', time()),
            'date_till' => date('Y-m-d H:i:s', time() + self::FIVE_MINUTE_IN_SECONDS),
            'filter' => [
                'field' => 'call_session_id',
                'operator' => 'in',
                'value' => $ids,
            ],
            'fields' => [
                'id',
                'call_session_id',
                'call_records',
                'start_time',
                'connect_time',
                'duration',
                'total_duration',
                'finish_reason',
                'finish_reason_description',
                'virtual_phone_number',
                'calling_phone_number',
                'called_phone_number',
                'direction',
                'is_transfered',
                'is_operator',
                'employee_id',
                'employee_full_name',
                'employee_phone_number',
                'employee_rating',
                'scenario_id',
                'scenario_name',
                'is_coach',
                'release_cause_code',
                'release_cause_description',
                'is_failed',
                'is_talked',
                'contact_id',
                'contact_full_name',
                'contact_phone_number',
            ]
        ]);

        return $this->sendRequest();
    }

    public function getEmployeesByIds($ids)
    {
        $this->setMethod('get.employees');

        $this->setParams([
            'filter' => [
                'field' => 'call_session_id',
                'operator' => 'in',
                'value' => $ids,
            ],
        ]);

        return $this->sendRequest();
    }

    public function getScenarios()
    {
        $this->setMethod('get.scenarios');

        $this->setParams([]);

        return $this->sendRequest();
    }

    private function setMethod($method)
    {
        $this->method = $method;
    }

    private function setParams($params)
    {
        $this->params = $params;
    }

    private function preparePayload()
    {
        $this->payload = [
            'jsonrpc' => $this->jsonrpc,
            'id' => uniqid('', true),
            'method' => $this->method,
            'params' => $this->params,
        ];

        $this->payload['params']['access_token'] = $this->accessToken;

        return json_encode($this->payload);
    }

    private function sendRequest()
    {
        $payload = $this->preparePayload();

        $ch = curl_init('https://' . $this->host . '/' . $this->version);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=UTF-8',
            'Content-Length: ' . strlen($payload)
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $message = curl_error($ch);
            curl_close($ch);
            throw new Exception($message);
        }

        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_status != 200) {
            curl_close($ch);
            throw new Exception('HTTP status not 200');
        }

        curl_close($ch);

        return $response;
    }
}