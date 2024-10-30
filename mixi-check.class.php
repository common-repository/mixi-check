<?php
class MixiCheck
{
	static public function add_style(){
		echo '<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'mixi-check.css?v='.MIXI_CHECK_PLUGIN_VERSION.'" type="text/css" />'.PHP_EOL;
	}
	
	static public function add_admin_style(){
		wp_enqueue_style( 'mixi-check', plugin_dir_url( __FILE__ ).'admin.css', false, MIXI_CHECK_PLUGIN_VERSION );
	}
	
	static public function add_sharing_service( $services ) {
		$services[ 'mixi-check' ] = 'Share_MixiCheck';
		return $services;
	}
	
	static public function ogp_namespace( $output ) {
		if ( !strpos( $output, 'xmlns:og' ) )
			$output.=' xmlns:og="http://ogp.me/ns#"';
		if ( !strpos( $output, 'xmlns:mixi' ) )
			$output.=' xmlns:mixi="http://mixi-platform.com/ns#"';
		return $output;
	}
	
	static public function add_plugin_links( $links ) {
		return $links;
		//$plugin_links = array();
		//$plugin_links[] = '<a>link</a>';
		//return $plugin_links + $links;
	}
	
	static public function add_plugin_meta( $links, $file ) {
		global $plugins;
		if ( $file != MIXI_CHECK_PLUGIN_NAME ) return $links;
		if ( !isset( $plugins[ 'sharedaddy/sharedaddy.php' ] ) )
			$links[] = '<a class="thickbox" title="Sharedaddy" href="plugin-install.php?tab=plugin-information&plugin=sharedaddy&TB_iframe=true"><strong>' . __( 'Install Sharedaddy', 'mixi-check' ) . '</strong></a>';
		$links[] = '<a class="thickbox" title="'.__( 'mixi Check Usage', 'mixi-check' ).'" href="/wp-content/plugins/mixi-check/mixi-check.php?page=usage&height=300&width=300">'.__( 'Usage', 'mixi-check' ).'</a>';
		return $links;
	}
}
