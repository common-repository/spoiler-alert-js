<?php
/*
Plugin Name: Spoiler alert JS
Plugin URI: http://www.arthos.fr/
Description: Add a blur on spoiler alerts ; based on Joshua Hull's spoiler-alert JS (https://github.com/joshbuddy/spoiler-alert)
Version: 1.0
Author: Luc Delaborde
Author Email: contact@arthos.fr
License:

  Copyright 2011 Luc Delaborde (contact@arthos.fr)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

class SpoileralertJS {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'Spoiler alert JS';
	const slug = 'spoiler_alert_js';
	
	/**
	 * Constructor
	 */
	function __construct() {
		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_spoiler_alert_js' ) );
	}
  
	/**
	 * Runs when the plugin is initialized
	 */
	function init_spoiler_alert_js() {
		// Load JavaScript and stylesheets
		$this->register_scripts_and_styles();

		// Register the shortcode [spoiler]
		add_shortcode( 'spoiler', array( &$this, 'render_shortcode' ) );

		/*
		 * Hook the shortcode
		 */
		add_action( 'init', array( &$this, 'register_shortcode' ) );
	}

	function register_shortcode() {
		add_shortcode( 'spoiler', array( &$this, 'render_shortcode' ) );
	}

	function render_shortcode( $atts, $content = null ) {
		// Extract the attributes
		extract(shortcode_atts(array(
			'max' 		=> '4',
			'partial'	=> '2'
			), $atts));

		$rand = mt_rand();

		return '<spoiler class="spoiler-' . $rand . '">' . $content . '</spoiler>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$(".spoiler-' . $rand . '").spoilerAlert({max : ' . $max . ', partial : ' . $partial . '});
				});
			</script>';
	}
  
	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	private function register_scripts_and_styles() {
		if ( is_admin() ) {

		} else {
			$this->load_file( self::slug . '-script', '/js/spoiler.min.js', true );
		} // end if/else
	} // end register_scripts_and_styles
	
	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @name	The 	ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	private function load_file( $name, $file_path, $is_script = false ) {

		$url = plugins_url($file_path, __FILE__);
		$file = plugin_dir_path(__FILE__) . $file_path;

		if( file_exists( $file ) ) {
			if( $is_script ) {
				wp_register_script( $name, $url, array('jquery') ); //depends on jquery
				wp_enqueue_script( $name );
			} else {
				wp_register_style( $name, $url );
				wp_enqueue_style( $name );
			} // end if
		} // end if

	} // end load_file
  
} // end class
new SpoileralertJS();

?>