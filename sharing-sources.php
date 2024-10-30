<?php

$standalone = dirname( __FILE__ ).'/../sharedaddy/sharing-sources.php';
$jetpack = dirname( __FILE__ ).'/../jetpack/modules/sharedaddy/sharing-sources.php';
if ( file_exists( $standalone ) ) {
	include_once $standalone;
	$sharedaddy_textdomain = 'sharedaddy';
}
elseif ( file_exists( $jetpack ) ) {
	include_once $jetpack;
	$sharedaddy_textdomain = 'jetpack';
}

if ( class_exists( 'Sharing_Advanced_Source' ) ) :

class Share_MixiCheck extends Sharing_Advanced_Source {
	private $smart = false;
	private $button = 'button-1';
	private $check_key = null;
	private $ogp_use = false;
	private $ogp_ns = 'og';
	
	public function __construct( $id, array $settings ) {
		parent::__construct( $id, $settings );
		
		if ( isset( $settings['smart'] ) )
			$this->smart = $settings['smart'];
		
		if ( isset( $settings['button'] ) )
			$this->button = $settings['button'];
		
		if ( isset( $settings['check_key'] ) )
			$this->check_key = $settings['check_key'];
		
		if ( isset( $settings['ogp_use'] ) )
			$this->ogp_use = $settings['ogp_use'];
		
		if ( isset( $settings['ogp_ns'] ) )
			$this->ogp_ns = $settings['ogp_ns'];
	}
	
	public function get_name(){
		return __( 'mixi Check', 'mixi-check' );
	}
	
	public function has_custom_button_style() {
		return $this->smart;
	}
	
	public function display_header(){
		if ( $this->ogp_use ) {
			$ns = $this->ogp_ns;
			if ( is_home() ) {
				echo '<meta property="'.$ns.':title" content="' . get_bloginfo( 'title' ) . '" />'.PHP_EOL;
				echo '<meta property="'.$ns.':description" content="' . get_bloginfo( 'description' ) . '" />'.PHP_EOL;
				echo '<meta property="'.$ns.':type" content="blog" />'.PHP_EOL;
				echo '<meta property="'.$ns.':url" content="' . home_url( '/', 'http' ) . '" />'.PHP_EOL;
			}
			elseif ( is_singular() ) {
				the_post();
				$post = get_post( get_the_ID() );
				if ( has_excerpt() ) {
					$description = strip_tags( $post->post_excerpt );
				} else {
					$description = strip_tags( strip_shortcodes( str_replace( array("\n","\r","\t"), '', $post->post_content ) ) );
					$description = mb_substr( $description, 0 , 100 );
				}
				echo '<meta property="'.$ns.':title" content="' . $post->post_title . '" />'.PHP_EOL;
				echo '<meta property="'.$ns.':description" content="' . $description . '" />'.PHP_EOL;
				echo '<meta property="'.$ns.':type" content="article" />'.PHP_EOL;
				echo '<meta property="'.$ns.':url" content="' . get_permalink() . '" />'.PHP_EOL;
				
				//成年コンテンツ
				//echo '<meta property="mixi:content-rating" content="1" />'.PHP_EOL;
				
				//サムネイル画像
				$images = array();
				if ( has_post_thumbnail() ) {
					list( $src ) = wp_get_attachment_image_src( get_post_thumbnail_id() );
					$images[] = $src;
				}
				preg_match_all( "/\<img[^\>]*[src] *= *[\"\']{0,1}([^\"\'\ >]*)/i", $post->post_title, $matches );
				$images = array_merge( $images, $matches[1] );
				foreach ( $images as $image ) {
					echo '<meta property="'.$ns.':image" content="'.$image.'" />'.PHP_EOL;
				}
				
				//自動取得の制御
				//echo '<meta name="mixi-check-robots" CONTENT="notitle, nodescription, noimage">'.PHP_EOL;
				
				//デバイス別URLの指定 linkでもmetaでも
				//echo '<link rel="mixi-check-alternate" type="text/html" media="mixi-device-(smartphone|mobile|docomo|au|softbank)" href="[記事URL]" />'.PHP_EOL;
				//echo '<meta property="mixi:device-(smartphone|mobile|docomo|au|softbank)" content="[記事URL]" />'.PHP_EOL;
				
				rewind_posts();
			}
			else {
				$title = rtrim( wp_title( '|', false, 'right' ), ' |' );
				$parse_url = parse_url( home_url( '/' ) );
				$url = home_url( preg_replace( '|^'.$parse_url[ 'path' ].'|', '', $_SERVER[ 'REQUEST_URI' ] ), 'http' );
				echo '<meta property="'.$ns.':title" content="' . $title . '" />'.PHP_EOL;
				echo '<meta property="'.$ns.':type" content="blog" />'.PHP_EOL;
				echo '<meta property="'.$ns.':url" content="' . $url . '" />'.PHP_EOL;
			}
		}
	}
	
	public function get_display( $post ) {
		if ( !$this->check_key ) return __( 'Not set mixi check key.', 'mixi-check' );
		if ( $this->smart )
			return '<a href="http://mixi.jp/share.pl" class="mixi-check-button" data-key="'.$this->check_key.'" data-url="'.get_permalink( $post->ID ).'" data-button="'.$this->button.'">Check</a><script type="text/javascript" src="http://static.mixi.jp/js/share.js"></script>';
		else
			return $this->get_link( get_permalink( $post->ID ), __( 'mixi Check', 'mixi-check' ), __( 'Click to share on mixi Check', 'mixi-check' ), 'share=mixi-check' );
	}
	
	public function get_link( $url, $text, $title, $query = '' ) {
		$klasses = array( 'share-'.$this->get_class() );
		
		if ( $this->button_style == 'icon' || $this->button_style == 'icon-text' )
			$klasses[] = 'share-icon';
		
		if ( $this->button_style == 'icon' ) {
			$text = '';
			$klasses[] = 'no-text';
		}
		
		if ( $this->button_style == 'text' )
			$klasses[] = 'no-icon';
		
		if ( !empty( $query ) ) {
			if ( stripos( $url, '?' ) === false )
				$url .= '?'.$query;
			else
				$url .= '&amp;'.$query;
		}
		
		$javascript = "window.open('".$url."','share',['width=632','height=456','location=yes','resizable=yes','toolbar=no','menubar=no','scrollbars=no','status=no'].join(','));";
		
		return sprintf( '<a href="javascript:void(0);" onclick="%s" class="%s" title="%s">%s</a>', $javascript, implode( ' ', $klasses ), $title, $text );
	}
	
	public function process_request( $post, array $post_data ) {
		$post_title = $post->post_title;
		$post_link = apply_filters( 'sharing_permalink', get_permalink( $post->ID ), $post->ID );
		
		$mixi_check_url = '';	
		$mixi_check_url = 'http://mixi.jp/share.pl?u=' . urlencode( $post_link ) . '&k=' . $this->check_key;
		
		// Record stats
		parent::process_request( $post, $post_data );
		
		// Redirect to Twitter
		wp_redirect( $mixi_check_url );
		die();
	}
	
	public function update_options( array $data ) {
		$this->smart = false;
		$this->button = 'button-1';
		$this->check_key = null;
		$this->ogp_use = false;
		$this->ogp_ns = 'og';
		
		if ( isset( $data['smart'] ) )
			$this->smart = $data['smart'];
		
		if ( isset( $data['button'] ) )
			$this->button = $data['button'];
		
		if ( isset( $data['check_key'] ) )
			$this->check_key = $data['check_key'];
		
		if ( isset( $data['ogp_use'] ) )
			$this->ogp_use = true;
		
		if ( isset( $data['ogp_ns'] ) )
			$this->ogp_ns = $data['ogp_ns'];
	}
	
	public function get_options() {
		return array(
			'smart'     => $this->smart,
			'button'    => $this->button,
			'check_key' => $this->check_key,
			'ogp_use'   => $this->ogp_use,
			'ogp_ns'    => $this->ogp_ns,
		);
	}
	
	public function display_options() {
		global $sharedaddy_textdomain;
?>
	<div class="input">
		<label><?php _e( 'mixi check key', 'mixi-check' ); ?><br />
		<input type="text" name="check_key" size="10" value="<?php echo esc_attr( $this->check_key ); ?>" /></label>
		<input class="button-secondary" type="submit"value="<?php _e( 'Save', $sharedaddy_textdomain ); ?>" />
	</div>
	<div class="input">
		<label><input name="smart" type="checkbox"<?php if ( $this->smart ) echo ' checked="checked"'; ?>/>
		<?php _e( 'Use smart button', $sharedaddy_textdomain ); ?></label><br />
		<label><?php _e( 'Button', 'mixi-check' ); ?></label>
		<select name="button">
			<option value="button-1"<?php if ( $this->button == 'button-1' ) echo ' selected="selected"'; ?>><?php _e( 'button-1', 'mixi-check' ); ?></option>
			<option value="button-2"<?php if ( $this->button == 'button-2' ) echo ' selected="selected"'; ?>><?php _e( 'button-2', 'mixi-check' ); ?></option>
			<option value="button-3"<?php if ( $this->button == 'button-3' ) echo ' selected="selected"'; ?>><?php _e( 'button-3', 'mixi-check' ); ?></option>
			<option value="button-4"<?php if ( $this->button == 'button-4' ) echo ' selected="selected"'; ?>><?php _e( 'button-4', 'mixi-check' ); ?></option>
			<option value="button-5"<?php if ( $this->button == 'button-5' ) echo ' selected="selected"'; ?>><?php _e( 'button-5', 'mixi-check' ); ?></option>
		</select>
	</div>
	<div class="input">
		<label><input name="ogp_use" type="checkbox"<?php if ( $this->ogp_use ) echo ' checked="checked"'; ?>/>
		<?php _e( 'OGP meta to output', 'mixi-check' ); ?></label><br />
		<label><?php _e( 'Namespace', 'mixi-check' ); ?></label>
		<select name="ogp_ns">
			<option value="og"<?php if ( $this->ogp_ns == 'og' ) echo ' selected="selected"'; ?>><?php _e( 'og', 'mixi-check' ); ?></option>
			<option value="mixi"<?php if ( $this->ogp_ns == 'mixi' ) echo ' selected="selected"'; ?>><?php _e( 'mixi', 'mixi-check' ); ?></option>
		</select>
	</div>
<?php
	}
	
	public function display_preview() {
?>
	<div class="option option-smart-<?php echo $this->smart ? 'on option-'.$this->button : 'off'; ?>">
		<?php
			if ( !$this->smart ) {
				if ( $this->button_style == 'text' || $this->button_style == 'icon-text' )
					echo $this->get_name();
				else
					echo '&nbsp;';
			}
		?>
	</div>
<?php
	}
}

endif;
