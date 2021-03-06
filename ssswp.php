<?php
/**
 * Plugin Name:     Super Simple Slider for WordPress
 * Plugin URI:      http://demo.gresak.net/ssswp
 * Description:     Slider that has everything a slider needs and nothing more.
 * Author:          Gregor Grešak
 * Author URI:      http://gresak.net
 * Text Domain:     ssswp
 * Domain Path:     /languages
 * Version:         1.2.0
 *
 * @package         Sssliderwp
 */

namespace gresnet;

use gresnet\posttype\postType;
use gresnet\update\GG_auto_update;

include_once "post-types/slide.php";
include_once "meta-box.php";

$sssliderPostType = postType::instance();
$Ssslider = Ssslider::instance();

class Ssslider {

	protected $version;

	protected $url;

	private static $instance;

	protected function __construct() {
		$this->set_version();
		$this->get_plugin_url();
		add_shortcode("sss",array($this,"display_slider"));
		add_filter( 'term_description', 'do_shortcode' );
		add_action('wp_enqueue_scripts', array($this,'load_scripts'));
		add_filter('init', array($this,'updater'));
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
				$cf = get_post_custom( );
				$turl = $this->get_target_url($cf);
				echo "<div class='slide'>";
				if(has_post_thumbnail()) {
					echo "<a href='".$turl."'>";
					the_post_thumbnail( 'full', '' );
					echo "</a>";
				}
				echo "<div class='text'><h1><a href='".$turl."'>".get_the_title()."</a></h1>";
				the_content();
				echo '<div class="more"><a href="'.$turl.'">Preberite več ...</a></div></div>';
				echo "</div>";
			}
			echo "</div>";
			echo "<script>jQuery('.slider').sss({'transition': 1200, speed: 8000});</script>";
		}
	}

	protected function get_target_url($cf) {
		$url = isset($cf['ssm_target_url'][0])?$cf['ssm_target_url'][0]:"#";
		if($url == "#") return $url;
		$sch = is_ssl()?"https://":"http://";
		$url = preg_match('/^https?:\/\//',$url) ? $url : $sch.$url;
		return $url;
	}

	protected function set_version() {
		$theme = wp_get_theme();
		$wp_version = get_bloginfo( 'version' );
		$this->version =  $wp_version ."." . $theme->get('Version');
	}

	protected function get_plugin_url() {
		$this->url = plugin_dir_url(__FILE__);
	}

	public function updater() {
		include_once "update/GG_auto_update.php";
		$version = get_file_data(__FILE__,array("Version"));
		$version = $version[0];
		$remote_path = "http://demo.gresak.net/ssswp/update.php";
		$plugin = "ssswp/ssswp.php";
		new GG_auto_update($version,$remote_path,$plugin); 
	}


	public static function instance() {
		if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
	}
}
