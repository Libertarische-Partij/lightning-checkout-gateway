<?php

function lnc_gateway_menu() {
    add_options_page(
        'Lightning Checkout Settings',
        'Lightning Checkout',
        'manage_options',
        'lnc-plugin',
        'lnc_gateway_settings_page'
    );
}
add_action('admin_menu', 'lnc_gateway_menu');

function lnc_gateway_settings_page() {
    ?>
    <div class="wrap">
        <h1>Lightning Checkout Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('lnc-plugin-settings');
            do_settings_sections('lnc-plugin');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registreer de instellingen
function lnc_gateway_settings_init() {
    // Registratie
    register_setting('lnc-plugin-settings', 'LNC_API_KEY');
    register_setting('lnc-plugin-settings', 'LNC_URL');
    register_setting('lnc-plugin-settings', 'LNC_WEBHOOK_URL');
    register_setting('lnc-plugin-settings', 'LNC_DEFAULT_DESCRIPTION');
    register_setting('lnc-plugin-settings', 'LNC_DEFAULT_AMOUNT');
    register_setting('lnc-plugin-settings', 'LNC_DEFAULT_RETURNURL');

    add_settings_section(
        'lnc_gateway_settings_section',
        'API Settings',
        '',
        'lnc-plugin'
    );

    add_settings_field('LNC_API_KEY', 'Lightning Checkout API KEY', 'lnc_api_key_field_render', 'lnc-plugin', 'lnc_gateway_settings_section');
    add_settings_field('LNC_URL', 'Lightning Checkout API URL', 'lnc_url_field_render', 'lnc-plugin', 'lnc_gateway_settings_section');
    add_settings_field('LNC_WEBHOOK_URL', 'Lightning Checkout Webhook URL', 'lnc_webhook_url_field_render', 'lnc-plugin', 'lnc_gateway_settings_section');
    add_settings_field('LNC_DEFAULT_DESCRIPTION', 'Default payment description', 'lnc_default_description_field_render', 'lnc-plugin', 'lnc_gateway_settings_section');
    add_settings_field('LNC_DEFAULT_AMOUNT', 'Default payment amount', 'lnc_default_amount_field_render', 'lnc-plugin', 'lnc_gateway_settings_section');
    add_settings_field('LNC_DEFAULT_RETURNURL', 'Default return URL', 'lnc_default_returnurl_field_render', 'lnc-plugin', 'lnc_gateway_settings_section');
}
add_action('admin_init', 'lnc_gateway_settings_init');

function lnc_api_key_field_render() {
    $value = get_option('LNC_API_KEY');
    echo '<input type="text" name="LNC_API_KEY" value="' . esc_attr($value) . '">';
}

function lnc_url_field_render() {
    $value = get_option('LNC_URL');
    echo '<input type="text" name="LNC_URL" value="' . esc_attr($value) . '">';
}

function lnc_webhook_url_field_render() {
    $value = get_option('LNC_WEBHOOK_URL');
    echo '<input type="text" name="LNC_WEBHOOK_URL" value="' . esc_attr($value) . '">';
}

function lnc_default_description_field_render() {
    $value = get_option('LNC_DEFAULT_DESCRIPTION');
    echo '<input type="text" name="LNC_DEFAULT_DESCRIPTION" value="' . esc_attr($value) . '">';
}

function lnc_default_amount_field_render() {
    $value = get_option('LNC_DEFAULT_AMOUNT');
    echo '<input type="text" name="LNC_DEFAULT_AMOUNT" value="' . esc_attr($value) . '">';
}

function lnc_default_returnurl_field_render() {
    $value = get_option('LNC_DEFAULT_RETURNURL');
    echo '<input type="text" name="LNC_DEFAULT_RETURNURL" value="' . esc_attr($value) . '">';
}
