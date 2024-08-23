<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Simple_Referral_Block {
    /**
     * Initialize the block functionality
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_block'));
        add_action('enqueue_block_editor_assets', array(__CLASS__, 'enqueue_block_editor_assets'));
    }

    /**
     * Register the Gutenberg block
     */
    public static function register_block() {
        register_block_type('simple-referral-system/referral-link', array(
            'render_callback' => array(__CLASS__, 'render_referral_link_block'),
        ));
    }

    /**
     * Enqueue block editor assets
     */
    public static function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'simple-referral-system-block',
            plugin_dir_url(__FILE__) . '../js/block.js',
            array('wp-blocks', 'wp-element', 'wp-editor'),
            '1.2.0',
            true
        );
    }

    /**
     * Render callback for the Gutenberg block
     *
     * @return string HTML for the referral link input and copy button
     */
    public static function render_referral_link_block() {
        return EasyRefferalRegistration::get_referral_link_html();
    }
}