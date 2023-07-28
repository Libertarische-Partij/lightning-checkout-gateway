<?php

use LightningCheckoutPlugin\LightningCheckoutAPI;

function lnc_gateway_check_payment_status(WP_REST_Request $request) {
    $api = new LightningCheckoutAPI(LNC_API_KEY, LNC_URL, LNC_WEBHOOK_URL);
    $paymentHash = $request->get_param('hash');
    
    try {
        $paymentStatus = $api->checkPayment($paymentHash);
    } catch (Exception $e) {
        return new WP_REST_Response(['error' => $e->getMessage()], 500);
    } 
    
    $paid = $paymentStatus['paid'];

    $response = [
        "paid" => $paid,
    ];
    return new WP_REST_Response($response, 200);
}

add_action('rest_api_init', 'lnc_gateway_rest');

function lnc_gateway_rest() {
    register_rest_route('lnc-gateway/v1', '/checkpaymentstatus/', array(
        'methods' => 'GET', 
        'callback' => 'lnc_gateway_check_payment_status',
    )); 
}
