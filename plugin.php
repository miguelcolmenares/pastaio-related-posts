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

		add_action( 'woocommerce_after_single_product_summary', array( $this, 'get_related_post' ), 15 );
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

	public function get_related_post() {
		$productId = get_the_ID();
		$query = new WP_Query(
			array(
				'meta_query' => array(
					array(
						'key'     => 'related_products',
						'value'   => $productId,
						'compare' => 'LIKE',
					),
				),
				'post_type' => 'post',
				'post_status' => 'publish'
			)
		);
		if ( $query->have_posts() ) {
			$output = "<section class=\"related products\">";
				$output .= $this->get_section_title();
				$output .= "<ul class=\"products columns-4\">";
				while ( $query->have_posts() ) :
					$query->the_post();
					$thumbnail = has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), 'woocommerce_thumbnail') : '<img src="' . get_stylesheet_directory_uri() . '/assets/dist/images/placeholder.png" alt="">';
					$output .= "<li class=\"product\">
						<div class=\"jupiterx-product-container \">
							<a href=\"" . esc_url( get_permalink() ) . "\" class=\"woocommerce-LoopProduct-link woocommerce-loop-product__link\">
								<div class=\"jupiterx-wc-loop-product-image\">" . $thumbnail . "</div>
								<h2 class=\"woocommerce-loop-product__title\">" . get_the_title() . "</h2>
							</a>
						</div>
					</li>";
				endwhile;
				$output .= "</ul>";
			$output .= "</section>";
			wp_reset_postdata();
			echo $output;
		}
	}

	private function get_section_title() {
		return "<h2>" . __('Recetas relacionadas', 'related-posts') . "</h2>";
	}
}

// Plugin init.
$RelatedPostsPlugin = RelatedPosts::getInstance();