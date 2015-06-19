<?php
/**
Plugin Name: Yonox Add Multiple Posts
Plugin URI: http://yonoxpc.com
Description: Add multiple posts by a simple ajax method.
Version: 1.2
Author: Yonox
Author URI: http://yonoxpc.com
License: General Public License
*/

if ( !defined( 'ABSPATH' ) ) exit;

if (!defined('YNXADMP_LOCATION')) { define('YNXADMP_LOCATION', WP_PLUGIN_URL . '/'.basename(dirname(__FILE__))); }
if (!defined('YNXADMP_PATH')) { define('YNXADMP_PATH', plugin_dir_path( __FILE__ )); }

class YnxAddMultiplePosts{
  
	private $ynx_add_posts_screen_name;
	private static $instance;
	/*......*/

	static function YnxGetInstance()
	{
	  
	  if (!isset(self::$instance))
	  {
		  self::$instance = new self();
	  }
	  return self::$instance;
	}

	public function PluginMenu()
	{
		$this->ynx_add_posts_screen_name = add_menu_page(__('Yonox Add Multiple Posts','ynxadmp'),__('Yonox Add Posts','ynxadmp'), 'manage_options',__FILE__, array($this, 'YnxRenderPage'), 'dashicons-admin-page');
	}

	public function ynxadmp_admin_styles() {
	wp_enqueue_style( 'ynxadmp-admin-styles', YNXADMP_LOCATION . '/css/ynxadmp-admin-style.css' );
	wp_enqueue_script('jquery-ui', YNXADMP_LOCATION . '/js/jquery-ui.min.js', array( 'jquery') );
	wp_enqueue_script( 'ynxadmp-ajax-admin', YNXADMP_LOCATION . '/js/ynxadmp-ajax-admin.js', array( 'jquery' ) );  
	wp_localize_script( 'ynxadmp-ajax-admin', 'YnxadmpAdminAjax', 
						array( 
								'adminynxadmpajax' 	=> admin_url( 'admin-ajax.php' ),
								'ynxadmpNonce'	=> wp_create_nonce( 'ynxadmp-send-secur' )
						) );
	}
      
	public function YnxRenderPage(){
		include('ynxadmp-functions.php');
	}
	
	public function ynxadmp_langinit() {
		load_plugin_textdomain( 'ynxadmp', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}
	
	public function ynxadmpCreatePosts() {

		$nonce = $_POST['sendlocNonce']; 	
		if ( ! wp_verify_nonce( $nonce, 'ynxadmp-send-secur' ) )
			die ( __('Security Failed!','ynxadmp') );

		$ynxadmpTitle = $_POST['ynxadmpTitle'];
		$ynxadmpAuthor = $_POST['ynxadmpAuthor'];
		
		global $wpdb;
		
		if (get_magic_quotes_gpc()) {
			// Yes? Strip the added slashes
			$ynxadmpTitle = stripslashes($ynxadmpTitle);
		}
		
		$author_id = 1;
		$user_query = "SELECT ID, user_login, display_name, user_email FROM $wpdb->users WHERE ID = ".intval($ynxadmpAuthor) . " LIMIT 1";
		$users = $wpdb->get_results($user_query);
		foreach ($users AS $row) {
			$author_id = $row->ID;
		}
		
		$postTitle = trim($ynxadmpTitle);

		if (!empty ($postTitle)) {
			$ynxadmpPost = array ();
			$ynxadmpPost['post_title'] = $postTitle;
			$ynxadmpPost['post_type'] = 'post';
			$ynxadmpPost['post_content'] = '';
			$ynxadmpPost['post_status'] = 'publish';
			$ynxadmpPost['post_author'] = $author_id;
			wp_insert_post($ynxadmpPost);
		}

		exit;	
	}

	public function InitYnxadmpPlugin()
	{
	   add_action( 'admin_menu', array($this, 'PluginMenu') );
	   add_action( 'plugins_loaded', array($this, 'ynxadmp_langinit') );
	   add_action( 'admin_enqueue_scripts', array($this, 'ynxadmp_admin_styles') );
	   add_action( 'wp_ajax_addposts_ajaxfunc', array($this, 'ynxadmpCreatePosts') );
	}
  
}

if ( is_admin() ) {
	
	$YnxAddMultiplePosts = YnxAddMultiplePosts::YnxGetInstance();
	$YnxAddMultiplePosts->InitYnxadmpPlugin();
	
}
?>