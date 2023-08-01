<?php

/*
Plugin Name: Lightning Checkout Payment Gateway
Description: Accept Bitcoin over Lightning instantly and with near to zero fees using the Lightning Checkout API
Version: 0.2.2
Author: Rutger Weemhoff
Author URI: https://github.com/rutgernation
*/

define('LNC_API_KEY', get_option('LNC_API_KEY'));
define('LNC_URL', get_option('LNC_URL'));
define('LNC_WEBHOOK_URL', get_option('LNC_WEBHOOK_URL'));
define('LNC_DEFAULT_DESCRIPTION', get_option('LNC_DEFAULT_DESCRIPTION'));
define('LNC_DEFAULT_AMOUNT', get_option('LNC_DEFAULT_AMOUNT'));
define('LNC_DEFAULT_RETURNURL', get_option('LNC_DEFAULT_RETURNURL'));

require_once(__DIR__ . '/includes/init.php');
require_once(__DIR__ . '/includes/settings.php');
require_once(__DIR__ . '/includes/shortcode.php');
require_once(__DIR__ . '/includes/rest.php');

register_activation_hook(__FILE__, 'lightning_checkout_activate');
function lightning_checkout_activate() {
    if (!current_user_can('activate_plugins')) return;
}
