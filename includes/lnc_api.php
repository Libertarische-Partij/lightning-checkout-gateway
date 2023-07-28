<?php

namespace LightningCheckoutPlugin;

class LightningCheckoutAPI {

    protected $lnc_url;
    protected $webhook_url;
    protected $api_key;
    protected $curlWrapper;

    const HTTP_OK = 200;
    const HTTP_MULTIPLE_CHOICES = 300;

    public function __construct($api_key, $lnc_url, $webhook_url) {
        $this->api_key = $api_key;
        $this->lnc_url = $lnc_url;
        $this->webhook_url = $webhook_url;
        $this->curlWrapper = new CurlWrapper();
    }

    public function generateInvoice($amount, $memo) {
        $data = array(
            "out" => false,
            "amount" => $amount,
            "memo" => $memo,
            "unit" => "eur",
            "webhook" => $this->webhook_url
        );
        $headers = array(
            'accept' => 'application/json',
            'X-API-KEY' => $this->api_key,
            'Content-Type' => 'application/json'
        );        
        $result = $this->curlWrapper->post($this->lnc_url.'/api/v1/payments', array(), $data, $headers);

        if ($result['status'] < self::HTTP_OK || $result['status'] >= self::HTTP_MULTIPLE_CHOICES) {
            throw new \Exception('Could not generate lightning invoice, status: ' . $result['status']);
        }

        return $result['response'];
    }

    public function checkPayment($payment_hash) {
        $headers = array(
            'accept' => 'application/json',
            'X-API-KEY' => $this->api_key
        );      
        $result = $this->curlWrapper->get($this->lnc_url.'/api/v1/payments/'.$payment_hash, array(), $headers);
        
        if ($result['status'] < self::HTTP_OK || $result['status'] >= self::HTTP_MULTIPLE_CHOICES) {
            throw new \Exception('Could not check payment status, status: ' . $result['status']);
        }

        return $result['response'];
    }
}
