<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('copacf_get_option')) {
    function copacf_get_option($field_name, $default = false) {
        return get_option('copacf_options_' . $field_name, $default);
    }
}
