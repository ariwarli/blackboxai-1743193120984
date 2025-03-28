<?php
/**
 * Plugin Name: Sentra AI Dashboard
 * Plugin URI: https://sentra-ai.com
 * Description: A secure dashboard for managing Sentra AI configurations and data input without direct access to Google Sheets.
 * Version: 1.0.0
 * Author: Sentra AI
 * Text Domain: sentra-ai-dashboard
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('SENTRA_AI_VERSION', '1.0.0');
define('SENTRA_AI_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SENTRA_AI_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once SENTRA_AI_PLUGIN_DIR . 'includes/google-sheets-api.php';

/**
 * Add admin menu item
 */
function sentra_ai_add_admin_menu() {
    add_menu_page(
        'Sentra AI Dashboard',
        'Sentra AI',
        'manage_options',
        'sentra-ai-dashboard',
        'sentra_ai_dashboard_page',
        'dashicons-analytics',
        30
    );
}
add_action('admin_menu', 'sentra_ai_add_admin_menu');

/**
 * Enqueue admin scripts and styles
 */
function sentra_ai_enqueue_admin_assets() {
    $screen = get_current_screen();
    if ($screen->id !== 'toplevel_page_sentra-ai-dashboard') {
        return;
    }

    // Enqueue Tailwind CSS
    wp_enqueue_style(
        'tailwind-css',
        'https://cdn.tailwindcss.com',
        array(),
        SENTRA_AI_VERSION
    );

    // Enqueue Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css',
        array(),
        '6.0.0'
    );

    // Enqueue custom CSS
    wp_enqueue_style(
        'sentra-ai-dashboard',
        SENTRA_AI_PLUGIN_URL . 'css/sentra-ai-dashboard.css',
        array(),
        SENTRA_AI_VERSION
    );

    // Enqueue custom JavaScript
    wp_enqueue_script(
        'sentra-ai-dashboard',
        SENTRA_AI_PLUGIN_URL . 'js/sentra-ai-dashboard.js',
        array('jquery'),
        SENTRA_AI_VERSION,
        true
    );

    // Add the WordPress AJAX URL as a JavaScript variable
    wp_localize_script(
        'sentra-ai-dashboard',
        'sentraAiAjax',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sentra_ai_nonce')
        )
    );
}
add_action('admin_enqueue_scripts', 'sentra_ai_enqueue_admin_assets');

/**
 * Register AJAX handlers
 */
function sentra_ai_register_ajax_handlers() {
    add_action('wp_ajax_save_training_data', 'sentra_ai_save_training_data');
    add_action('wp_ajax_save_company_info', 'sentra_ai_save_company_info');
    add_action('wp_ajax_save_qna_data', 'sentra_ai_save_qna_data');
}
add_action('init', 'sentra_ai_register_ajax_handlers');

/**
 * Include the admin dashboard page
 */
function sentra_ai_dashboard_page() {
    require_once SENTRA_AI_PLUGIN_DIR . 'admin-dashboard.php';
}