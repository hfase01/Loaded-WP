<?php
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

  // Info: http://wordpress.org/support/topic/fatal-error-call-to-undefined-function-wp_verify_nonce
  
  if ( defined('ABSPATH') ){
  
    require_once(ABSPATH .'wp-includes/pluggable.php'); 
    require_once(ABSPATH .'wp-includes/l10n.php'); 
    
  }else{
  
    require_once( '../../../wp-includes/pluggable.php'); 
    require_once( '../../../wp-includes/l10n.php'); 
  }
    
  function twiz_ajax_callback(){
  
   global $wpdb;
   
    /* Nonce security (number used once) */
    $_POST['twiz_nonce'] = (!isset($_POST['twiz_nonce'])) ? '' : $_POST['twiz_nonce'] ;
    $nonce = $_POST['twiz_nonce'];
    
    if (!wp_verify_nonce($nonce, 'twiz-nonce') ) {
    
        die("Security check"); 
    }

    /* actions */
    $_POST['twiz_action'] = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'] ;   
    $action = $_POST['twiz_action'];
    
    $_POST['twiz_section_id'] = (!isset($_POST['twiz_section_id'])) ? '' : $_POST['twiz_section_id'] ;

    $htmlresponse = '';

    switch(esc_attr(trim($action))){ 
    
        case Twiz::ACTION_MENU:
        
            $_POST['twiz_order_by'] = (!isset($_POST['twiz_order_by'])) ? '' : esc_attr($_POST['twiz_order_by']) ;
            
            $twiz_section_id = esc_attr($_POST['twiz_section_id']);
                
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlList($twiz_section_id, '' , $_POST['twiz_order_by']);

            break;
            
        case Twiz::ACTION_MENU_STATUS:
            
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
        
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->switchMenuStatus($twiz_id);

            break;    
        case Twiz::ACTION_VMENU_STATUS:

            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->switchMenuStatus($twiz_id);

            break;  

        case Twiz::ACTION_GET_MENU:
       
            // Needed for translation
            load_default_textdomain();
            
            $twiz_section_id = $_POST['twiz_section_id'];
            
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->getHtmlMenu($twiz_section_id);

            break; 
            
        case Twiz::ACTION_GET_VMENU:
       
            // Needed for translation
            load_default_textdomain();
            
            $twiz_section_id = $_POST['twiz_section_id'];
            
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->getHtmlVerticalMenu($twiz_section_id);

            break;  
            
        case Twiz::ACTION_SAVE_SECTION:
        
            $twiz_section_id = $_POST['twiz_section_id'];
            $twiz_current_section_id = $_POST['twiz_current_section_id'];
            $twiz_section_name = esc_attr(trim($_POST['twiz_section_name']));
            $twiz_output_choice = esc_attr($_POST['twiz_output_choice']);
            $twiz_custom_logic = $_POST['twiz_custom_logic'];
			$twiz_shortcode = $_POST['twiz_shortcode'];

            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->saveSectionMenu($twiz_section_id, $twiz_section_name, $twiz_current_section_id, $twiz_output_choice, $twiz_custom_logic, $twiz_shortcode);
            
            break;
            
        case Twiz::ACTION_GET_MULTI_SECTION:

            // Needed for translation
            load_default_textdomain();
            
            $twiz_section_id = esc_attr($_POST['twiz_section_id']);
            $twiz_action_lbl = esc_attr($_POST['twiz_action_lbl']);
            
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->GetHtmlMultiSectionBoxes($twiz_section_id, $twiz_action_lbl);
            
            break;            

        case Twiz::ACTION_DELETE_SECTION:
        
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
        
            $myTwizMenu  = new TwizMenu();
            
            $htmlresponse = $myTwizMenu->deleteSectionMenu($twiz_section_id);
            
            break;
            
        case Twiz::ACTION_SAVE:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            
            $myTwiz  = new Twiz();
            
            if($saved_id = $myTwiz->save($twiz_id)){ 
            
                $htmlresponse = $myTwiz->getHtmlList($twiz_section_id, $saved_id);        
                
            }else{
            
                $htmlresponse = $myTwiz->getHtmlForm();
                
            }
            break;
            
        case Twiz::ACTION_CANCEL:
        
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlList($twiz_section_id);       
            
            break;

        case Twiz::ACTION_OPTIONS:
        
            $twiz_charid = esc_attr(trim($_POST['twiz_charid']));
            
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlOptionList($twiz_charid);
            
            break;                        
            
        case Twiz::ACTION_VIEW:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            
            $myTwiz  = new Twiz();
            
            $htmlresponse = $myTwiz->getHtmlView($twiz_id);    
            
            break;
            
        case Twiz::ACTION_NEW:
        
            $myTwiz  = new Twiz();
            
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            $htmlresponse = $myTwiz->getHtmlForm('', Twiz::ACTION_NEW, $twiz_section_id);     
            
            break;

        case Twiz::ACTION_EDIT:
            
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            
            $myTwiz  = new Twiz();
            
            if($htmlresponse = $myTwiz->getHtmlForm($twiz_id, Twiz::ACTION_EDIT, $twiz_section_id)){}else{
                $htmlresponse = $myTwiz->getHtmlList($twiz_section_id);
            }
            
            break;
            
        case Twiz::ACTION_EDIT_TD:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_value = esc_attr(trim($_POST['twiz_value']));
            $twiz_column = esc_attr(trim($_POST['twiz_column']));
            
            $myTwiz  = new Twiz();
                       
            if(($saved = $myTwiz->saveValue($twiz_id, $twiz_column, $twiz_value)) // insert or update
            or($saved=='0')){ // success, but no differences
            
                switch($twiz_column){
                
                    case "duration":
                    
                       $htmlresponse = $myTwiz->formatDuration($twiz_id);
                       break;
                       
                    case 'delay':
                    
                       $htmlresponse = $twiz_value;
                       break;
                       
                    case 'on_event':
                    
                       $htmlresponse = $myTwiz->format_on_event($twiz_value);
                       break;
                }
            }
        
            break;
            
         case Twiz::ACTION_COPY:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            
            $myTwiz  = new Twiz();
            
            if($htmlresponse = $myTwiz->getHtmlForm($twiz_id, Twiz::ACTION_COPY, $twiz_section_id)){}else{
                $htmlresponse = $myTwiz->getHtmlList($twiz_section_id);
            }
    
            break;
            
        case Twiz::ACTION_DELETE:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->delete($twiz_id);
    
            break;

        case Twiz::ACTION_STATUS:
        
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->switchStatus($twiz_id);    
            
            break;    

        case Twiz::ACTION_GLOBAL_STATUS:
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->switchGlobalStatus();    
            
            break;    
            
        case Twiz::ACTION_EXPORT:
        
            $twiz_section_id = esc_attr(trim($_POST['twiz_section_id']));
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
        
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->export($twiz_section_id, $twiz_id);    
            
            break;             
            
        case Twiz::ACTION_LIBRARY:

            $myTwizLibrary  = new TwizLibrary();
            $htmlresponse = $myTwizLibrary->getHtmlLibrary();    
            
            break;    
            
        case Twiz::ACTION_LIBRARY_STATUS:
            
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            
            $myTwizLibrary  = new TwizLibrary();
            $htmlresponse = $myTwizLibrary->switchLibraryStatus($twiz_id);    
            
            break;   
            
       case Twiz::ACTION_DELETE_LIBRARY:
            
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            
            $myTwizLibrary  = new TwizLibrary();
            $htmlresponse = $myTwizLibrary->deleteLibrary($twiz_id);    
            
            break; 
            
       case Twiz::ACTION_ORDER_LIBRARY:
            
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_order = esc_attr(trim($_POST['twiz_order']));
            
            $myTwizLibrary  = new TwizLibrary();
            
            if( $updated =  $myTwizLibrary->updateLibraryOrder($twiz_id, $twiz_order) ) {
                $htmlresponse = $myTwizLibrary->getHtmlLibrary(); 
            }
            
            break;       

        case Twiz::ACTION_ADMIN:
            
            // Needed for translation
            load_default_textdomain();
            
            $myTwizAdmin  = new TwizAdmin();
            $htmlresponse = $myTwizAdmin->getHtmlAdmin();    
            
            break;     

        case Twiz::ACTION_SAVE_ADMIN:
           
            $twiz_settings[Twiz::KEY_OUTPUT] = esc_attr(trim($_POST['twiz_slc_output']));
            $twiz_settings[Twiz::KEY_OUTPUT_COMPRESSION] = esc_attr(trim($_POST['twiz_output_compression']));
            $twiz_settings[Twiz::KEY_REGISTER_JQUERY] = esc_attr(trim($_POST['twiz_register_jquery']));
            $twiz_settings[Twiz::KEY_NUMBER_POSTS] = esc_attr(trim($_POST['twiz_number_posts']));
            $twiz_settings[Twiz::KEY_DELETE_ALL] = esc_attr(trim($_POST['twiz_delete_all']));
            $twiz_settings[Twiz::KEY_MIN_ROLE_LEVEL] = esc_attr(trim($_POST['twiz_min_rolelevel']));
            $twiz_settings[Twiz::KEY_STARTING_POSITION] = esc_attr(trim($_POST['twiz_starting_position']));

            $myTwizAdmin  = new TwizAdmin();
            $htmlresponse = $myTwizAdmin->saveAdmin($twiz_settings);    
            
            break;             
            
        case Twiz::ACTION_SAVE_SKIN:
           
            $skin = Twiz::SKIN_PATH.esc_attr(trim($_POST['twiz_skin']));  
            $code = update_option('twiz_skin', $skin);
            
            break;
            
        case Twiz::ACTION_GET_MAIN_ADS:
           
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->getHtmlAds();    
            
            break;           
            
        case Twiz::ACTION_GET_EVENT_LIST:
           
            $twiz_id = esc_attr(trim($_POST['twiz_id']));
            $twiz_event = esc_attr(trim($_POST['twiz_event']));
           
            $myTwiz  = new Twiz();
            $htmlresponse = $myTwiz->getHTMLEventList($twiz_event, $twiz_id, 'twiz-select-event-list');
            
            break;
    }
    
    echo($htmlresponse); // output the result
    die();
 }
?>