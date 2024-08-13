<?php
/*
Plugin Name: Retack Logging Plugin
Description: Logs errors and sends them to an retack.ai.
Version: 1.0
Author: Truenary Solutions
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

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

function elp_options_page() {
?>
    <div class="wrap">
        <h2>Error Retack Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('elp_options_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="elp_api_key">Retack Environment Key</label></th>
                    <td><input type="text" id="elp_api_key" name="elp_api_key" value="<?php echo esc_attr(get_option('elp_api_key')); ?>" style="width: 400px;" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

// Send error log to the API
function send_error_to_api($title, $stack) {
    $api_url = 'https://api.dev.retack.ai/observe/error-log/';
    $api_key = get_option('elp_api_key');

    $user_context = [
        'user_id' => get_current_user_id(),
        'username' => wp_get_current_user()->user_login
    ];

    if (empty($api_key)) {
        error_log('API key is not set.');
        return;
    }

    $body = json_encode([
        'title' => $title,
        'stack_trace' => $stack,
        'user_context' => $user_context,
        'site_url' => get_site_url(),
        'timestamp' => current_time('mysql')
    ]);

    $response = wp_remote_post($api_url, [
        'headers' => [
            'Content-Type' => 'application/json',
            'ENV-KEY' => $api_key
        ],
        'body' => $body
    ]);

    if (is_wp_error($response)) {
        error_log('Failed to send error to retack.ai: ' . $response->get_error_message());
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
    wp_enqueue_script('elp-error-handler', plugins_url('/js/error-handler.js', __FILE__), [], '1.0', false);
}
add_action('wp_enqueue_scripts', 'elp_enqueue_error_script');

// Handle AJAX errors
function handle_js_error_logging() {
    // Decode the JSON body to get the data
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['title']) && isset($data['stack_trace'])) {

        // Send the error to the API and capture the response
        $response = send_error_to_api($data['title'], $data['stack_trace']);

        if (is_wp_error($response)) {
            wp_send_json_error('Failed to send error to API: ' . $response->get_error_message());
        }
    } else {
        wp_send_json_error($data);
    }
}

add_action('wp_ajax_log_js_error', 'handle_js_error_logging');
add_action('wp_ajax_nopriv_log_js_error', 'handle_js_error_logging');
