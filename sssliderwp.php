<?php
/**
 * Plugin Name:     Super Simple Slider for WordPress
 * Plugin URI:      http://gresak.net/plugins
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          Gregor Grešak
 * Author URI:      http://gresak.net
 * Text Domain:     sssliderwp
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Sssliderwp
 */

namespace gresnet;

use gresnet\posttype\postType;

include_once "post-types/slide.php";

postType::instance();
Ssslider::instance();

class Ssslider {

	protected $version;

	protected $url;

	private static $instance;

	protected function __construct() {
		$this->set_version();
		$this->get_plugin_url();
		add_shortcode("sss",array($this,"display_slider"));
		add_action('wp_enqueue_scripts', array($this,'load_scripts'));
	}

	public function load_scripts() {
		wp_enqueue_script( 'sssjs',trailingslashit($this->url)."scripts/sss/sss.min.js",array('jquery'),$this->version);
		wp_enqueue_style( 'ssscss',trailingslashit($this->url)."scripts/sss/sss.css",false,$this->version);
	}

	public function display_slider($atts) {

		$args = array(
			'post_type' => "slide",
			'tax_query' => array(
				array (
					'taxonomy' => 'slider_tag',
					'field'	=> 'slug',
					'terms' => $atts[0]
					)
				)
			);
		$query = new \WP_Query($args);
		if($query->have_posts()) {
			echo "<div class='slider'>";
			while($query->have_posts()) {
				$query->the_post();
				echo "<div class='slide'>";
				the_post_thumbnail( 'full', '' );
				echo "<div class='text'><h1>".get_the_title()."</h1>";
				the_content();
				echo '<div class="more"><a href="#">Preberite več ...</a></div></div>';
				echo "</div>";
			}
			echo "</div>";
			echo "<script>jQuery('.slider').sss("transition": 900);</script>";
		}
	}

	protected function set_version() {
		$theme = wp_get_theme();
		$wp_version = get_bloginfo( 'version' );
		$this->version =  $wp_version ."." . $theme->get('Version');
	}

	protected function get_plugin_url() {
		$this->url = plugin_dir_url(__FILE__);
	}


	public static function instance() {
		if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
	}
}
