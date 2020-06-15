<?php
/**
 * @package Woo Plugin
 * @version 1.0.0
 */
/*
Plugin Name: Woo File Upload Plugin
Plugin URI: http://wordpress.org/plugins/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Hossain Md Awlad
Version: 1.0.0
Author URI: http://fb/hossain.md.awlad
Text Domain: woopn
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'WOOPN_VERSION', '1.0.0' );
define( 'WOOPN__MINIMUM_WP_VERSION', '4.0' );


class WooPlugin{
	// enque scripts 
	function enque(){
		wp_enqueue_style('woopnstyles',plugins_url('/assets/css/styles.css',__FILE__));
		wp_enqueue_script('woopnscripts',plugins_url('/assets/js/scripts.js',__FILE__));
	}
	function register(){
		add_action('admin_enqueue_scripts',array($this,'enque'));
		add_action('admin_menu',array($this,'add_admin_pages'));
		add_action( 'admin_init', array($this,'woopn_settings_init') );
	}
	function add_admin_pages(){
		add_menu_page('Woo Plugin','Woops','manage_options','wooplugin',array($this,'wooplugin_index'),'dashicons-store', 110);
	}
	function wooplugin_index(){
		require_once plugin_dir_path(__FILE__).'view/admin/wooplugin_index.php';
	}
	
	function woopn_settings_init(  ) { 

		register_setting( 'pluginPage', 'woopn_settings' );

		add_settings_section(
			'woopn_pluginPage_section', 
			__( 'Your section description', 'woopn' ), 
			array($this,'woopn_settings_section_callback'), 
			'pluginPage'
		);

		add_settings_field( 
			'woopn_select_field_0', 
			__( 'Select the product', 'woopn' ), 
			array($this,'woopn_select_field_0_render'), 
			'pluginPage', 
			'woopn_pluginPage_section' 
		);

		add_settings_field( 
			'woopn_select_field_1', 
			__( 'Select the custom page', 'woopn' ), 
			array($this,'woopn_select_field_1_render'), 
			'pluginPage', 
			'woopn_pluginPage_section' 
		);

		add_settings_field( 
			'woopn_text_field_2', 
			__( 'Settings field description', 'woopn' ), 
			array($this,'woopn_text_field_2_render'), 
			'pluginPage', 
			'woopn_pluginPage_section' 
		);


	}
	function woopn_select_field_0_render(  ) { 

		$options = get_option( 'woopn_settings' );
		$args = array(
			'post_type' => 'product',
			'status' => 'publish',
			);
		$products = new WP_Query( $args );

		// $pr = wc_get_products(array());
		// print_r('<pre>');
		// var_dump($pr);
		?>
		<select name='woopn_settings[woopn_select_field_0]'>
		<?php
		if($products->have_posts()){
			while($products->have_posts()):
				$products->the_post();
				global $product; 
		?>
			<option value='<?php echo $product->get_id();?>' <?php selected( $options['woopn_select_field_0'], $product->get_id() ); ?>><?php echo the_title();?></option>
		<?php
			endwhile;
		wp_reset_postdata();
		}
		
		?>
			
		</select>
	<?php
	}
	function woopn_select_field_1_render(  ) { 

		$options = get_option( 'woopn_settings' );
		$args = array(
			'post_type' => 'Page'
			);
		$loop = new WP_Query( $args );
		?>
		<select name='woopn_settings[woopn_select_field_1]'>
		<?php
		if($loop->have_posts()){
			while($loop->have_posts()):
				$loop->the_post();
		?>
			<option value='<?php echo get_the_ID();?>' <?php selected( $options['woopn_select_field_1'], get_the_ID() ); ?>><?php echo the_title();?></option>
		<?php
		endwhile;
		}
		wp_reset_postdata();
		?>
		</select>
	<?php
	}
	function woopn_text_field_2_render(  ) { 

		$options = get_option( 'woopn_settings' );
		?>
		<input type='text' name='woopn_settings[woopn_text_field_2]' value='<?php echo $options['woopn_text_field_2']; ?>'>
		<?php

	}
	function woopn_settings_section_callback(  ) { 

		echo __( 'This section description', 'woopn' );

	}

	// activate
	function activate(){
		add_option( 'WOOPN_VERSION', WOOPN_VERSION );
		flush_rewrite_rules();
	}
	//deactivate
	function deactivate(){
		delete_option('WOOPN_VERSION');
		flush_rewrite_rules();
	}


}

if(class_exists('WooPlugin')){
	$wooplugin = new WooPlugin();
	$wooplugin->register();
	// activation 
	register_activation_hook(__FILE__,array($wooplugin, 'activate'));

	// deactivation 
	register_deactivation_hook( __FILE__, array($wooplugin,'deactivate') );
}

class WooPluginFront{
	function enque(){
		// wp_enqueue_style('woopnstyles',plugins_url('/assets/css/styles.css',__FILE__));
		wp_enqueue_script('woopn_main_scripts',plugins_url('/assets/js/main_scripts.js',__FILE__),array( 'jquery' ));
	}
	function target_product(){
		$options = get_option( 'woopn_settings' );
		return $options['woopn_select_field_0'];
	}
	function upload_page(){
		$options = get_option( 'woopn_settings' );
		return $options['woopn_select_field_1'];
	}
	function register(){
		add_action('wp_enqueue_scripts',array($this,'enque'));
		add_action('woocommerce_thankyou', array($this,'redirect_to_custom_page'), 10, 1);
	}
	function redirect_to_custom_page( $order_id ) {
		if ( ! $order_id ){
			return;
		}
		echo '<h1>'.$this->target_product().'</h1>';
		echo '<h1>'.$order_id.'</h1>';
		$order = wc_get_order($order_id);
		foreach ($order->get_items() as $item_key => $item ){
			$item_id = $item->get_product_id();
			if( $item_id == $this->target_product()){
				echo '<h1>Please go to the page: <a href="'.get_permalink($this->upload_page()).'">'.get_the_title( $this->upload_page() ).'</a></h1>';
			}
		}
	}

}
if(class_exists('WooPluginFront')){
	$woopluginfront = new WooPluginFront();
	$woopluginfront->register();
	// $t_p = $woopluginfront->target_product();
	// $u_p = $woopluginfront->upload_page();
	// print_r($u_p);
}

require_once plugin_dir_path(__FILE__).'inc/page_templater.php';