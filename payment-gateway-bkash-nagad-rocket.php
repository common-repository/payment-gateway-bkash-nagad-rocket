<?php
/*
 * Plugin Name: Payment Gateway Bkash Nagad Rocket
 * Plugin URI: https://freelancersohag.com/about-payment-gateway-bkash-nagad-rocket
 * Description: Payment Gateway Bkash Nagad Rocket is a powerful tool for Elementor addons. It is very beautiful for any website showcase.
 * Version: 1.0.0
 * Author: Freelancer Sohag
 * Author URI: https://freelancersohag.com
 * Elementor tested up to: 3.20.0
 * Elementor Pro tested up to: 3.20.0
 * Text Domain: payment-gateway-bkash-nagad-rocket
 * Domain Path: /languages
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

final class Nucleus_Addons_Extension {

    const VERSION = '1.0.0';
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
    const MINIMUM_PHP_VERSION = '7.0';

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'init', [ $this, 'localization_setup' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    // Load plugin localization files
    public function localization_setup() {
        load_plugin_textdomain( 'payment-gateway-bkash-nagad-rocket', false, plugin_dir_path( __FILE__ ) . '/languages' );
    }

    // Initialize the plugin
    public function init() {
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );        
        add_action( 'elementor/init', [ $this, 'nucleus_addons_category' ] );
    }

    // Display admin notice if Elementor is not activated
    public function admin_notice_missing_main_plugin() {
        printf(
            '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
            esc_html__( '"Payment Gateway Bkash Nagad Rocket Extension" requires "Elementor" to be installed and activated.', 'payment-gateway-bkash-nagad-rocket' )
        );
    }

// Display admin notice for minimum Elementor version
public function admin_notice_minimum_elementor_version() {
    printf(
        '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
        sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"Payment Gateway Bkash Nagad Rocket Extension" requires "Elementor" version %s or greater.', 'payment-gateway-bkash-nagad-rocket' ),
            esc_html( self::MINIMUM_ELEMENTOR_VERSION )
        )
    );
}

// Display admin notice for minimum PHP version
public function admin_notice_minimum_php_version() {
    printf(
        '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
        sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"Payment Gateway Bkash Nagad Rocket Extension" requires "PHP" version %s or greater.', 'payment-gateway-bkash-nagad-rocket' ),
            esc_html( self::MINIMUM_PHP_VERSION )
        )
    );
}

    // Initialize custom widgets
    public function init_widgets() {
        require_once( __DIR__ . '/widgets/PaymentGatewayBkashNagadRocket.php' );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Nucleus_Addons() );
    }

    // Enqueue widget styles
    public function widget_styles() {
        wp_enqueue_style( 'nucleus_addons_bootstrap', plugins_url( '/assets/css/bootstrap.min.css', __FILE__ ), [], '1.0.0' );
        wp_enqueue_style( 'nucleus_addons_fontawesome', plugins_url( '/assets/css/fontawesome.all.min.css', __FILE__ ), [], '1.0.0' );
        wp_enqueue_style( 'nucleus_addons_style', plugins_url( 'style.css', __FILE__ ), [], self::VERSION );
    }

    // Register custom category
    public function nucleus_addons_category () {
        /* Translators: Custom category title in Elementor */
        \Elementor\Plugin::$instance->elements_manager->add_category( 
            'payment-gateway-bkash-nagad-rocket-cat',
            [
                'title' => esc_html__( 'Payment Gateway Bkash Nagad Rocket', 'payment-gateway-bkash-nagad-rocket' ),
                'icon' => 'fa fa-plug', //default icon
            ],
            2 // position
        );
    }   
}

Nucleus_Addons_Extension::instance();