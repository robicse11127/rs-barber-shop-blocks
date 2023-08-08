<?php
/**
 * Plugin Name: RS barber shop blocks
 * Description: All blocks for the RS Barber SHop
 * Author: Md. Rabiul Islam
 * Author URI: http://example.com
 * Text-Domain: gb-block-tuts
 */
if( ! defined( 'ABSPATH' ) ) : exit(); endif;

final class RSBS_Blocks {

    const VERSION = '1.0.0';

    /**
     * Construct Function
     */
    private function __construct() {
        $this->plugin_constants();
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Define plugin constants
     */
    public function plugin_constants() {
        define( 'RSBS_VERSION', self::VERSION );
        define( 'RSBS_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
        define( 'RSBS_PLUGIN_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
    }

    /**
     * Singletone Instance
     */
    public static function init() {
        static $instance = false;
        if( ! $instance ) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Plugin Init
     */
    public function init_plugin() {
        $this->enqueue_scripts();
        $this->register_blocks();
        $this->register_patterns();
        add_filter( 'block_categories', [ $this, 'rsbs_block_category' ], 10, 2);
    }

    /**
     * Enqueue Scripts
     */
    public function enqueue_scripts() {
        add_action( 'enqueue_block_editor_assets', [ $this, 'register_block_editor_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_scripts' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_public_scripts' ] );
    }

    /**
     * Regsiter Block Editor Assets
     */
    public function register_block_editor_assets() {
        wp_enqueue_script(
            'rsbs-editor',
            RSBS_PLUGIN_URL . '/assets/js/editor.js',
            rand(),
            true
        );

        wp_enqueue_style(
            'rsbs-editor',
            RSBS_PLUGIN_URL . '/assets/css/editor.css',
            [],
            false,
            'all'
        );
    }

    /**
     * Register Admin Scritps
     */
    public function register_admin_scripts() {}

    /**
     * Register Public Scritps
     */
    public function register_public_scripts() {
        wp_enqueue_script(
            'rsbs-public',
            RSBS_PLUGIN_URL . '/assets/js/scripts.js',
            rand(),
            true
        );

        wp_enqueue_style(
            'rsbs-public',
            RSBS_PLUGIN_URL . '/assets/css/style.css',
            [],
            false,
            'all'
        );
    }

    /**
     * Register Blocks
     */
    public function register_blocks() {
        $rsbs_block_files = glob( RSBS_PLUGIN_PATH . '/src/blocks/**/index.php' );

        foreach( $rsbs_block_files as $rsbs_block ) {
            require_once $rsbs_block;
        }
    }

    /**
     * Register Patterns.
     */
    public function register_patterns() {
        require_once RSBS_PLUGIN_PATH . '/inc/patterns/index.php';
    }


    /**
     * Register custom block category.
     */
    public function rsbs_block_category( $categories, $post ) {
        return array_merge(
            $categories,
            array(
                array(
                    'slug' => 'rsbs-blocks',
                    'title' => __( 'Prefix Blocks', 'rsbs-blocks' ),
                ),
            )
        );
    }
}

/**
 * Init Main Plugin
 */
function rsbs_run_plugin() {
    return RSBS_Blocks::init();
}
// Run the plugin
rsbs_run_plugin();
