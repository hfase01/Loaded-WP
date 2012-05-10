<?php
/*
Plugin Name: The Welcomizer
Version: 1.4.4.1
Plugin URI: http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer
Description: This plugin allows you to animate your blog using jQuery effects. (100% AJAX) + .js/.css Includer.
Author: S&#233;bastien Laframboise
Author URI: http://www.sebastien-laframboise.com
License: GPL2
*/

/*  Copyright 2012  Sébastien Laframboise  (email:wordpress@sebastien-laframboise.com)

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

    /***********************
    * --- The Twiz Class ---
    ***********************/

    require_once(dirname(__FILE__).'/includes/twiz.class.php'); 
    require_once(dirname(__FILE__).'/includes/twiz.library.class.php');     
    require_once(dirname(__FILE__).'/includes/twiz.output.class.php'); 
    require_once(dirname(__FILE__).'/twiz-ajax.php');   

    /******************
    * --- Functions ---
    *******************/
  
    
    // Create the necessary for the installation
    function twizInstall() {
        
        $myTwiz  = new Twiz();
        $ok = $myTwiz->install();
    }
    
    // uninstall the plugin, drop table etc... 
    function twizUninstall() {
    
        $array_admin = get_option('twiz_admin');
        
        // Check admin option
        if( $array_admin[Twiz::KEY_DELETE_ALL] == '1' ) {
        
            $myTwiz  = new Twiz();
            $ok = $myTwiz->uninstall();
        }
    }

    // Database version check 
    function twizUpdateDbCheck() {
    
        $myTwiz  = new Twiz();
       
        $dbversion = get_option('twiz_db_version');

        if( $dbversion  != $myTwiz->dbVersion ){
        
            $ok = $myTwiz->install();
        }
    }
    
    // Add a menu link under theme menu.
    function twizAddLinkAdminMenu() {
    
        $array_admin = get_option('twiz_admin'); // get min access level
        
        add_theme_page(__('The Welcomizer', 'the-welcomizer'), __('The Welcomizer', 'the-welcomizer'), $array_admin[Twiz::KEY_MIN_ROLE_LEVEL], 'the-welcomizer', 'twizDisplayMainPage');
    }
        
    // Admin page
    function twizDisplayMainPage() {     
    
        $myTwiz = new Twiz();
        echo($myTwiz->twizIt());
    }
    
    // GenerateOutput code
    function twizGenerateOutput() {
    
        $myTwizOutput  = new TwizOutput();
        echo($myTwizOutput->generateOutput());
    }
    
	// do short code
	function twizReplaceShortCode( $shortcode = '' ){
	
        $admin_option = get_option('twiz_admin');
		
        $myTwizOutput  = new TwizOutput($shortcode['id']);
		
        return($myTwizOutput->generateOutput());		
	}
	
    function twizInit(){
		
        $gstatus = get_option('twiz_global_status');
     
        if (( !is_admin() ) and ( $gstatus == '1' ) 
        and (!preg_match("/wp-admin/i", $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]))
        and (!preg_match("/wp-login/i", $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]))
        ) {
			
            $myTwizLibrary  = new TwizLibrary();
            
            // get output setting
            $admin_option = get_option('twiz_admin');
            
            if( $admin_option[Twiz::KEY_OUTPUT] !=  '' ){

				add_shortcode('twiz','twizReplaceShortCode');
				add_filter('widget_text', 'do_shortcode', 11);
				
                // add the Frontend generated code
                add_filter($admin_option[Twiz::KEY_OUTPUT], 'twizGenerateOutput');
            }
            
            if( $admin_option[Twiz::KEY_REGISTER_JQUERY] ==  '1' ){
     
                // register frontend default jQuery lib 
                wp_deregister_script( 'jquery' );  
                wp_register_script( 'jquery', includes_url().'js/jquery/jquery.js');  
                wp_enqueue_script( 'jquery' );  
            }
            
            foreach( $myTwizLibrary->array_library as $key => $value ){
            
                if( $value[Twiz::F_STATUS] == "1" ){
                
                    $file = Twiz::IMPORT_PATH . $value[Twiz::KEY_FILENAME];
                    
                    if( @file_exists( WP_CONTENT_DIR.$file ) ) {
                    
                        $fileinfo = pathinfo(WP_CONTENT_URL.$file);
                        
                        switch($fileinfo['extension']){
                        
                            // Enqueue js files
                            case Twiz::EXT_JS: 
                            
                                wp_deregister_script( 'the-welcomizer'.$key );
                                wp_register_script( 'the-welcomizer'.$key, WP_CONTENT_URL.$file);
                                wp_enqueue_script( 'the-welcomizer'.$key );
                            
                                break;
                                
                            // Enqueue css files
                            case Twiz::EXT_CSS:
                            
                                wp_enqueue_style('twiz-css-'.str_replace('.'.Twiz::EXT_CSS, '', $value[Twiz::KEY_FILENAME] ), WP_CONTENT_URL.$file , __FILE__ );
                                
                                break;
                        }
                    }
                }
            }
        }
    }
    
    function twizAdminEnqueueScripts(){
               
        $myTwiz  = new Twiz();
        $skinurl = get_option('twiz_skin');

        // Enqueue default stylesheet
        wp_enqueue_style('twiz-'.$myTwiz->cssVersion.'-a-'.Twiz::DEFAULT_SKIN, plugins_url(Twiz::SKIN_PATH.Twiz::DEFAULT_SKIN.'/twiz-style.css', __FILE__ ));
 
        // Current skin
        wp_enqueue_style('twiz-'.$myTwiz->cssVersion.'-a', plugins_url($skinurl.'/twiz-style.css', __FILE__ ));
        
        wp_enqueue_style('twiz-css-b', plugins_url('includes/import/client/fileuploader.css', __FILE__ ));

        // Admin Ajax script
        wp_enqueue_script( 'twiz-ajax-request', plugin_dir_url( __FILE__ ) . 'twiz-ajax.js.php', array( 'jquery' ) ); 
        
        // Fileuploader
        wp_enqueue_script( 'twiz-file-uploader', plugin_dir_url( __FILE__ ) . 'includes/import/client/fileuploader.js', array( 'jquery' ) );   
    }
    
    /****************
    * --- Actions ---
    *****************/

    // register installation hooks and action
    register_activation_hook( __FILE__,  'twizInstall' );
    register_deactivation_hook( __FILE__,  'twizUninstall' );    
     
    // Enqueue style in admin welcomizer page only
    if( ( is_admin() ) 
    and (!preg_match("/plugins.php/i", $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"])) 
    and (!preg_match("/plugin-install.php/i", $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"])) 
    and (!preg_match("/update.php/i", $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"])) 
    and ((preg_match("/the-welcomizer/i", $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]))
    or (preg_match("/admin-ajax/i", $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]))
    or (preg_match("/php.php/i", $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]))
    )){

        $_POST['page'] = (!isset($_POST['page'])) ? '' : $_POST['page'];

        // Set the multi-language file, english is the standard.
        load_plugin_textdomain( 'the-welcomizer', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' ); 
        

        // Ajax callback
        add_action('wp_ajax_my_action', 'twiz_ajax_callback');
        
        $_POST['action'] = (!isset($_POST['action'])) ? '' : $_POST['action'];
        $_POST['twiz_action'] = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'];
         
        // Do WP Ajax
        if(($_POST['action']!='')and($_POST['twiz_action']!='')){
        
            do_action('wp_ajax_my_action', $_POST['action']);
            
        }else{
        
            // (for the admin dashboard)
            add_action('admin_enqueue_scripts', 'twizAdminEnqueueScripts');
        }
    }
    
    // dbversion check 
    add_action('plugins_loaded', 'twizUpdateDbCheck');
    
    // (for the frontend)
    add_action('wp_enqueue_scripts', 'twizInit');
    
    // Add the menu link
    add_action('admin_menu', 'twizAddLinkAdminMenu');
?>