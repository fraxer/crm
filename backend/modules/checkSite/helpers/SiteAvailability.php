<?php

namespace backend\modules\checkSite\helpers;

use backend\modules\checkSite\models\CheckSite;

class SiteAvailability
{
    private $domain;

    private $url;

    private $numberAttempts = 0;

    public function __construct($domain)
    {
        $this->domain = $domain;

        $this->setUrl('http://' . $this->domain);
    }

    public function check()
    {
        $this->numberAttempts++;

        if ($this->numberAttempts >= 10) {
            return 301;
        }

        $responseHeaders = $this->sendRequest();

        if (!count($responseHeaders)) {
            return 404;
        }

        $httpCode = $responseHeaders['http_code'];

        if ($httpCode == 0) $httpCode == 404;

        if (in_array($httpCode, [301, 302, 307, 308])) {
            $this->setUrl($responseHeaders['redirect_url']);

            return $this->check();
        }

        return $httpCode;
    }

    private function setUrl($url)
    {
        $this->url = $url;
    }

    private function sendRequest()
    {
        $ch = curl_init($this->url);

        $headers = [
            'Cache-Control: no-cache',
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36',
        ];

        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $ret = curl_exec($ch);

        if (curl_errno($ch)) {
            \Yii::error(curl_error($ch));

            curl_close($ch);

            return [];
        }

        $headers = curl_getinfo($ch);

        curl_close($ch);

        return $headers;
    }
}
