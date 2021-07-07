<?php
/**
 * Plugin Name:       Recetas relacionadas
 * Description:       Este plugin agrega la funcionalidad para mostrar las recetas relacionadas a cada producto
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Miguel Colmenares
 * Author URI:        https://miguelcolmenares.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       related-posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RelatedPosts {
	static $instance = false;

	public $plugin_version = '1.0';

	private function __construct() {
		// Text domain.
		add_action( 'init', array( $this, 'register_users_load_textdomain' ) );
		// Start it up.
		add_action( 'after_setup_theme', array( $this, 'init' ), 0 );
		// Form scripts
		// add_action( 'wp_enqueue_scripts', array( $this, 'register_users_javascript') );
		// Ajax
		// add_action( 'wp_ajax_nopriv_register-users', array( $this, 'ajax_register_users' ) );
		// add_action( 'wp_ajax_register-users', array( $this, 'ajax_register_users' ) );
	}

	public static function getInstance() {
		if ( !self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function init() {}

    public function register_users_load_textdomain() {
		load_plugin_textdomain( 'related-posts', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}
}

// Plugin init.
$RelatedPostsPlugin = RelatedPosts::getInstance();