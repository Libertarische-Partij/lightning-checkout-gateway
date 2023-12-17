<?php

use LightningCheckoutPlugin\LightningCheckoutAPI;

/**
 * Renders a template file from the templates directory.
 *
 * @param string $tpl_name The name of the template file (without the extension).
 * @param array  $params   An array of parameters to pass to the template file.
 * @return string The output of the rendered template.
 */
function render_template($tpl_name, $params = array()) {
    $params = is_array($params) ? $params : array();

    $tpl_path = plugin_dir_path(__FILE__) . '/../templates/' . $tpl_name . '.php';
    if (!file_exists($tpl_path)) {
        // Handle error accordingly.
        // You can throw an exception, return an error message or just an empty string.
        return '';
    }

    extract($params, EXTR_SKIP);

    ob_start();

    include $tpl_path;

    return ob_get_clean();
}

/**
 * Converts a string into a float value.
 *
 * @param string $input The input string.
 * @return float The converted float value.
 */
function getFloatValueFromString($input) {
    $numericalString = preg_replace('/[^0-9,]/', '', $input);
    $numericalString = str_replace(',', '.', $numericalString);
    return floatval($numericalString);
}


/**
 * Generic error handler
 */
function handleError($error) {
    return "<p>Error: " . $error->getMessage() . "</p>";
}

/**
 * Validates the plugin settings.
 *
 * @throws Exception If a setting is not defined or is empty.
 */
function validateSettings() {
    $requiredConstants = ['LNC_API_KEY', 'LNC_URL', 'LNC_WEBHOOK_URL'];
    
    foreach ($requiredConstants as $constant) {
        if (!defined($constant) || empty(constant($constant))) {
            throw new Exception($constant . ' is not set. Please check your settings.');
        }
    }
}

/**
 * Validates the shortcode attributes.
 *
 * @param array $atts The shortcode attributes.
 * @throws Exception If a required attribute is not set or is empty.
 */
function validateAttributes($atts) {
    $requiredKeys = ['description', 'amount', 'returnurl'];

    foreach ($requiredKeys as $key) {
        if (!array_key_exists($key, $atts) || empty($atts[$key])) {
            throw new Exception('Required parameter ' . $key . ' is not set. Please check your shortcode attributes or default settings.');
        }
    }
}

/**
 * Lightning Payment Shortcode Handler
 *
 * @param array $atts Shortcode attributes.
 * @return string Rendered shortcode content.
 */
function lightning_payment_shortcode($atts = []) {
    try {
        validateSettings();
        $atts = prepareAttributes($atts);
        validateAttributes($atts);
    } catch (Exception $e) {
        return handleError($e);
    }

    $amount = getFloatValueFromString($atts['amount']);

    $api = new LightningCheckoutAPI(LNC_API_KEY, LNC_URL, LNC_WEBHOOK_URL);
    try {
        $invoiceData = $api->generateInvoice($amount, $atts['description'] . " (" . $amount . " EUR)");
    } catch (Exception $e) {
        return handleError($e);
    }

    enqueue_payment_script($atts['returnurl'], $invoiceData);

    $template_params = array(
        "amount" => number_format($amount, 2, ',', ''),
        "payment_request" => $invoiceData['payment_request'],
    );
    
    return render_template('payment_shortcode', $template_params);
}

/**
 * Prepare shortcode attributes.
 *
 * @param array $atts Shortcode attributes.
 * @return array Processed shortcode attributes.
 */
function prepareAttributes($atts) {
    $defaults = array(
        'amount' => LNC_DEFAULT_AMOUNT,
        'description' => LNC_DEFAULT_DESCRIPTION,
        'returnurl' => LNC_DEFAULT_RETURNURL,
    );

    if (!is_array($atts)) {
        $atts = [];
    }    

    return array_merge($defaults, $atts);
}

/**
 * Enqueue the payment script.
 *
 * @param string $returnUrl The return URL.
 * @param array $invoiceData The invoice data.
 */
function enqueue_payment_script($returnUrl, $invoiceData) {
    wp_register_script( 'check-payment', plugins_url('/../js/shortcode.js' , __FILE__), ['jquery'], '1.0', true );
    $injection = array(
        'returnurl' => $returnUrl,
    );
    wp_localize_script( 'check-payment', 'injected_data', $injection );    
    wp_enqueue_script('check-payment');   

    $check_payment_url = get_bloginfo('wpurl') . '/wp-json/lnc-gateway/v1/checkpaymentstatus/?hash=' . $invoiceData['payment_hash'];
    $js_data = array(
        'check_payment_url' => esc_url($check_payment_url),
        'invoice_data' => $invoiceData,
    );

    wp_localize_script('check-payment', 'paymentData', $js_data);
}

add_shortcode('lightning_payment', 'lightning_payment_shortcode');