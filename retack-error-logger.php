<?php
/*
Plugin Name: Retack AI
Description: Logs errors and sends them to retack.ai.
Version: 1.0
Author: Truenary Solutions
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$version = '1.0';

// Register settings and settings page
function elp_register_settings() {
    add_option('elp_api_key', '');
    register_setting('elp_options_group', 'elp_api_key');
}
add_action('admin_init', 'elp_register_settings');

function elp_register_options_page() {
    add_options_page('Error Logging Plugin', 'Retack', 'manage_options', 'elp', 'elp_options_page');
}
add_action('admin_menu', 'elp_register_options_page');

// Enqueue styles
function elp_enqueue_assets() {
	global $version;
		
    wp_enqueue_style('elp-styles', plugins_url('/css/style.css', __FILE__), array(), $version, false);
}
add_action('admin_enqueue_scripts', 'elp_enqueue_assets');

function elp_options_page() {
    // Include the external HTML file
    include plugin_dir_path(__FILE__) . 'views/settings_page_content.php';
}



// Send error log to the API
function send_error_to_api($title, $stack) {
    $api_url = 'https://api.retack.ai/observe/error-log/';
    $api_key = get_option('elp_api_key');

    $user_context = [
        'user_id' => get_current_user_id(),
        'username' => wp_get_current_user()->user_login
    ];

    if (empty($api_key)) {
        $error_message = 'API key is not set.';
        error_log($error_message);
        return new WP_Error('api_key_missing', $error_message);
    }

    // Escape output when needed
    $escaped_api_key = esc_html($api_key); // For HTML output

    // If using in JavaScript, escape accordingly
    $escaped_api_key_js = esc_js($escaped_api_key); // Uncomment if needed for JS context

    $body = wp_json_encode([
        'title' => $title,
        'stack_trace' => $stack,
        'user_context' => $user_context,
        'site_url' => get_site_url(),
        'timestamp' => current_time('mysql')
    ]);

    $response = wp_remote_post($api_url, [
        'headers' => [
            'Content-Type' => 'application/json',
            'ENV-KEY' => $escaped_api_key_js
        ],
        'body' => $body
    ]);

    if (is_wp_error($response)) {
        $error_message = 'Failed to send error to retack.ai: ' . $response->get_error_message();
        error_log($error_message);
        return new WP_ERROR('api_error', $error_message);
    }

    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code == 200) {
        return 'Error successfully sent to API.';
    } else {
        $error_message = 'Error sending to API. HTTP Status Code: ' . $response_code;
        error_log($error_message);
        return new WP_Error('api_error', $error_message);
    }
}

// Hook into PHP errors and shutdown errors
function log_php_errors($errno, $errstr, $errfile, $errline) {
    $error_message = "Error: [$errno] $errstr in $errfile on line $errline";
    send_error_to_api('PHP Error', $error_message); // Title for PHP errors
    error_log($error_message);
}
set_error_handler('log_php_errors');

function log_shutdown_errors() {
    $error = error_get_last();
    if ($error !== NULL) {
        $error_message = "Shutdown Error: [{$error['type']}] {$error['message']} in {$error['file']} on line {$error['line']}";
        send_error_to_api('Shutdown Error', $error_message);
        error_log($error_message);
    }
}
add_action('shutdown', 'log_shutdown_errors');

// Enqueue JavaScript for error logging
function elp_enqueue_error_script() {
	global $version;
	// Enqueue the error-handling script in all necessary contexts
    if (is_admin() || $GLOBALS['pagenow'] === 'wp-login.php' || !is_admin()) {
        wp_enqueue_script('elp-error-handler', plugins_url('/js/error-handler.js', __FILE__), array(), $version, false);
    }
}
// Frontend Area
add_action('wp_enqueue_scripts', 'elp_enqueue_error_script');

// Admin area
add_action('admin_enqueue_scripts', 'elp_enqueue_assets');

// Login page
add_action('login_enqueue_scripts', 'elp_enqueue_assets');

// Handle AJAX errors
function handle_js_error_logging() {
    // Decode the JSON body to get the data
    $data = json_decode(file_get_contents('php://input'), true);

    if (is_array($data)) {
         // Sanitize the title
        $title = isset($data['title']) ? sanitize_text_field($data['title']) : '';
        // Sanitize the stack trace
        $stack_trace = isset($data['stack_trace']) ? sanitize_textarea_field($data['stack_trace']) : '';

        // Validate title and stack trace
        if (empty($title)) { // Ensure title is not empty
            // Handle the error appropriately
            return;
        }

        if (empty($stack_trace)) { // Ensure stack trace is not empty
            // Handle the error appropriately
            return;
        }

        // Escape output when needed
        $escaped_title = esc_html($title); // For HTML output
        $escaped_stack_trace = esc_html($stack_trace); // For HTML output

        // If using in JavaScript, escape accordingly
        $escaped_title_js = esc_js($escaped_title); // Uncomment if needed for JS context
        $escaped_stack_trace_js = esc_js($escaped_stack_trace); 

        // Send the error to the API and capture the response
        $response = send_error_to_api($escaped_title_js, $escaped_stack_trace_js);

        if (is_wp_error($response)) {
            wp_send_json_error($response->get_error_message());
        } else {
            wp_send_json_success($response);
        }
    } else {
        wp_send_json_error($data);
    }
}

add_action('wp_ajax_log_js_error', 'handle_js_error_logging');
add_action('wp_ajax_nopriv_log_js_error', 'handle_js_error_logging');
