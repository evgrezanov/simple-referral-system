<?php
/*
Plugin Name: Simple Referral System
Plugin URI: http://example.com/
Description: A simple referral system plugin for WordPress with Gutenberg block support
Version: 1.2.0
Author: redmonkey73
Author URI: https://evgrezanov.github.io
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once plugin_dir_path(__FILE__) . 'includes/class-referral-block.php';

class EasyRefferalRegistration {
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('user_register', array($this, 'save_referrer'));
        add_filter('simple_referral_copy_button_text', array($this, 'modify_copy_button_text'));
        add_action('wp', array($this, 'check_referral_cookie'));
        
        Simple_Referral_Block::init();
    }

    /**
     * Initialize the plugin by registering shortcodes
     */
    public function init() {
        add_shortcode('referral_link', array($this, 'referral_link_shortcode'));
        add_shortcode('referral_list', array($this, 'referral_list_shortcode'));
    }

    /**
     * Enqueue necessary scripts for the plugin
     */
    public function enqueue_scripts() {
        wp_enqueue_script('clipboard', 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js', array(), '2.0.8', true);
        wp_enqueue_script('referral-system', plugin_dir_url(__FILE__) . 'js/referral-system.js', array('clipboard'), '1.2.0', true);
        wp_add_inline_style('referral-system', '
            #referral-link {
                width: 100%;
                box-sizing: border-box;
            }
        ');
    }

    /**
     * Shortcode to display the user's referral link
     *
     * @return string HTML for the referral link input and copy button
     */
    public function referral_link_shortcode() {
        return self::get_referral_link_html();
    }

    /**
     * Get the HTML for the referral link input and copy button
     *
     * @return string HTML for the referral link input and copy button
     */
    public static function get_referral_link_html() {
        if (!is_user_logged_in()) {
            return 'Please log in to get your referral link.';
        }

        $user_id = get_current_user_id();
        $referral_link = add_query_arg('ref_id', $user_id, home_url('/register/'));

        $copy_button_text = apply_filters('simple_referral_copy_button_text', 'Copy Link');

        return '<input type="text" id="referral-link" value="' . esc_url($referral_link) . '" readonly>
                <button class="copy-button" data-clipboard-target="#referral-link">' . esc_html($copy_button_text) . '</button>';
    }

    /**
     * Shortcode to display the list of user's referrals
     *
     * @return string HTML list of user's referrals or a message if no referrals
     */
    public function referral_list_shortcode() {
        if (!is_user_logged_in()) {
            return 'Please log in to view your referrals.';
        }

        $user_id = get_current_user_id();
        $referrals = $this->get_user_referrals($user_id);

        if (empty($referrals)) {
            return 'You have no referrals yet.';
        }

        $output = '<ul>';
        foreach ($referrals as $referral) {
            $output .= '<li>' . esc_html($referral->user_login) . ' (Registered: ' . $referral->user_registered . ')</li>';
        }
        $output .= '</ul>';

        return $output;
    }

    /**
     * Save the referrer ID when a new user registers
     */
    public function save_referrer() {
        $referrer_id = $this->get_referrer_id();
        if ($referrer_id) {
            $user_id = get_current_user_id();
            if ($user_id !== $referrer_id) {
                update_user_meta($user_id, 'bvpref', $referrer_id);
            }
        }
    }

    /**
     * Get the list of referrals for a given user
     *
     * @param int $user_id The ID of the user to get referrals for
     * @return array An array of user objects representing the referrals
     */
    private function get_user_referrals($user_id) {
        global $wpdb;

        $referrals = $wpdb->get_results($wpdb->prepare(
            "SELECT u.* FROM {$wpdb->users} u
            JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
            WHERE um.meta_key = 'bvpref' AND um.meta_value = %d",
            $user_id
        ));

        return $referrals;
    }

    /**
     * Modify the copy button text (can be filtered by other plugins)
     *
     * @param string $text The current text of the copy button
     * @return string The modified text of the copy button
     */
    public function modify_copy_button_text($text) {
        return $text; // Default implementation, can be overridden using the filter
    }

    /**
     * Check for referral ID in URL and set a cookie if present
     */
    public function check_referral_cookie() {
        if (isset($_GET['ref_id'])) {
            $referrer_id = intval($_GET['ref_id']);
            setcookie('bvpref', $referrer_id, time() + (30 * DAY_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN);
        }
    }

    /**
     * Get the referrer ID from either GET parameter or cookie
     *
     * @return int|false The referrer ID or false if not found
     */
    private function get_referrer_id() {
        if (isset($_GET['ref_id'])) {
            return intval($_GET['ref_id']);
        } elseif (isset($_COOKIE['bvpref'])) {
            return intval($_COOKIE['bvpref']);
        }
        return false;
    }
}

new EasyRefferalRegistration();