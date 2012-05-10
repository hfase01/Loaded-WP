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
require_once(dirname(__FILE__).'/twiz.admin.class.php'); 
require_once(dirname(__FILE__).'/twiz.menu.class.php'); 
  
class Twiz{
    
    /* variable declaration */
    protected $table;
    protected $nonce;    
    protected $pluginName;
    protected $DEFAULT_SECTION;
    protected $import_path_message;
    protected $version;
    private $skinname;
    public $skin;
    public $dbVersion;
    public $cssVersion;
    public $pluginUrl;
    public $pluginDir;
       
    /* section constants */ 
    const DEFAULT_SECTION_HOME           = 'home';
    const DEFAULT_SECTION_EVERYWHERE     = 'everywhere';
    const DEFAULT_SECTION_ALL_CATEGORIES = 'allcategories';
    const DEFAULT_SECTION_ALL_PAGES      = 'allpages';
    const DEFAULT_SECTION_ALL_ARTICLES   = 'allarticles'; // This means allposts.
    
    /* default min role level required */
    const DEFAULT_MIN_ROLE_LEVEL = 'activate_plugins'; // http://codex.wordpress.org/Roles_and_Capabilities

    /* default skin  constant */ 
    const DEFAULT_SKIN  = '_default';
    const SKIN_PATH     = '/skins/';
    
    /* default number of posts to display */
    const DEFAULT_NUMBER_POSTS = '125'; // Last 125 posts
        
    /* element type constants */ 
    const ELEMENT_TYPE_ID    = 'id';
    const ELEMENT_TYPE_CLASS = 'class';
    const ELEMENT_TYPE_NAME  = 'name';
    
    /* status constants*/
    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';
    
    /* library order constants */
    const LB_ORDER_UP   = 'up';
    const LB_ORDER_DOWN = 'down';
    
    /* directional image suffix constants */ 
    const DIMAGE_N  = 'n';
    const DIMAGE_NE = 'ne';
    const DIMAGE_E  = 'e';
    const DIMAGE_SE = 'se';
    const DIMAGE_S  = 's';
    const DIMAGE_SW = 'sw';
    const DIMAGE_W  = 'w';
    const DIMAGE_NW = 'nw';
    
    /* action constants */ 
    const ACTION_MENU           = 'menu';
    const ACTION_MENU_STATUS    = 'menustatus';
    const ACTION_VMENU_STATUS   = 'vmenustatus';
    const ACTION_GET_MENU      = 'getmenu';
    const ACTION_GET_VMENU      = 'getvmenu';
    const ACTION_SAVE           = 'save';
    const ACTION_CANCEL         = 'cancel';
    const ACTION_OPTIONS        = 'options';
    const ACTION_VIEW           = 'view';
    const ACTION_NEW            = 'Add New';
    const ACTION_EDIT           = 'Edit';
    const ACTION_EDIT_TD        = 'tdedit';
    const ACTION_COPY           = 'Copy';
    const ACTION_DELETE         = 'delete';
    const ACTION_STATUS         = 'status';
    const ACTION_IMPORT         = 'import';
    const ACTION_EXPORT         = 'export';
    const ACTION_LIBRARY        = 'library';
    const ACTION_LIBRARY_STATUS = 'libstatus';
    const ACTION_UPLOAD_LIBRARY = 'uploadlib';
    const ACTION_GLOBAL_STATUS  = 'gstatus';
    const ACTION_SAVE_SECTION      = 'savesection';
    const ACTION_GET_MULTI_SECTION = 'getmultisection';
    const ACTION_DELETE_SECTION = 'deletesection';
    const ACTION_DELETE_LIBRARY = 'deletelib';
    const ACTION_ORDER_LIBRARY  = 'orderlib';
    const ACTION_ADMIN          = 'admin';
    const ACTION_SAVE_ADMIN     = 'adminsave';
    const ACTION_SAVE_SKIN      = 'skinsave';
    const ACTION_GET_MAIN_ADS   = 'getmainads';
    const ACTION_GET_EVENT_LIST = 'geteventlist';
    
    /* jquery common options constants */ 
    const JQ_HEIGHT            = 'height';
    const JQ_WITDH             = 'width';
    const JQ_OPACITY           = 'opacity';
    const JQ_FONTSIZE          = 'fontSize';    
    const JQ_MARGINTOP         = 'marginTop';
    const JQ_MARGINBOTTOM      = 'marginBottom';
    const JQ_MARGINLEFT        = 'marginLeft';
    const JQ_MARGINRIGHT       = 'marginRight';
    const JQ_PADDINGTOP        = 'paddingTop';
    const JQ_PADDINGBOTTOM     = 'paddingBottom';    
    const JQ_PADDINGLEFT       = 'paddingLeft';
    const JQ_PADDINGRIGHT      = 'paddingRight';
    const JQ_BORDERWIDTH       = 'borderWidth';
    const JQ_BORDERTOPWIDTH    = 'borderTopWidth';
    const JQ_BORDERBOTTOMWIDTH = 'borderBottomWidth';        
    const JQ_BORDERIGHTWIDTH   = 'borderRightWidth';
    const JQ_BORDERLEFTWIDTH   = 'borderLeftWidth';
    
    /* table field constants */ 
    const F_ID                     = 'id';   
    const F_EXPORT_ID              = 'export_id';
    const F_SECTION_ID             = 'section_id';    
    const F_STATUS                 = 'status';  
    const F_TYPE                   = 'type';  
    const F_LAYER_ID               = 'layer_id'; // TODO Rename layer_id -> element
    const F_ON_EVENT               = 'on_event';  
    const F_START_DELAY            = 'start_delay';  
    const F_DURATION               = 'duration';  
    const F_OUTPUT                 = 'output';  
    const F_OUTPUT_POS             = 'output_pos';
    const F_JAVASCRIPT             = 'javascript';
    const F_START_TOP_POS_SIGN     = 'start_top_pos_sign';        
    const F_START_TOP_POS          = 'start_top_pos';  
    const F_START_TOP_POS_FORMAT   = 'start_top_pos_format';
    const F_START_LEFT_POS_SIGN    = 'start_left_pos_sign';  
    const F_START_LEFT_POS         = 'start_left_pos';  
    const F_START_LEFT_POS_FORMAT  = 'start_left_pos_format';  
    const F_POSITION               = 'position';  
    const F_ZINDEX                 = 'zindex';  
    const F_EASING_A               = 'easing_a';
    const F_EASING_B               = 'easing_b';
    const F_MOVE_TOP_POS_SIGN_A    = 'move_top_pos_sign_a';  
    const F_MOVE_TOP_POS_A         = 'move_top_pos_a'; 
    const F_MOVE_TOP_POS_FORMAT_A  = 'move_top_pos_format_a'; 
    const F_MOVE_LEFT_POS_SIGN_A   = 'move_left_pos_sign_a';  
    const F_MOVE_LEFT_POS_A        = 'move_left_pos_a';  
    const F_MOVE_LEFT_POS_FORMAT_A = 'move_left_pos_format_a'; 
    const F_MOVE_TOP_POS_SIGN_B    = 'move_top_pos_sign_b';  
    const F_MOVE_TOP_POS_B         = 'move_top_pos_b';  
    const F_MOVE_TOP_POS_FORMAT_B  = 'move_top_pos_format_b'; 
    const F_MOVE_LEFT_POS_SIGN_B   = 'move_left_pos_sign_b'; 
    const F_MOVE_LEFT_POS_B        = 'move_left_pos_b'; 
    const F_MOVE_LEFT_POS_FORMAT_B = 'move_left_pos_format_b'; 
    const F_OPTIONS_A              = 'options_a'; 
    const F_OPTIONS_B              = 'options_b'; 
    const F_EXTRA_JS_A             = 'extra_js_a'; 
    const F_EXTRA_JS_B             = 'extra_js_b';     
 
    /* Field constants keys */
    const KEY_FILENAME = 'filename';  
    const KEY_ORDER    = 'order';  
    const KEY_TITLE    = 'title'; 
    
    /* Output constants keys */
    const KEY_OUTPUT             = 'output';
    const KEY_OUTPUT_COMPRESSION = 'output_compression';
    
    /* Default jQuery constant key */
    const KEY_REGISTER_JQUERY    = 'register_jquery';
    
    /* Minimal role level constant key */
    const KEY_MIN_ROLE_LEVEL = 'min_role_level';
    
    /* Deactivation constant key */
    const KEY_DELETE_ALL = 'delete_all';
    
    /* Number Posts to display key */
    const KEY_NUMBER_POSTS = 'number_posts';

    /* Starting position by default on add new */
    const KEY_STARTING_POSITION = 'starting_position';
    
    /* Output constants*/  
    const OUTPUT_HEADER = 'wp_head';    
    const OUTPUT_FOOTER = 'wp_footer'; 
    
    /* extension constants */
    const EXT_JS  = 'js';
    const EXT_CSS = 'css';
    const EXT_TWZ = 'twz'; 
    const EXT_TWIZ = 'twiz';
    const EXT_XML = 'xml'; 
    
    /* Format constants */
    const FORMAT_PIXEL    = 'px';
    const FORMAT_PERCENT  = '%';
    const FORMAT_EM       = 'em'; 
    const FORMAT_INCH     = 'in';
    
    /* on event constants */
    const EV_MANUAL     = 'Manually'; 
    const EV_BLUR       = 'Blur'; 
    const EV_CHANGE     = 'Change'; 
    const EV_CLICK      = 'Click'; 
    const EV_DBLCLICK   = 'DblClick';
    const EV_ERROR      = 'Error'; 
    const EV_FOCUS      = 'Focus'; 
    const EV_FOCUSIN    = 'FocusIn'; 
    const EV_FOCUSOUT   = 'FocusOut';
    const EV_HOVER      = 'Hover';
    const EV_KEYDOWN    = 'KeyDown';
    const EV_KEYPRESS   = 'KeyPress';
    const EV_KEYUP      = 'KeyUp';
    const EV_MOUSEDOWN  = 'MouseDown';
    const EV_MOUSEENTER = 'MouseEnter';
    const EV_MOUSELEAVE = 'MouseLeave';
    const EV_MOUSEMOVE  = 'MouseMove';
    const EV_MOUSEOUT   = 'MouseOut';
    const EV_MOUSEOVER  = 'MouseOver'; 
    const EV_MOUSEUP    = 'MouseUp'; 
    const EV_RESIZE     = 'Resize'; 
    const EV_SCROLL     = 'Scroll'; 
    const EV_SELECT     = 'Select'; 
    const EV_SUBMIT     = 'Submit'; 
    const EV_TOGGLE     = 'Toggle'; 
    const EV_UNLOAD     = 'Unload'; 

    /* starting position constants */
    const POS_NO_POS   = ''; 
    const POS_ABSOLUTE = 'absolute'; 
    const POS_RELATIVE = 'relative';
    const POS_FIXED    = 'fixed';
    const POS_STATIC   = 'static';
    
    /* default starting position on add new */    
    const DEFAULT_STARTING_POSITION = self::POS_RELATIVE;
        
    /* Position array */
    protected $array_position = array(self::POS_NO_POS
                                     ,self::POS_ABSOLUTE   
                                     ,self::POS_RELATIVE
                                     ,self::POS_FIXED
                                     ,self::POS_STATIC 
                                     );
    
    /* Format array */
    private $array_format = array(self::FORMAT_PIXEL     
                                 ,self::FORMAT_PERCENT  
                                 ,self::FORMAT_EM  
                                 ,self::FORMAT_INCH 
                                 );
    
    /* on event array */ 
    private $array_element_type = array(self::ELEMENT_TYPE_ID      
                                       ,self::ELEMENT_TYPE_CLASS  
                                       ,self::ELEMENT_TYPE_NAME  
                                       );
    /* section constants array */
    protected $array_default_section = array(self::DEFAULT_SECTION_HOME    
                                            ,self::DEFAULT_SECTION_EVERYWHERE  
                                            ,self::DEFAULT_SECTION_ALL_CATEGORIES
                                            ,self::DEFAULT_SECTION_ALL_PAGES 
                                            ,self::DEFAULT_SECTION_ALL_ARTICLES 
                                            );
    /* on event array */ 
    private $array_on_event = array(self::EV_MANUAL
                                   ,self::EV_BLUR      
                                   ,self::EV_CHANGE    
                                   ,self::EV_CLICK     
                                   ,self::EV_DBLCLICK  
                                   ,self::EV_ERROR      
                                   ,self::EV_FOCUS      
                                   ,self::EV_FOCUSIN    
                                   ,self::EV_FOCUSOUT   
                                   ,self::EV_HOVER      
                                   ,self::EV_KEYDOWN    
                                   ,self::EV_KEYPRESS   
                                   ,self::EV_KEYUP      
                                   ,self::EV_MOUSEDOWN  
                                   ,self::EV_MOUSEENTER 
                                   ,self::EV_MOUSELEAVE 
                                   ,self::EV_MOUSEMOVE  
                                   ,self::EV_MOUSEOUT   
                                   ,self::EV_MOUSEOVER  
                                   ,self::EV_MOUSEUP    
                                   ,self::EV_RESIZE     
                                   ,self::EV_SCROLL     
                                   ,self::EV_SELECT    
                                   ,self::EV_SUBMIT    
                                   ,self::EV_TOGGLE    
                                   ,self::EV_UNLOAD
                                   );
                                      
    /* directional array image suffix */ 
    private $array_arrows = array(self::DIMAGE_N   
                                 ,self::DIMAGE_NE    
                                 ,self::DIMAGE_E  
                                 ,self::DIMAGE_SE     
                                 ,self::DIMAGE_S    
                                 ,self::DIMAGE_SW  
                                 ,self::DIMAGE_W  
                                 ,self::DIMAGE_NW  
                                 );
   
    /* action array used to exclude ajax container */
    private $array_action_excluded = array(self::ACTION_MENU   
                                          ,self::ACTION_SAVE    
                                          ,self::ACTION_CANCEL  
                                          ,self::ACTION_NEW     
                                          ,self::ACTION_EDIT   
                                          ,self::ACTION_COPY
                                          );
                            
    /* jQuery common options array */
    private $array_jQuery_options = array(self::JQ_HEIGHT
                                         ,self::JQ_WITDH
                                         ,self::JQ_OPACITY
                                         ,self::JQ_FONTSIZE
                                         ,self::JQ_MARGINTOP
                                         ,self::JQ_MARGINBOTTOM
                                         ,self::JQ_MARGINLEFT
                                         ,self::JQ_MARGINRIGHT
                                         ,self::JQ_PADDINGTOP
                                         ,self::JQ_PADDINGBOTTOM
                                         ,self::JQ_PADDINGLEFT
                                         ,self::JQ_PADDINGRIGHT
                                         ,self::JQ_BORDERWIDTH
                                         ,self::JQ_BORDERTOPWIDTH
                                         ,self::JQ_BORDERBOTTOMWIDTH
                                         ,self::JQ_BORDERIGHTWIDTH
                                         ,self::JQ_BORDERLEFTWIDTH
                                         );
                
    /* XML MULTI-VERSION mapping values */
    private $array_twz_mapping = array(self::F_EXPORT_ID                => 'AA'
                                      ,self::F_SECTION_ID               => 'AH' 
                                      ,self::F_STATUS                   => 'BB'
                                      ,self::F_TYPE                     => 'BL' 
                                      ,self::F_LAYER_ID                 => 'CC'    
                                      ,self::F_ON_EVENT                 => 'DA'
                                      ,self::F_START_DELAY              => 'DD'
                                      ,self::F_DURATION                 => 'EE'
                                      ,self::F_OUTPUT                   => 'EG'
                                      ,self::F_OUTPUT_POS               => 'EJ'
                                      ,self::F_JAVASCRIPT               => 'EL'
                                      ,self::F_START_TOP_POS_SIGN       => 'FF'
                                      ,self::F_START_TOP_POS            => 'GG'    
                                      ,self::F_START_TOP_POS_FORMAT     => 'GF'  
                                      ,self::F_START_LEFT_POS_SIGN      => 'HH'
                                      ,self::F_START_LEFT_POS           => 'II'
                                      ,self::F_START_LEFT_POS_FORMAT    => 'IF'
                                      ,self::F_POSITION                 => 'JJ'
                                      ,self::F_ZINDEX                   => 'JL'
                                      ,self::F_EASING_A                 => 'KD'
                                      ,self::F_EASING_B                 => 'KH'
                                      ,self::F_MOVE_TOP_POS_SIGN_A      => 'KK'    
                                      ,self::F_MOVE_TOP_POS_A           => 'LL'
                                      ,self::F_MOVE_TOP_POS_FORMAT_A    => 'LF'
                                      ,self::F_MOVE_LEFT_POS_SIGN_A     => 'MM'
                                      ,self::F_MOVE_LEFT_POS_A          => 'OO'
                                      ,self::F_MOVE_LEFT_POS_FORMAT_A   => 'OF'
                                      ,self::F_MOVE_TOP_POS_SIGN_B      => 'PP'    
                                      ,self::F_MOVE_TOP_POS_B           => 'QQ'
                                      ,self::F_MOVE_TOP_POS_FORMAT_B    => 'QF'
                                      ,self::F_MOVE_LEFT_POS_SIGN_B     => 'RR'
                                      ,self::F_MOVE_LEFT_POS_B          => 'SS'
                                      ,self::F_MOVE_LEFT_POS_FORMAT_B   => 'SF'
                                      ,self::F_OPTIONS_A                => 'TT'
                                      ,self::F_OPTIONS_B                => 'UU'
                                      ,self::F_EXTRA_JS_A               => 'VV'
                                      ,self::F_EXTRA_JS_B               => 'WW'
                                      );
                                      
    /* Fields array */ 
    protected $array_fields = array(self::F_ID          
                                 ,self::F_EXPORT_ID 
                                 ,self::F_SECTION_ID          
                                 ,self::F_STATUS 
                                 ,self::F_TYPE                                   
                                 ,self::F_LAYER_ID 
                                 ,self::F_ON_EVENT                             
                                 ,self::F_START_DELAY          
                                 ,self::F_DURATION    
                                 ,self::F_OUTPUT  
                                 ,self::F_OUTPUT_POS
                                 ,self::F_JAVASCRIPT   
                                 ,self::F_START_TOP_POS_SIGN 
                                 ,self::F_START_TOP_POS           
                                 ,self::F_START_TOP_POS_FORMAT   
                                 ,self::F_START_LEFT_POS_SIGN  
                                 ,self::F_START_LEFT_POS      
                                 ,self::F_START_LEFT_POS_FORMAT 
                                 ,self::F_POSITION             
                                 ,self::F_ZINDEX     
                                 ,self::F_EASING_A     
                                 ,self::F_EASING_B     
                                 ,self::F_MOVE_TOP_POS_SIGN_A      
                                 ,self::F_MOVE_TOP_POS_A      
                                 ,self::F_MOVE_TOP_POS_FORMAT_A   
                                 ,self::F_MOVE_LEFT_POS_SIGN_A 
                                 ,self::F_MOVE_LEFT_POS_A      
                                 ,self::F_MOVE_LEFT_POS_FORMAT_A   
                                 ,self::F_MOVE_TOP_POS_SIGN_B     
                                 ,self::F_MOVE_TOP_POS_B      
                                 ,self::F_MOVE_TOP_POS_FORMAT_B  
                                 ,self::F_MOVE_LEFT_POS_SIGN_B 
                                 ,self::F_MOVE_LEFT_POS_B     
                                 ,self::F_MOVE_LEFT_POS_FORMAT_B    
                                 ,self::F_OPTIONS_A           
                                 ,self::F_OPTIONS_B            
                                 ,self::F_EXTRA_JS_A          
                                 ,self::F_EXTRA_JS_B           
                                 );                                 
    

    /* upload import export path constant*/
    const IMPORT_PATH = '/twiz/';       
    const EXPORT_PATH = 'export/';       
  
    /* import max file size constant */ 
    const IMPORT_MAX_SIZE = '2097152';
    
    function __construct(){
    
        global $wpdb;
        
        /* PLUGIN URL */
        $pluginUrl = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
        $pluginUrl = str_replace('/includes/','',$pluginUrl);
        
        /* PLUGIN DIR */
        $pluginDir = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
        $pluginDir = str_replace('/includes/','',$pluginDir);

        /* Twiz variable configuration */
        $this->version    = '1.4.4.1';
        $this->cssVersion = '1-2';
        $this->dbVersion  = '2.61';
        $this->pluginUrl  = $pluginUrl;
        $this->pluginDir  = $pluginDir;
        $this->nonce      =  wp_create_nonce('twiz-nonce');
        $this->table      = $wpdb->prefix .'the_welcomizer';
        $this->DEFAULT_SECTION = get_option('twiz_setting_menu');
        $this->pluginName = __('The Welcomizer', 'the-welcomizer');
        $this->import_path_message = '/wp-content'.self::IMPORT_PATH;
        $this->skin = get_option('twiz_skin');
         
        if(( $this->skin == '' ) or ( $this->skin == self::SKIN_PATH ) ) {
        
            $this->skin = self::SKIN_PATH.self::DEFAULT_SKIN;  
            $code = update_option('twiz_skin', $this->skin);
        }
        
        $this->skinname = str_replace( self::SKIN_PATH, '', $this->skin );
    }

    function twizIt(){
        
        $html = '<div id="twiz_plugin">';
        $html .= '<div id="twiz_background"></div>';
        $html .= '<div id="twiz_master">';
        
        $html .= $this->getHtmlSkinBullets();
        $html .= $this->getHtmlGlobalstatus();
        $html .= $this->getHtmlHeader();
        
        $myTwizMenu = new TwizMenu(); 
        $html .= '<div id="twiz_menu"><div id="twiz_ajax_menu">'.$myTwizMenu->getHtmlMenu().'</div>';
        $html .= '<div id="twiz_option_menu"><div id="twiz_more_menu">&gt;&gt;</div>';
        $html .= '<div id="twiz_add_menu">+</div></div>';
        $html .= '<div id="twiz_loading_menu"></div>';
        $html .= '<div id="twiz_add_sections"></div>';
        $html .= '<div class="twiz-clear"></div></div>';
        
        $html .= $this->getHtmlList();
        $html .= $this->getHtmlFooter();
        $html .= $this->getHtmlFooterMenu();
        
        $html .= '</div>';
        $html .= '<div id="twiz_vertical_menu">'.$myTwizMenu->getHtmlVerticalMenu().'</div>';
        $html .= '<div id="twiz_right_panel"></div>';
        
        $html .= $this->preloadImages();
        $html .= '</div>'; 
        
   
        return $html;
    }
      
    private function getHtmlGlobalstatus(){
    
        return '<div id="twiz_global_status">'.$this->getImgGlobalStatus().'</div>';
    }
    
    private function getHtmlHeader(){
    
        $twiz_setting_menu_1 = ( ( $this->DEFAULT_SECTION == self::DEFAULT_SECTION_HOME ) || ( $this->DEFAULT_SECTION  == '' ) ) ? ' checked="checked"' : '';
    
        $twiz_setting_menu_2 = ( $this->DEFAULT_SECTION == self::DEFAULT_SECTION_EVERYWHERE ) ? ' checked="checked"' : '';
        
        $header = '<div id="twiz_header"><div id="twiz_head_logo"></div><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FThe-Welcomizer%2F173368186051321&amp;send=false&amp;layout=button_count&amp;width=150&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=20&amp;appId=24077487353" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:20px; width:150px;" allowTransparency="true"></iframe><span id="twiz_head_title"><a href="http://www.sebastien-laframboise.com/wordpress/plugins-wordpress/the-welcomizer/" target="_blank">'.$this->pluginName.'</a></span><span id="twiz_head_addnew"><a class="button-secondary" id="twiz_new" name="twiz_new">'.__('Add New', 'the-welcomizer').'</a></span><div id="twiz_head_version"><a href="http://www.wordpress.org/extend/plugins/the-welcomizer/changelog/" target="_blank">v'.$this->version.'</a></div> 

</div><div class="twiz-clear"></div>
    ';
        
        return $header;
    }
    
    private function getHtmlFooterMenu(){
    
      $import = '<div id="twiz_import_container">'.__('Import', 'the-welcomizer').'</div>';
      $export = '<div id="twiz_export">'.__('Export', 'the-welcomizer').'</div>';
      $library_upload = '<div id="twiz_library_upload">'.__('Upload', 'the-welcomizer').'</div>';
      $library = '<div id="twiz_library">'.__('Library', 'the-welcomizer').'</div>';
      $admin = '<div id="twiz_admin">'.__('Admin', 'the-welcomizer').'</div>';
      
      $html = '<div id="twiz_footer_menu">'.$library_upload.$import.$export.$admin.$library.'</div><div id="twiz_export_url"></div>';
      
      return $html;
      
    }
    
    function getHtmlAds(){

    
        $extraspaces = '&nbsp;&nbsp;&nbsp;';
        
        $ads['1and1'] = '<a href="http://www.anrdoezrs.net/dp101vpyvpxCIJLIMFFCEDHFLMDJ" target="_blank" title="1and1.com"><img src="http://www.ftjcfx.com/h7104ltxlrpAGHJGKDDACBFDJKBH" border="0" class="twiz-ads-img"/></a>';
      
        $ads['All-Battery'] = '<a href="http://www.tkqlhce.com/g8104nmvsmu9FGIFJCC9BAGADEJH" target="_blank" title="All-Battery.com"><img src="http://www.tqlkg.com/4c106jy1qwuFLMOLPIIFHGMGJKPN" border="0" class="twiz-ads-img"/></a>';
      
        $ads['Cell Phone Shop'] = '<a href="http://www.dpbolvw.net/97115tenkem178A7B441326B6779" target="_blank" title="Cell Phone Shop"><img src="http://www.awltovhc.com/id102h48x20MSTVSWPPMONRWRSSU" border="0" class="twiz-ads-img"/></a>';
      
        $ads['bluehost'] = '<a href="http://www.bluehost.com/track/affordable_web_hosting/" target="_blank" title="bluehost.com"><img border="0" src="'.$this->pluginUrl.'/images/ads/bh_88x31_04.gif" class="twiz-ads-img"/></a>';
        
        $ads['watches'] = '<a href="http://www.dpbolvw.net/1h66r09608OUVXUYRROQPXSSWRU" target="_blank" title="Free Shipping on ALL orders!"><img src="http://www.lduhtrp.net/b8103kpthnl6CDFCG99687FAAE9C" border="0" class="twiz-ads-img"/></a>';
        
        $ads['Gravity Defyer'] = '<a href="http://www.kqzyfj.com/tn83vpyvpxCIJLIMFFCEDJIMKLD" target="_blank" title="Gravity Defyer"><img src="http://www.awltovhc.com/ec106g04tzxIOPROSLLIKJPOSQRJ" border="0"  class="twiz-ads-img"/></a>';
        
        $ads['myhosting'] = '<a href="http://www.anrdoezrs.net/dc100r09608OUVXUYRROQPWSRUWY" title="myhosting.com" target="_blank"><img src="http://www.lduhtrp.net/rl82g04tzxIOPROSLLIKJQMLOQS" alt="" border="0" class="twiz-ads-img"/></a>';
        
        $ads['mypcbackup'] = '<a href="http://www.jdoqocy.com/68104shqnhp4ABDAE77465DE7B5D" target="_blank" title="mypcbackup.com"><img src="http://www.lduhtrp.net/7r79qmqeki39AC9D66354CD6A4C" alt="" border="0" class="twiz-ads-img"/></a>';
        
        $ads['PetFoodDirect'] = '<a href="http://www.tkqlhce.com/tq119tenkem178A7B44132A76668" title="PetFoodDirect.com" target="_blank"><img src="http://www.awltovhc.com/6i104qmqeki39AC9D66354C9888A" alt="" border="0" class="twiz-ads-img"/></a>';
        
        $ads['Order Flowers Online'] = '<a href="http://www.anrdoezrs.net/fc81lnwtnvAGHJGKDDAKIDIECK" target="_blank" title="Order Flowers Online"><img src="http://www.lduhtrp.net/3b111nswkqo9FGIFJCC9JHCHDBJ" border="0" class="twiz-ads-img"/></a>';
        
        $ads['SuperJeweler'] = '<a href="http://www.anrdoezrs.net/9c102mu2-u1HNOQNRKKHJINJOOJK" target="_blank" title="Shop SuperJeweler - Free Shipping & Free Gift!"><img src="http://www.tqlkg.com/2t70h48x20MSTVSWPPMONSOTTOP" border="0" class="twiz-ads-img"/></a>';
        
        $ads['AccessoryGeeks'] = '<a href="http://www.dpbolvw.net/ka108iqzwqyDJKMJNGGDFEIIKHNE" target="_blank" title="Shop AccessoryGeeks.com!"><img src="http://www.awltovhc.com/47102snrflj4ABDAE7746599B8E5" border="0" class="twiz-ads-img"/></a>';
        
        $ok = shuffle($ads);
        
        $html = '<div id="twiz_ads">'
            . $ads[0] . $extraspaces 
            . $ads[1] . $extraspaces 
            . $ads[2] . $extraspaces 
            . $ads[3]
            .'</div><div id="twiz_more_ads">' 
            . $ads[4] . $extraspaces 
            . $ads[5] . $extraspaces 
            . $ads[6] . $extraspaces 
            . $ads[7] 
            .'<div class="twiz-ads-sep"></div>'
            . $ads[8] . $extraspaces 
            . $ads[9] . $extraspaces 
            . $ads[10] . $extraspaces 
            . $ads[11]
            .'</div><div id="twiz_show_more_ads"><a>'.__('More suggestions', 'the-welcomizer').'</a></div><div id="twiz_show_less_ads"><a>'.__('Fewer suggestions', 'the-welcomizer').'</a></div>';
        
        return $html;
    
    }
    
    private function getHtmlFooter(){

        $html = '
<div class="twiz-clear"></div><div id="twiz_footer"><img src="'.$this->pluginUrl.$this->skin.'/images/twiz-loading.gif" /></div>';
        
        return $html;
    }    
      
    private function add_animation_link( $javascript = '' ){
        
        if( $javascript == ''){return '';}
        
        $searchstring = '';
        $htmllink = '';
        $listarray = $this->getListArray();
      
        foreach( $listarray as $value ){
        
            $searchstring = "$(document).twiz_".$value[self::F_SECTION_ID]."_".str_replace("-","_",$value[self::F_LAYER_ID])."_".$value[self::F_EXPORT_ID]."();";
       
            if(preg_match( "/".preg_quote($searchstring)."/i", $javascript)){
            
                $htmllink = '<a id="twiz_anim_link_'.$value[self::F_ID].'[]" name="twiz_anim_link_'.$value[self::F_ID].'[]" class="twiz-anim-link">'.$searchstring.'</a>';
            
                $javascript = str_replace($searchstring, $htmllink, $javascript);
            }
        }
        
        return $javascript;
    }
    
    private function createHtmlList( $listarray = array(), $id = '' ){ 
    
        if( count($listarray) == 0 ){return false;}
        
        $bullet = ' &#8226;';
        $opendiv = '';
        $closediv = '';
        $rowcolor = '';
        $saveeffect = '';
        $twiz_order_by = get_option('twiz_order_by');
        $twiz_order_by_status = ($twiz_order_by == self::F_STATUS)? $bullet : '' ;
        $twiz_order_by_on_event = ($twiz_order_by == self::F_ON_EVENT)? $bullet : '' ;
        $twiz_order_by_element = ($twiz_order_by == self::F_LAYER_ID)? $bullet : '' ;
        $twiz_order_by_delay = ($twiz_order_by == self::F_START_DELAY)? $bullet : '' ;
        $twiz_order_by_duration = ($twiz_order_by == self::F_DURATION)? $bullet : '' ;
        
        $_POST['twiz_action'] = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'];
        
        /* ajax container */ 
        if( !in_array($_POST['twiz_action'], $this->array_action_excluded) ){
        
            $opendiv = '<div id="twiz_container">';
            $closediv = '</div>';
        }
        
        /* save effect */
        if($id != ''){
            $saveeffect = '$("#twiz_list_div_element_'.$id.'").animate({opacity:0}, 300, 
function(){
$("#twiz_list_div_element_'.$id.'").css("color","green");
$("#twiz_list_div_element_'.$id.'").animate({opacity:1}, 300, function(){
});
}); ';
        }
        
        /* show element */
        $jsscript_show = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {
        $("#twiz_new").fadeIn("fast");
        $(".twiz-status-menu").css("visibility","visible");
        $("#twiz_add_menu").fadeIn("fast");
        $("#twiz_edit_menu").fadeIn("fast");
        $("#twiz_delete_menu").fadeIn("fast");
        $("#twiz_import").fadeIn("fast");
        $("#twiz_export").fadeIn("fast");
'.$saveeffect.'
  });
 //]]>
</script>';

        $htmllist = $opendiv.'<table class="twiz-table-list" cellspacing="0">';
        $htmllist.= '<tr class="twiz-table-list-tr-h"><td class="twiz-td-status twiz-table-list-td-h twiz-td-center"><a id="twiz_order_by_'.self::F_STATUS.'">'.__('Status', 'the-welcomizer').'</a>'.$twiz_order_by_status.'</td><td class="twiz-table-list-td-h twiz-td-left" nowrap="nowrap"><a id="twiz_order_by_'.self::F_LAYER_ID.'">'.__('Element', 'the-welcomizer').'</a>'.$twiz_order_by_element.'</td><td class="twiz-table-list-td-h twiz-td-center twiz-td-event" nowrap="nowrap"><a id="twiz_order_by_'.self::F_ON_EVENT.'">'.__('Event', 'the-welcomizer').'</a>'.$twiz_order_by_on_event.'</td><td class="twiz-table-list-td-h twiz-td-right twiz-td-delay" nowrap="nowrap"><a id="twiz_order_by_'.self::F_START_DELAY.'">'.__('Delay', 'the-welcomizer').'</a>'.$twiz_order_by_delay.'</td><td class="twiz-table-list-td-h twiz-td-right twiz-td-duration" nowrap="nowrap"><a id="twiz_order_by_'.self::F_DURATION.'">'.__('Duration', 'the-welcomizer').'</a>'.$twiz_order_by_duration.'</td><td class="twiz-table-list-td-h  twiz-td-action twiz-td-right" nowrap="nowrap">'.__('Action', 'the-welcomizer').'</td></tr>';
        
        foreach($listarray as $value){
            
            $rowcolor = ($rowcolor == 'twiz-row-color-1') ? 'twiz-row-color-2' : 'twiz-row-color-1';
            
            $statushtmlimg = ($value[self::F_STATUS]=='1') ? $this->getHtmlImgStatus($value[self::F_ID], self::STATUS_ACTIVE) : $this->getHtmlImgStatus($value[self::F_ID], self::STATUS_INACTIVE);
            
            /* add a '2x' to the duration if necessary */
            $duration = $this->formatDuration($value[self::F_ID], $value);

            if( $value[self::F_ON_EVENT] != self::EV_MANUAL) {
                $on_event = ( $value[self::F_ON_EVENT] != '' ) ? 'On'.$value[self::F_ON_EVENT] : '-';
            }else{
                $on_event = self::EV_MANUAL;    
            }
            
            $elementype = ($value[self::F_TYPE] == '') ? self::ELEMENT_TYPE_ID : $value[self::F_TYPE];

            /* the table row */
            $htmllist.= '
    <tr class="twiz_list_tr '.$rowcolor.'" name="twiz_list_tr_'.$value[self::F_ID].'" id="twiz_list_tr_'.$value[self::F_ID].'" ><td class="twiz-td-center" id="twiz_td_status_'.$value[self::F_ID].'">'.$statushtmlimg.'</td><td class="twiz-td-left"><div id="twiz_list_div_element_'.$value[self::F_ID].'" name="twiz_list_div_element_'.$value[self::F_ID].'">'.$value[self::F_LAYER_ID].'<span class="twiz-green"> - ['.$elementype.']</span></div><div class="twiz_list_tr_action" name="twiz_list_tr_action_'.$value[self::F_ID].'" id="twiz_list_tr_action_'.$value[self::F_ID].'" ><a id="twiz_edit_a_'.$value[self::F_ID].'" name="twiz_edit_a_'.$value[self::F_ID].'" class="twiz-edit">'.__('Edit', 'the-welcomizer').'</a> | <a id="twiz_copy_a_'.$value[self::F_ID].'" name="twiz_copy_a_'.$value[self::F_ID].'" class="twiz-copy">'.__('Copy', 'the-welcomizer').'</a> | <a id="twiz_delete_a_'.$value[self::F_ID].'" name="twiz_delete_a_'.$value[self::F_ID].'" class="twiz-red twiz-delete">'.__('Delete', 'the-welcomizer').'</a></div></td><td class="twiz-blue twiz-td-center"><div id="twiz_ajax_td_val_on_event_'.$value[self::F_ID].'">'.$on_event.'</div><div id="twiz_ajax_td_loading_on_event_'.$value[self::F_ID].'" name="twiz_ajax_td_loading_on_event_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_on_event_'.$value[self::F_ID].'" name="twiz_ajax_td_edit_on_event_'.$value[self::F_ID].'" class="twiz_ajax_td_edit"></div></td><td class="twiz-td-delay twiz-td-right"><div id="twiz_ajax_td_val_delay_'.$value[self::F_ID].'">'.$value[self::F_START_DELAY].'</div><div id="twiz_ajax_td_loading_delay_'.$value[self::F_ID].'" name="twiz_ajax_td_loading_delay_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_delay_'.$value[self::F_ID].'" name="twiz_ajax_td_edit_delay_'.$value[self::F_ID].'" class="twiz_ajax_td_edit"><input type="text" name="twiz_input_delay_'.$value[self::F_ID].'" id="twiz_input_delay_'.$value[self::F_ID].'" value="'.$value[self::F_START_DELAY].'" maxlength="100"></div></td><td name="twiz_ajax_td_duration_'.$value[self::F_ID].'" id="twiz_ajax_td_duration_'.$value[self::F_ID].'" class="twiz-td-right twiz-td-duration" nowrap="nowrap"><div id="twiz_ajax_td_val_duration_'.$value[self::F_ID].'">'.$duration.'</div><div id="twiz_ajax_td_loading_duration_'.$value[self::F_ID].'" name="twiz_ajax_td_loading_duration_'.$value[self::F_ID].'"></div><div id="twiz_ajax_td_edit_duration_'.$value[self::F_ID].'" name="twiz_ajax_td_edit_duration_'.$value[self::F_ID].'" class="twiz_ajax_td_edit"><input type="text" name="twiz_input_duration_'.$value[self::F_ID].'" id="twiz_input_duration_'.$value[self::F_ID].'" value="'.$value[self::F_DURATION].'" maxlength="100"></div></td><td class="twiz-td-right" nowrap="nowrap"><img id="twiz_edit_'.$value[self::F_ID].'" name="twiz_edit_'.$value[self::F_ID].'" alt="'.__('Edit', 'the-welcomizer').'" title="'.__('Edit', 'the-welcomizer').'" src="'.$this->pluginUrl.'/images/twiz-edit.gif" height="25" class="twiz-edit" /> <img id="twiz_copy_'.$value[self::F_ID].'" name="twiz_copy_'.$value[self::F_ID].'" alt="'.__('Copy', 'the-welcomizer').'" title="'.__('Copy', 'the-welcomizer').'" src="'.$this->pluginUrl.'/images/twiz-copy.png" height="25" class="twiz-copy" /> <img height="25" src="'.$this->pluginUrl.'/images/twiz-delete.gif" id="twiz_delete_'.$value[self::F_ID].'" name="twiz_delete_'.$value[self::F_ID].'" alt="'.__('Delete', 'the-welcomizer').'" title="'.__('Delete', 'the-welcomizer').'" class="twiz-delete" /></td></tr>';
         
         }
         
         $htmllist.= '</table>'.$closediv.$jsscript_show;
         
         return $htmllist;
    }
    
    function export( $section_id = '', $id = '' ){
  
        $sectionname = '';
        $error = '';
        
        if( $id != '' ) {
        
           $where = " where ".self::F_ID." = '".$id."'";
           $id = "_".$id;
        }else{
        
            $where = ($section_id != '') ? " where ".self::F_SECTION_ID." = '".$section_id."'" : " where ".self::F_SECTION_ID." = '".$this->DEFAULT_SECTION."'";
        }
     
        $listarray = $this->getListArray( $where ); 

        $filedata = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        
        $filedata .= '<TWIZ>'."\n";

        foreach( $listarray as $value ){

              if ( $sectionname == '' ) {
              
                  $myTwizMenu  = new TwizMenu(); 
                  $sectionname = sanitize_title_with_dashes($myTwizMenu->getSectionName($value[self::F_SECTION_ID]));
              }
              
              $filedata .= '<ROW>'."\n";
              
              $count_array = count($this->array_fields);
                    
              /* loop fields array */
              foreach( $this->array_fields as $key ){
              
                  if( $key != self::F_ID ){
             
                     $filedata .= '<'.$this->array_twz_mapping[$key].'>'.$value[$key].'</'.$this->array_twz_mapping[$key].'>'."\n";
                  }
              }
                
              $filedata .= '</ROW>'."\n";
        }

        $filedata .= '</TWIZ>'."\n";
        
        $sectionname = ($sectionname == '') ? $sectionname = $section_id.$id : $sectionname.$id;
        $sectionname =  str_replace(self::DEFAULT_SECTION_ALL_ARTICLES, 'allposts', $sectionname);
       
        $filename = urldecode($sectionname).".".self::EXT_TWIZ;
        $filepath = self::IMPORT_PATH.self::EXPORT_PATH;
        $filepathdir = WP_CONTENT_DIR.$filepath;
        $filefullpathdir = $filepathdir.$filename;
        $filefullpathurl = WP_CONTENT_URL.$filepath.$filename;
 
        if (!is_writable($filepathdir)) {
            @mkdir($filepathdir, 755); 
        }
 
        if (is_writable($filepathdir)) {

            if (!$handle = fopen($filefullpathdir, 'w')) {
                $error =  __("Cannot open file", 'the-welcomizer').' ('.$filename.')';
                exit;
            }

            if (fwrite($handle, $filedata) === FALSE) {
                $error = __("Cannot write to file", 'the-welcomizer').' ('.$filename.')';
                exit;
            }

            fclose($handle);

        } else {
           $error =  __("You must first create this directory", 'the-welcomizer').':<br>'.$this->import_path_message;
        }
        
        $html = ($error!='')? '<div class="twiz-red">' . $error .'</div>' : ' <a href="'.$filefullpathurl.'" title="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'" alt="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'"><img name="twiz_img_download_export" id="twiz_img_download_export" src="'.$this->pluginUrl.'/images/twiz-download.png" /></a><a href="'.$filefullpathurl.'" title="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'" alt="'.__('Right-click, Save Target As/Save Link As', 'the-welcomizer').'">'.__('Download file', 'the-welcomizer').'<br>'. $filename .'</a>' ;
        
        return $html;
    }
   
    private function utf8_strlen( $string = '' ) {
    
        $char = strlen($string); 
        $l = 0;
        
        for ($i = 0; $i < $char; ++$i){
            
            if ( ( ord($string[$i]) & 0xC0 ) != 0x80 ){ 
                ++$l;
            }
        }
        
        return $l;
    }
    
    function delete( $id = '' ){
    
        global $wpdb;
        
        if( $id == '' ){return false;}
         
        $sql = "DELETE from ".$this->table." where ".self::F_ID." = ".$id.";";
        $code = $wpdb->query($sql);
    
        return $code;

    }        
    
    protected function in_multi_array($needle, $haystack){

        $in_multi_array = false;
        
        if(in_array($needle, $haystack)){
        
            $in_multi_array = true;
            
        }else{   
        
            foreach( $haystack as $i => $value ) {
                
                if(is_array($haystack[$i])){
                
                    if($this->in_multi_array($needle, $haystack[$i])) {
                        $in_multi_array = true;
                        break;
                    }
                }
            }
        }
        
        return $in_multi_array;
    } 
    
    function install(){ 
    
        global $wpdb;

        $sql = "CREATE TABLE ".$this->table." (". 
                self::F_ID . " int NOT NULL AUTO_INCREMENT, ". 
                self::F_EXPORT_ID . " varchar(13) NOT NULL default '', ". 
                self::F_SECTION_ID . " varchar(22) NOT NULL default '".self::DEFAULT_SECTION_HOME."', ". 
                self::F_STATUS . " tinyint(3) NOT NULL default 0, ". 
                self::F_TYPE . " varchar(5) NOT NULL default '".self::ELEMENT_TYPE_ID."', ". 
                self::F_LAYER_ID . " varchar(50) NOT NULL default '', ". 
                self::F_ON_EVENT . " varchar(15) NOT NULL default '', ".
                self::F_START_DELAY . " varchar(100) NOT NULL default '0', ". 
                self::F_DURATION . " varchar(100) NOT NULL default '0', ". 
                self::F_OUTPUT . " varchar(1) NOT NULL default 'b', ".
                self::F_OUTPUT_POS . " varchar(1) NOT NULL default 'b', ".
                self::F_JAVASCRIPT . " text NOT NULL default '', ". 
                self::F_START_TOP_POS_SIGN . " varchar(1) NOT NULL default '', ". 
                self::F_START_TOP_POS . " int(5) default NULL, ". 
                self::F_START_TOP_POS_FORMAT . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."', ". 
                self::F_START_LEFT_POS_SIGN . " varchar(1) NOT NULL default '', ". 
                self::F_START_LEFT_POS . " int(5) default NULL, ". 
                self::F_START_LEFT_POS_FORMAT . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."', ". 
                self::F_POSITION . " varchar(8) NOT NULL default '', ". 
                self::F_ZINDEX . " varchar(5) NOT NULL default '', ". 
                self::F_EASING_A . " varchar(20) NOT NULL default 'swing', ". 
                self::F_EASING_B . " varchar(20) NOT NULL default 'swing', ". 
                self::F_MOVE_TOP_POS_SIGN_A . " varchar(1) NOT NULL default '', ". 
                self::F_MOVE_TOP_POS_A . " int(5) default NULL, ". 
                self::F_MOVE_TOP_POS_FORMAT_A . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."', ". 
                self::F_MOVE_LEFT_POS_SIGN_A . " varchar(1) NOT NULL default '', ". 
                self::F_MOVE_LEFT_POS_A . " int(5) default NULL, ". 
                self::F_MOVE_LEFT_POS_FORMAT_A . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."', ". 
                self::F_MOVE_TOP_POS_SIGN_B . " varchar(1) NOT NULL default '', ". 
                self::F_MOVE_TOP_POS_B . " int(5) default NULL, ". 
                self::F_MOVE_TOP_POS_FORMAT_B . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."', ". 
                self::F_MOVE_LEFT_POS_SIGN_B . " varchar(1) NOT NULL default '', ". 
                self::F_MOVE_LEFT_POS_B . " int(5) default NULL, ". 
                self::F_MOVE_LEFT_POS_FORMAT_B . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."', ". 
                self::F_OPTIONS_A . " text NOT NULL default '', ". 
                self::F_OPTIONS_B . " text NOT NULL default '', ". 
                self::F_EXTRA_JS_A . " text NOT NULL default '', ". 
                self::F_EXTRA_JS_B . " text NOT NULL default '', " .  
                "PRIMARY KEY (". self::F_ID . ")
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
                
                
        if ( $wpdb->get_var( "show tables like '".$this->table."'" ) != $this->table ) {

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
            dbDelta($sql);
        
            $code = update_option('twiz_db_version', $this->dbVersion);
            $code = update_option('twiz_global_status', '1');
            $code = update_option('twiz_order_by', self::F_ON_EVENT);
            $code = update_option('twiz_setting_menu', self::DEFAULT_SECTION_HOME); // home / cat / page / post
            $code = new TwizAdmin(); // Default settings
        
        }else{
            
           $dbversion = get_option('twiz_db_version');
           $array_describe = '';       
           
            if( $dbversion != $this->dbVersion ){
                
                
                /* Describe table */
                $describe = "DESCRIBE ".$this->table ."";
                $describe_rows = $wpdb->get_results($describe, ARRAY_A);
                
                foreach($describe_rows as $values){
                
                    $array_describe[] = $values['Field'];
                }
                
                if( !in_array(self::F_SECTION_ID, $array_describe) ){
                
                    /* Add the new field from <= v.1.3.2.3 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_SECTION_ID . " varchar(22) NOT NULL default '".self::DEFAULT_SECTION_HOME."' after ".self::F_ID."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_ON_EVENT, $array_describe) ){    
                
                    /* Add the new field from <= v 1.3.5.7 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ".self::F_ON_EVENT." varchar(15) NOT NULL default '' after ".self::F_LAYER_ID."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_JAVASCRIPT, $array_describe) ){
                
                    /* Add the new field from <= v 1.3.5.8 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_JAVASCRIPT . " text NOT NULL default '' after ".self::F_DURATION."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_ZINDEX, $array_describe) ){
                
                    /* Add the new field from <= v 1.3.5.8 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_ZINDEX . " varchar(5) NOT NULL default '' after ".self::F_POSITION."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_TYPE, $array_describe) ){        
                
                    /* Add the new field from <= v 1.3.6.1 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_TYPE . " varchar(5) NOT NULL default '".self::ELEMENT_TYPE_ID."' after ".self::F_STATUS."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_OUTPUT, $array_describe) ){
                
                    /* Add the new field from <= v 1.3.6.6 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_OUTPUT . " varchar(1) NOT NULL default 'b' after ".self::F_DURATION."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_OUTPUT_POS, $array_describe) ){
                
                    /* Add the new field from <= v 1.3.7 */
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_OUTPUT_POS . " varchar(1) NOT NULL default 'b' after ".self::F_OUTPUT."";
                    $code = $wpdb->query($altersql);
                }
                
                
                /*
                * HOTFIX - Export ID
                *
                * Adds the new field export_id and applies a Hotfix for updating and replacing ids.
                */
                if( !in_array(self::F_EXPORT_ID, $array_describe) ){
                    

                    // Add the new field from <= v 1.3.7.6 
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ".  self::F_EXPORT_ID . " varchar(13) NOT NULL default '' after ". self::F_ID ."";
                    $code = $wpdb->query($altersql);
                    
                    // Select all the table
                    $sql = "SELECT * from ".$this->table;
                    $rows = $wpdb->get_results($sql, ARRAY_A);                    
                    
                    foreach ( $rows as $value ){
                        
                        // wait for 1/10 second
                        usleep(100000);
                        
                        // a simple uniq id
                        $exportid = uniqid();
                        
                        // array temp
                        if( !isset($array_ids[$value[self::F_ID]]) ) $array_ids[$value[self::F_ID]] = '' ;
                        $array_ids[$value[self::F_ID]] = $exportid;
                        
                        // update each row with the unique id and removes section_id.
                        $updatesql = "UPDATE ".$this->table . " SET ".  self::F_EXPORT_ID . " = '".$exportid."'
                        WHERE ". self::F_ID ." = '".$value[self::F_ID]."'";
                        $code = $wpdb->query($updatesql);

                    }
                    
                    // loops the previous temp array
                    foreach ( $array_ids as $t_id => $t_export_id ){
                    
                        // loop all rows again and again
                        foreach ( $rows as $value ){
                        
                            // Replace all current ids. all functions and activations vars included
                            $updatesql = "UPDATE ".$this->table . " SET
                             ". self::F_JAVASCRIPT . " = replace(". self::F_JAVASCRIPT . ", '_".$t_id."', '_".$t_export_id."') 
                            ,". self::F_EXTRA_JS_A . " = replace(". self::F_EXTRA_JS_A . ", '_".$t_id."', '_".$t_export_id."') 
                            ,". self::F_EXTRA_JS_B . " = replace(". self::F_EXTRA_JS_B . ", '_".$t_id."', '_".$t_export_id."') 
                            WHERE ". self::F_ID ." = '".$value[self::F_ID]."'";
                            $code = $wpdb->query($updatesql);
                            
                        }
                    }                    
                }
                
                /* Add the new field from <= v 1.3.7.9  */
                if( !in_array(self::F_START_TOP_POS_FORMAT, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_START_TOP_POS_FORMAT . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."' after ".self::F_START_TOP_POS."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_START_LEFT_POS_FORMAT, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_START_LEFT_POS_FORMAT . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."' after ".self::F_START_LEFT_POS."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_MOVE_TOP_POS_FORMAT_A, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_MOVE_TOP_POS_FORMAT_A . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."' after ".self::F_MOVE_TOP_POS_A."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_MOVE_LEFT_POS_FORMAT_A, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_MOVE_LEFT_POS_FORMAT_A . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."' after ".self::F_MOVE_LEFT_POS_A."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_MOVE_TOP_POS_FORMAT_B, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_MOVE_TOP_POS_FORMAT_B . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."' after ".self::F_MOVE_TOP_POS_B."";
                    $code = $wpdb->query($altersql);
                }
                if( !in_array(self::F_MOVE_LEFT_POS_FORMAT_B, $array_describe) ){
                    $altersql = "ALTER TABLE ".$this->table .
                    " ADD ". self::F_MOVE_LEFT_POS_FORMAT_B . " varchar(2) NOT NULL default '".self::FORMAT_PIXEL."' after ".self::F_MOVE_LEFT_POS_B."";
                    $code = $wpdb->query($altersql);
                }
                
                //  Add the new field from <= v 1.3.8.5
                if( !in_array(self::F_EASING_A, $array_describe) ){

                    $altersql = "ALTER TABLE ".$this->table 
                    . " ADD ". self::F_EASING_B . " varchar(20) NOT NULL default 'swing' after ".self::F_ZINDEX."," 
                    . " ADD ". self::F_EASING_A . " varchar(20) NOT NULL default 'swing' after ".self::F_ZINDEX."";
                    $code = $wpdb->query($altersql);
                }              
                
                
                //  Alter fields from <= v 1.3.9.8
                $altersql = "ALTER TABLE ".$this->table 
                . " MODIFY ". self::F_START_DELAY . " varchar(100) NOT NULL default '0'," 
                . " MODIFY ". self::F_DURATION . " varchar(100) NOT NULL default '0'";
                $code = $wpdb->query($altersql);  
                
                // from <= v 1.4.3
                $twiz_order_by = get_option('twiz_order_by'); 
                
                if( $twiz_order_by == '' ) {
                
                    $code = update_option('twiz_order_by', self::F_ON_EVENT);
                }                
                
                $twiz_setting_menu = get_option('twiz_setting_menu'); // home / cat / page
                
                if( $twiz_setting_menu == '' ) {
                
                    $code = update_option('twiz_setting_menu', self::DEFAULT_SECTION_HOME); // home / cat / page
                }
                 
                
                /* Admin Settings */
                $code = new TwizAdmin(); // Default settings
                
                /* Menu reformating */
                $myTwizMenu  = new TwizMenu();
                        
                /* db version */
                $code = update_option('twiz_db_version', $this->dbVersion);
                
            }
        }
        
        return true;
    }
    
    protected function import( $sectionid = self::DEFAULT_SECTION_HOME ){
    
        $filearray = $this->getImportDirectory(array(self::EXT_TWZ, self::EXT_TWIZ, self::EXT_XML));
        
        foreach( $filearray as $filename ){
            
            if( $code = $this->importData($filename, $sectionid) ){
 
                return true;
            }
        }
        
        return true;
    }
    
    private function importData( $filename = '', $sectionid = self::DEFAULT_SECTION_HOME ){
 
        /* full file path */
        $file = WP_CONTENT_DIR.self::IMPORT_PATH.$filename;

        if ( @file_exists($file) ) {
        
            if( $twz = @simplexml_load_file($file) ){

               /* flip array mapping value to match */
               $reverse_array_twz_mapping = array_flip($this->array_twz_mapping);
               
                /* loop xml entities */              
                foreach( $twz->children() as $twzrow )
                {                  
                    $row = array();
                    $row[self::F_SECTION_ID] = $sectionid; 
                    
                    foreach( $twzrow->children() as $twzfield )
                    {
                        $fieldname = '';
                        $fieldvalue = '';
                        
                        $fieldname = strtr($twzfield->getName(), $reverse_array_twz_mapping);

                        if( $fieldname != "" ) {                        

                            /* get the real name of the field */
                            $fieldvalue = $twzfield;

                            /* build record array */
                            $row[$fieldname] = $fieldvalue;
                           
                        }
                    }

                    /* insert row  */
                    if( !$code = $this->importInsert($row, $sectionid) ){
                        
                        return false;
                    }
                }
                /* imported */     
                return  true;
            }
        }
        
        return false;
    }
    
    private function importInsert( $data = array(), $newsectionid = self::DEFAULT_SECTION_HOME  ){
        
        global $wpdb;
                        
        /* Fields added after */
        if( !isset($data[self::F_ON_EVENT]) ) $data[self::F_ON_EVENT] = '';
        if( !isset($data[self::F_JAVASCRIPT]) ) $data[self::F_JAVASCRIPT] = '';
        if( !isset($data[self::F_ZINDEX]) ) $data[self::F_ZINDEX] = '';
        if( !isset($data[self::F_TYPE]) ) $data[self::F_TYPE] = '';
        if( !isset($data[self::F_OUTPUT]) ) $data[self::F_OUTPUT] = '';
        if( !isset($data[self::F_OUTPUT_POS]) ) $data[self::F_OUTPUT_POS] = '';
        if( !isset($data[self::F_EXPORT_ID]) ) $data[self::F_EXPORT_ID] = '';
        if( !isset($data[self::F_SECTION_ID]) ) $data[self::F_SECTION_ID] = '';
        if( !isset($data[self::F_START_TOP_POS_FORMAT]) ) $data[self::F_START_TOP_POS_FORMAT] = '';
        if( !isset($data[self::F_START_LEFT_POS_FORMAT]) ) $data[self::F_START_LEFT_POS_FORMAT] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_FORMAT_A]) ) $data[self::F_MOVE_TOP_POS_FORMAT_A] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_FORMAT_A]) ) $data[self::F_MOVE_LEFT_POS_FORMAT_A] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_FORMAT_B]) ) $data[self::F_MOVE_TOP_POS_FORMAT_B] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_FORMAT_B]) ) $data[self::F_MOVE_LEFT_POS_FORMAT_B] = '';
        if( !isset($data[self::F_EASING_A]) ) $data[self::F_EASING_A] = '';
        if( !isset($data[self::F_EASING_B]) ) $data[self::F_EASING_B] = '';
        
        $twiz_move_top_pos_a  = esc_attr(trim($data[self::F_MOVE_TOP_POS_A]));
        $twiz_move_left_pos_a = esc_attr(trim($data[self::F_MOVE_LEFT_POS_A]));
        $twiz_move_top_pos_b  = esc_attr(trim($data[self::F_MOVE_TOP_POS_B]));
        $twiz_move_left_pos_b = esc_attr(trim($data[self::F_MOVE_LEFT_POS_B]));
        $twiz_start_top_pos   = esc_attr(trim($data[self::F_START_TOP_POS]));
        $twiz_start_left_pos  = esc_attr(trim($data[self::F_START_LEFT_POS]));
        
        $twiz_move_top_pos_a  = ($twiz_move_top_pos_a=='') ? 'NULL' : $twiz_move_top_pos_a;
        $twiz_move_left_pos_a = ($twiz_move_left_pos_a=='') ? 'NULL' : $twiz_move_left_pos_a;
        $twiz_move_top_pos_b  = ($twiz_move_top_pos_b=='') ? 'NULL' : $twiz_move_top_pos_b;
        $twiz_move_left_pos_b = ($twiz_move_left_pos_b=='') ? 'NULL' : $twiz_move_left_pos_b;
        $twiz_start_top_pos   = ($twiz_start_top_pos=='') ? 'NULL' : $twiz_start_top_pos;
        $twiz_start_left_pos  = ($twiz_start_left_pos=='') ? 'NULL' : $twiz_start_left_pos;
      
        $twiz_javascript = str_replace("\\", "\\\\" , $data[self::F_JAVASCRIPT]);
        $twiz_extra_js_a = str_replace("\\", "\\\\" , $data[self::F_EXTRA_JS_A]);
        $twiz_extra_js_b = str_replace("\\", "\\\\" , $data[self::F_EXTRA_JS_B]);
        
        $twiz_start_delay = $data[self::F_START_DELAY];
        $twiz_duration = $data[self::F_DURATION];
        
        $twiz_start_delay = ( $twiz_start_delay == '' ) ? '0' : $twiz_start_delay;
        $twiz_duration = ( $twiz_duration == '' ) ? '0' : $twiz_duration;
        
        // replace section id
        $twiz_javascript = str_replace("$(document).twiz_".$data[self::F_SECTION_ID]."_", "$(document).twiz_".$newsectionid."_", $data[self::F_JAVASCRIPT]);
        $twiz_extra_js_a = str_replace("$(document).twiz_".$data[self::F_SECTION_ID]."_", "$(document).twiz_".$newsectionid."_", $data[self::F_EXTRA_JS_A]);
        $twiz_extra_js_b = str_replace("$(document).twiz_".$data[self::F_SECTION_ID]."_", "$(document).twiz_".$newsectionid."_", $data[self::F_EXTRA_JS_B]);

        // wait for 1/10 second
        usleep(100000);
        
        // a simple uniq id
        $exportid = uniqid();
        
        $data[self::F_EXPORT_ID] = ($data[self::F_EXPORT_ID]=='')? $exportid : $data[self::F_EXPORT_ID];
        
        // default output pos for older export files. b r default, because no backward v to check
        $data[self::F_OUTPUT] = ($data[self::F_OUTPUT] == '')? 'b' : $data[self::F_OUTPUT];
        $data[self::F_OUTPUT_POS] = ($data[self::F_OUTPUT_POS] == '') ? 'r' : $data[self::F_OUTPUT_POS];
        $data[self::F_START_TOP_POS_FORMAT] = ($data[self::F_START_TOP_POS_FORMAT] == '') ? self::FORMAT_PIXEL : $data[self::F_START_TOP_POS_FORMAT];
        $data[self::F_START_LEFT_POS_FORMAT] = ($data[self::F_START_LEFT_POS_FORMAT] == '') ? self::FORMAT_PIXEL : $data[self::F_START_LEFT_POS_FORMAT];
        $data[self::F_MOVE_TOP_POS_FORMAT_A] = ($data[self::F_MOVE_TOP_POS_FORMAT_A] == '') ? self::FORMAT_PIXEL : $data[self::F_MOVE_TOP_POS_FORMAT_A];
        $data[self::F_MOVE_LEFT_POS_FORMAT_A] = ($data[self::F_MOVE_LEFT_POS_FORMAT_A] == '') ? self::FORMAT_PIXEL : $data[self::F_MOVE_LEFT_POS_FORMAT_A];
        $data[self::F_MOVE_TOP_POS_FORMAT_B] = ($data[self::F_MOVE_TOP_POS_FORMAT_B] == '') ? self::FORMAT_PIXEL : $data[self::F_MOVE_TOP_POS_FORMAT_B];
        $data[self::F_MOVE_LEFT_POS_FORMAT_B] = ($data[self::F_MOVE_LEFT_POS_FORMAT_B] == '') ? self::FORMAT_PIXEL : $data[self::F_MOVE_LEFT_POS_FORMAT_B];
        
        $data[self::F_EASING_A] = ($data[self::F_EASING_A] == '')? 'swing' : $data[self::F_EASING_A];
        $data[self::F_EASING_B] = ($data[self::F_EASING_B] == '')? 'swing' : $data[self::F_EASING_B];
        
        // Check if the exportid already exists and replace it.
        $exportidExists = $this->ExportidExists($data[self::F_EXPORT_ID]);
        
        // If imported in the same section, new exportid is given
        if( ($exportidExists) and ($data[self::F_SECTION_ID] == $newsectionid) ){
       
            // new exportid
            $data[self::F_EXPORT_ID] = $exportid;
        }
        
                
        $sql = "INSERT INTO ".$this->table." 
             (".self::F_EXPORT_ID."
             ,".self::F_SECTION_ID."
             ,".self::F_STATUS."
             ,".self::F_TYPE."
             ,".self::F_LAYER_ID."
             ,".self::F_ON_EVENT."
             ,".self::F_START_DELAY."
             ,".self::F_DURATION."
             ,".self::F_OUTPUT."
             ,".self::F_OUTPUT_POS."
             ,".self::F_JAVASCRIPT."
             ,".self::F_START_TOP_POS_SIGN."
             ,".self::F_START_TOP_POS."
             ,".self::F_START_TOP_POS_FORMAT."
             ,".self::F_START_LEFT_POS_SIGN."
             ,".self::F_START_LEFT_POS."    
             ,".self::F_START_LEFT_POS_FORMAT."    
             ,".self::F_POSITION."    
             ,".self::F_ZINDEX."    
             ,".self::F_EASING_A."  
             ,".self::F_EASING_B."               
             ,".self::F_MOVE_TOP_POS_SIGN_A."
             ,".self::F_MOVE_TOP_POS_A."
             ,".self::F_MOVE_TOP_POS_FORMAT_A."
             ,".self::F_MOVE_LEFT_POS_SIGN_A."
             ,".self::F_MOVE_LEFT_POS_A."
             ,".self::F_MOVE_LEFT_POS_FORMAT_A."
             ,".self::F_MOVE_TOP_POS_SIGN_B."
             ,".self::F_MOVE_TOP_POS_B."
             ,".self::F_MOVE_TOP_POS_FORMAT_B."
             ,".self::F_MOVE_LEFT_POS_SIGN_B."
             ,".self::F_MOVE_LEFT_POS_B."
             ,".self::F_MOVE_LEFT_POS_FORMAT_B."
             ,".self::F_OPTIONS_A."
             ,".self::F_OPTIONS_B."
             ,".self::F_EXTRA_JS_A."
             ,".self::F_EXTRA_JS_B."       
             )values('".esc_attr(trim($data[self::F_EXPORT_ID]))."'
             ,'".$newsectionid."'
             ,'".esc_attr(trim($data[self::F_STATUS]))."'
             ,'".esc_attr(trim($data[self::F_TYPE]))."'
             ,'".esc_attr(trim($data[self::F_LAYER_ID]))."'
             ,'".esc_attr(trim($data[self::F_ON_EVENT]))."'             
             ,'".$twiz_start_delay."'
             ,'".$twiz_duration."'
             ,'".esc_attr(trim($data[self::F_OUTPUT]))."'
             ,'".esc_attr(trim($data[self::F_OUTPUT_POS]))."'
             ,'".esc_attr($twiz_javascript)."'    
             ,'".esc_attr(trim($data[self::F_START_TOP_POS_SIGN]))."'    
             ,".$twiz_start_top_pos."
             ,'".esc_attr(trim($data[self::F_START_TOP_POS_FORMAT]))."'   
             ,'".esc_attr(trim($data[self::F_START_LEFT_POS_SIGN]))."'    
             ,".$twiz_start_left_pos."
             ,'".esc_attr(trim($data[self::F_START_LEFT_POS_FORMAT]))."'    
             ,'".esc_attr(trim($data[self::F_POSITION]))."'
             ,'".esc_attr(trim($data[self::F_ZINDEX]))."'                      
             ,'".esc_attr(trim($data[self::F_EASING_A]))."'  
             ,'".esc_attr(trim($data[self::F_EASING_B]))."'               
             ,'".esc_attr(trim($data[self::F_MOVE_TOP_POS_SIGN_A]))."'    
             ,".$twiz_move_top_pos_a."
             ,'".esc_attr(trim($data[self::F_MOVE_TOP_POS_FORMAT_A]))."'    
             ,'".esc_attr(trim($data[self::F_MOVE_LEFT_POS_SIGN_A]))."'    
             ,".$twiz_move_left_pos_a."
             ,'".esc_attr(trim($data[self::F_MOVE_LEFT_POS_FORMAT_A]))."'    
             ,'".esc_attr(trim($data[self::F_MOVE_TOP_POS_SIGN_B]))."'                     
             ,".$twiz_move_top_pos_b."
             ,'".esc_attr(trim($data[self::F_MOVE_TOP_POS_FORMAT_B]))."'                     
             ,'".esc_attr(trim($data[self::F_MOVE_LEFT_POS_SIGN_B]))."'    
             ,".$twiz_move_left_pos_b."
             ,'".esc_attr(trim($data[self::F_MOVE_LEFT_POS_FORMAT_B]))."'    
             ,'".esc_attr(trim($data[self::F_OPTIONS_A]))."'                             
             ,'".esc_attr(trim($data[self::F_OPTIONS_B]))."'
             ,'".esc_attr($twiz_extra_js_a)."'                             
             ,'".esc_attr($twiz_extra_js_b)."'                 
             );";
            
            $code = $wpdb->query($sql);
            
            if($code){return true;}
            
            return $code;
    }
    
    function formatDuration( $id = '', $data = null ){
        
        $data = '';
        
        if($id==''){return false;}
       
        $data = ($data==null) ? $this->getRow($id) : $data;
        
        $duration = (($data[self::F_MOVE_TOP_POS_B] !='' ) or( $data[self::F_MOVE_LEFT_POS_B] !='' ) or( $data[self::F_OPTIONS_B] !='' ) or( $data[self::F_EXTRA_JS_B] !='' )) ? $data[self::F_DURATION].'<span class="twiz-green"> x2</span>' : $data[self::F_DURATION];
        
        return $duration;
    }
    
    function getSkinsDirectory(){
        
        $dirarray = '';
        
        if ( $handle = @opendir($this->pluginDir .self::SKIN_PATH ) ) {
        
            while ( false !== ( $file = readdir($handle) ) ) {
            
                if ( $file != "." && $file != ".." ) {
                
                    $dirarray[] = $file;
                
                }
            }
            
            closedir($handle);
        }
        
        if( !is_array($dirarray) ){ $dirarray = array(); }
         
        return $dirarray;
    }
    
    protected function getImportDirectory( $extensions = array(self::EXT_TWZ, self::EXT_TWIZ, self::EXT_XML) ){
        
        $filearray = '';
        if ( $handle = @opendir(WP_CONTENT_DIR.self::IMPORT_PATH ) ) {
        
            while ( false !== ( $file = readdir($handle) ) ) {
            
               $pathinfo = pathinfo(WP_CONTENT_DIR.self::IMPORT_PATH.$file);
               if( !isset($pathinfo['extension']) ) $pathinfo['extension'] = '' ;
               $ext = $pathinfo['extension'];
        
                if ( ( $file != "." && $file != ".." ) && (in_array(strtolower($ext), $extensions) ) ) {
                
                    $filearray[] = $file;
                }
            }
            
            closedir($handle);
        }
        
        if( !is_array($filearray) ){ $filearray = array(); }
         
        return $filearray;
    }
       
    function hasStartingConfigs( $value = array() ){

        if(($value[self::F_START_TOP_POS]!='') or ($value[self::F_START_LEFT_POS]!='')
         or($value[self::F_ZINDEX]!='') or ($value[self::F_JAVASCRIPT]!='')
         or ($value[self::F_POSITION]!='')){
         
             return true;
        }
        
        return false;
    
    }
    
    function hasMovements( $value = array() ){

        if(($value[self::F_OPTIONS_A]!='') or ($value[self::F_EXTRA_JS_A]!='')
         or($value[self::F_OPTIONS_B]!='') or ($value[self::F_EXTRA_JS_B]!='')
         or($value[self::F_MOVE_TOP_POS_A]!='') or($value[self::F_MOVE_LEFT_POS_A]!='')
         or($value[self::F_MOVE_TOP_POS_B]!='') or ($value[self::F_MOVE_LEFT_POS_B]!='')){
         
             return true;
        }
        
        return false;
    
    }
    
    function hasSomething( $value = array() ){

        if(($value[self::F_OPTIONS_A]!='') or ($value[self::F_EXTRA_JS_A]!='')
         or($value[self::F_OPTIONS_B]!='') or ($value[self::F_EXTRA_JS_B]!='')
         or($value[self::F_MOVE_TOP_POS_A]!='') or($value[self::F_MOVE_LEFT_POS_A]!='')
         or($value[self::F_MOVE_TOP_POS_B]!='') or ($value[self::F_MOVE_LEFT_POS_B]!='')
         or (($value[self::F_JAVASCRIPT]!='') and ($value[self::F_OUTPUT] == 'a' ))){
         
             return true;
        }
        
        return false;
    
    }
    
    private function getDirectionalImage( $data = '', $ab = ''){
    
        if($data==''){return '';}
        if($ab==''){return '';}
        $direction = '';
        
        if((($data['move_top_pos_sign_'.$ab] != '') and ($data['move_left_pos_'.$ab] == ''))
        or (($data['move_left_pos_sign_'.$ab] != '') and ($data['move_top_pos_'.$ab] == ''))
        or (($data['move_left_pos_sign_'.$ab] != '') and ($data['move_top_pos_'.$ab] != '')
        and ($data['move_top_pos_sign_'.$ab] != '') and ($data['move_left_pos_'.$ab] != ''))
        ){
        
            /* true super fast logical switch */
            switch(true){
            
                case (($data['move_top_pos_'.$ab]!= '') 
                     and ($data['move_top_pos_sign_'.$ab] == '-') 
                     and ($data['move_left_pos_'.$ab]== '') ): // N
                     
                     $direction = self::DIMAGE_N;
                     
                    break;
                    
                case (($data['move_top_pos_'.$ab]!= '') 
                     and ($data['move_top_pos_sign_'.$ab] == '-') 
                     and ($data['move_left_pos_'.$ab]!= '') 
                     and ($data['move_left_pos_sign_'.$ab] == '+' ) ): // NE
                     
                    $direction = self::DIMAGE_NE;
                
                    break;       
                    
                case (($data['move_top_pos_'.$ab]== '') 
                     and ($data['move_left_pos_'.$ab]!= '') 
                     and ($data['move_left_pos_sign_'.$ab] == '+' ) ): // E
                     
                    $direction = self::DIMAGE_E;
                
                    break;    
                    
                case (($data['move_top_pos_'.$ab]!= '') 
                     and ($data['move_top_pos_sign_'.$ab] == '+') 
                     and ($data['move_left_pos_'.$ab]!= '') 
                     and ($data['move_left_pos_sign_'.$ab] == '+' ) ): // SE
                     
                    $direction = self::DIMAGE_SE;
                
                    break;  
                    
               case (($data['move_top_pos_'.$ab]!= '') 
                     and ($data['move_top_pos_sign_'.$ab] == '+') 
                     and ($data['move_left_pos_'.$ab]== '') ): // S
                     
                    $direction = self::DIMAGE_S;                 
                
                    break;  
                    
               case (($data['move_top_pos_'.$ab]!= '') 
                     and ($data['move_top_pos_sign_'.$ab] == '+') 
                     and ($data['move_left_pos_'.$ab]!= '') 
                     and ($data['move_left_pos_sign_'.$ab] == '-' ) ): // SW
                     
                     $direction = self::DIMAGE_SW;
                
                    break; 
                    
               case (($data['move_top_pos_'.$ab]== '') 
                     and ($data['move_left_pos_'.$ab]!= '') 
                     and ($data['move_left_pos_sign_'.$ab] == '-' ) ): // W
                     
                     $direction = self::DIMAGE_W;
                
                    break;      
                    
               case (($data['move_top_pos_'.$ab]!= '') 
                     and ($data['move_top_pos_sign_'.$ab] == '-') 
                     and ($data['move_left_pos_'.$ab]!= '') 
                     and ($data['move_left_pos_sign_'.$ab] == '-' ) ): // NW
                     
                    $direction = self::DIMAGE_NW;
                
                    break;     
            }
        }
        
        if($direction!=''){ 

            return '<div class="twiz-arrow twiz-arrow-'.$direction.'"></div>';
            
        }else{
        
            return '';
            
        }
    }

    function getHtmlForm( $id = '', $action = self::ACTION_NEW, $section_id = ''){ 
    
        $data = '';        
        $opendiv = '';
        $closediv = '';
        $hideimport = '';
        $toggleoptions = '';
        $_POST['twiz_action'] = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'] ;  
        $array_admin = get_option('twiz_admin');
                
        if($id!=''){
            
            if(!$data = $this->getRow($id)){return false;}
            
            if( $action == self::ACTION_COPY ){
            
                $hideimport = '$("#twiz_export").fadeOut("fast");';
            }
        }else{
        
            $hideimport = '$("#twiz_export").fadeOut("fast");';
        }
        
        $jsscript_open = '<script>
 //<![CDATA[
 jQuery(document).ready(function($) {';
 
        $jsscript_close = '});
 //]]>
</script>';
        
        /* Toggle starting config */
        $jsscript_starting_config = '$("#twiz_tr_starting_config").toggle();';
        
        /* Toggle More Options */
        $jsscript_moreoptions = '$(".twiz-table-more-options").toggle();';
        
        /* hide element */
        $jsscript_hide = '$("#twiz_layer_id").focus();
$("#twiz_new").fadeOut("fast");
$("#twiz_right_panel").fadeOut("fast");
$("#twiz_add_menu").fadeIn("fast");
$("#twiz_edit_menu").fadeIn("fast");
$("#twiz_import").fadeIn("fast");
$("#twiz_delete_menu").fadeIn("fast");
$("#qq_upload_list li").remove(); 
$("#twiz_export_url").html(""); 
        '.$hideimport;

        /* Text Area auto expand */
        $jsscript_autoexpand = '
textarea = new Object();
textarea.expand = function(textbox){
    twizsizeOrig(textbox);
    textbox.style.height = (textbox.scrollHeight + 20) + "px";
    textbox.style.width = (textbox.scrollWidth + 8) + "px";
} 
function twizsizeOrig(textbox){
    $(textbox).css({"height":"50px", "width" : "230px"});
}
$("textarea[name^=twiz_javascript]").blur(function (){
   twizsizeOrig(this);
});
 $("textarea[name^=twiz_extra]").blur(function (){
   twizsizeOrig(this);
});';

        /* ajax container */ 
        if(!in_array($_POST['twiz_action'], $this->array_action_excluded)){
             $opendiv = '<div id="twiz_container">';
             $closediv = '</div>';
        }
        
        if( !isset($data[self::F_OPTIONS_A]) ) $data[self::F_OPTIONS_A] = '';
        if( !isset($data[self::F_OPTIONS_B]) ) $data[self::F_OPTIONS_B] = '';
        if( !isset($data[self::F_EXTRA_JS_A]) ) $data[self::F_EXTRA_JS_A] = '';
        if( !isset($data[self::F_EXTRA_JS_B]) ) $data[self::F_EXTRA_JS_B] = '';
        if( !isset($data[self::F_STATUS]) ) $data[self::F_STATUS] = '';
        if( !isset($data[self::F_POSITION]) ) $data[self::F_POSITION] = '';
        if( !isset($data[self::F_ZINDEX]) ) $data[self::F_ZINDEX] = '';
        if( !isset($data[self::F_TYPE]) ) $data[self::F_TYPE] = '';
        if( !isset($data[self::F_EASING_A]) ) $data[self::F_EASING_A] = '';
        if( !isset($data[self::F_EASING_B]) ) $data[self::F_EASING_B] = '';
        if( !isset($data[self::F_START_TOP_POS_SIGN]) ) $data[self::F_START_TOP_POS_SIGN] = '';
        if( !isset($data[self::F_START_LEFT_POS_SIGN]) ) $data[self::F_START_LEFT_POS_SIGN] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_SIGN_A]) ) $data[self::F_MOVE_TOP_POS_SIGN_A] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_SIGN_B]) ) $data[self::F_MOVE_TOP_POS_SIGN_B] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_SIGN_A]) ) $data[self::F_MOVE_LEFT_POS_SIGN_A] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_SIGN_B]) ) $data[self::F_MOVE_LEFT_POS_SIGN_B] = '';
        if( !isset($data[self::F_LAYER_ID]) ) $data[self::F_LAYER_ID] = '';
        if( !isset($data[self::F_START_DELAY]) ) $data[self::F_START_DELAY] = '';
        if( !isset($data[self::F_ON_EVENT]) ) $data[self::F_ON_EVENT] = '';
        if( !isset($data[self::F_DURATION]) ) $data[self::F_DURATION] = '';
        if( !isset($data[self::F_OUTPUT]) ) $data[self::F_OUTPUT] = '';
        if( !isset($data[self::F_OUTPUT_POS]) ) $data[self::F_OUTPUT_POS] = '';
        if( !isset($data[self::F_JAVASCRIPT]) ) $data[self::F_JAVASCRIPT] = '';
        if( !isset($data[self::F_START_TOP_POS]) ) $data[self::F_START_TOP_POS] = '';
        if( !isset($data[self::F_START_LEFT_POS]) ) $data[self::F_START_LEFT_POS] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_A]) ) $data[self::F_MOVE_TOP_POS_A] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_B]) ) $data[self::F_MOVE_TOP_POS_B] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_A]) ) $data[self::F_MOVE_LEFT_POS_A] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_B]) ) $data[self::F_MOVE_LEFT_POS_B] = '';
        if( !isset($data[self::F_START_TOP_POS_FORMAT]) ) $data[self::F_START_TOP_POS_FORMAT] = '';
        if( !isset($data[self::F_START_LEFT_POS_FORMAT]) ) $data[self::F_START_LEFT_POS_FORMAT] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_FORMAT_A]) ) $data[self::F_MOVE_TOP_POS_FORMAT_A] = '';
        if( !isset($data[self::F_MOVE_TOP_POS_FORMAT_B]) ) $data[self::F_MOVE_TOP_POS_FORMAT_B] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_FORMAT_A]) ) $data[self::F_MOVE_LEFT_POS_FORMAT_A] = '';
        if( !isset($data[self::F_MOVE_LEFT_POS_FORMAT_B]) ) $data[self::F_MOVE_LEFT_POS_FORMAT_B] = '';
    
        if( !isset($twiz_position[self::POS_ABSOLUTE] ) ) $twiz_position[self::POS_ABSOLUTE] = '';
        if( !isset($twiz_position[self::POS_RELATIVE]) ) $twiz_position[self::POS_RELATIVE] = '';
        if( !isset($twiz_position[self::POS_FIXED]) ) $twiz_position[self::POS_FIXED] = '';
        if( !isset($twiz_position[self::POS_STATIC]) ) $twiz_position[self::POS_STATIC] = '';
        
        if( !isset($twiz_start_top_pos_sign['nothing'] ) ) $twiz_start_top_pos_sign['nothing'] = '';
        if( !isset($twiz_start_top_pos_sign['-']) ) $twiz_start_top_pos_sign['-'] = '';
        if( !isset($twiz_start_left_pos_sign['nothing'] ) ) $twiz_start_left_pos_sign['nothing'] = '';
        if( !isset($twiz_start_left_pos_sign['-']) ) $twiz_start_left_pos_sign['-'] = '';
        
        if( !isset($twiz_move_top_pos_sign_a['+'] ) ) $twiz_move_top_pos_sign_a['+']  = '';
        if( !isset($twiz_move_top_pos_sign_a['-'] ) ) $twiz_move_top_pos_sign_a['-']  = '';
        if( !isset($twiz_move_top_pos_sign_a['='] ) ) $twiz_move_top_pos_sign_a['=']  = '';
        if( !isset($twiz_move_top_pos_sign_b['+']) ) $twiz_move_top_pos_sign_b['+'] = '';
        if( !isset($twiz_move_top_pos_sign_b['-']) ) $twiz_move_top_pos_sign_b['-'] = '';
        if( !isset($twiz_move_top_pos_sign_b['=']) ) $twiz_move_top_pos_sign_b['='] = '';
            
        if( !isset($twiz_move_left_pos_sign_a['+'] ) ) $twiz_move_left_pos_sign_a['+']  = '';
        if( !isset($twiz_move_left_pos_sign_a['-'] ) ) $twiz_move_left_pos_sign_a['-']  = '';
        if( !isset($twiz_move_left_pos_sign_a['='] ) ) $twiz_move_left_pos_sign_a['=']  = '';
        if( !isset($twiz_move_left_pos_sign_b['+']) ) $twiz_move_left_pos_sign_b['+'] = '';
        if( !isset($twiz_move_left_pos_sign_b['-']) ) $twiz_move_left_pos_sign_b['-'] = '';
        if( !isset($twiz_move_left_pos_sign_b['=']) ) $twiz_move_left_pos_sign_b['='] = '';
        
        if( !isset($twiz_easing_a['swing']) ) $twiz_easing_a['swing'] = '';
        if( !isset($twiz_easing_a['linear']) ) $twiz_easing_a['linear'] = '';
        if( !isset($twiz_easing_b['swing']) ) $twiz_easing_b['swing'] = '';
        if( !isset($twiz_easing_b['linear']) ) $twiz_easing_b['linear'] = '';
        

        $hasStartingConfigs = $this->hasStartingConfigs($data);

        /* toggle starting config if we have values */        
        if($hasStartingConfigs){
        
            $toggleoptions = $jsscript_starting_config;
        }
        
        /* toggle more options by default if we have values */        
        if(($data[self::F_OPTIONS_A]!='')or($data[self::F_EXTRA_JS_A]!='')
         or($data[self::F_OPTIONS_B]!='')or($data[self::F_EXTRA_JS_B]!='')){
         
            $toggleoptions .= $jsscript_moreoptions;
        }
    
        /* checked */
        $twiz_status = (( $data[self::F_STATUS] == 1 ) or ( $id == '' )) ? ' checked="checked"' : '';
 
        /* selected */
        $twiz_position[self::POS_NO_POS] = (( $data[self::F_POSITION] == self::POS_NO_POS ) or ($array_admin[self::KEY_STARTING_POSITION] == self::POS_NO_POS  )) ? ' selected="selected"' : '';
        $twiz_position[self::POS_ABSOLUTE] = (( $data[self::F_POSITION] == self::POS_ABSOLUTE ) or ($array_admin[self::KEY_STARTING_POSITION] == self::POS_ABSOLUTE  )) ? ' selected="selected"' : '';
        $twiz_position[self::POS_RELATIVE] = (( $data[self::F_POSITION] == self::POS_RELATIVE) or ($array_admin[self::KEY_STARTING_POSITION] == self::POS_RELATIVE )) ? ' selected="selected"' : '';
        $twiz_position[self::POS_FIXED]   = (( $data[self::F_POSITION] == self::POS_FIXED) or ($array_admin[self::KEY_STARTING_POSITION] == self::POS_FIXED )) ? ' selected="selected"' : '';
        $twiz_position[self::POS_STATIC]   = (( $data[self::F_POSITION] == self::POS_STATIC) or ($array_admin[self::KEY_STARTING_POSITION] == self::POS_STATIC )) ? ' selected="selected"' : '';
      
      
        $twiz_ouput_pos['ready'] = ($data[self::F_OUTPUT_POS] == 'r') ? ' selected="selected"' : '';
        $twiz_ouput_pos['before'] = ($data[self::F_OUTPUT_POS] == 'b') ? ' selected="selected"' : '';
        $twiz_ouput_pos['after'] = ($data[self::F_OUTPUT_POS] == 'a') ? ' selected="selected"' : '';
        $twiz_ouput_pos['ready'] = ($data[self::F_OUTPUT_POS] == '') ? ' selected="selected"' : $twiz_ouput_pos['ready'];
        
        $twiz_ouput['ready'] = ($data[self::F_OUTPUT] == 'r') ? ' selected="selected"' : '';
        $twiz_ouput['before'] = ($data[self::F_OUTPUT] == 'b') ? ' selected="selected"' : '';
        $twiz_ouput['after'] = ($data[self::F_OUTPUT] == 'a') ? ' selected="selected"' : '';
        $twiz_ouput['before'] = ($data[self::F_OUTPUT] == '') ? ' selected="selected"' : $twiz_ouput['before'];
        
        $twiz_start_top_pos_sign['nothing']  = ($data[self::F_START_TOP_POS_SIGN] == '') ? ' selected="selected"' : '';
        $twiz_start_top_pos_sign['-']        = ($data[self::F_START_TOP_POS_SIGN] == '-') ? ' selected="selected"' : '';
        $twiz_start_left_pos_sign['nothing'] = ($data[self::F_START_LEFT_POS_SIGN] == '') ? ' selected="selected"' : '';
        $twiz_start_left_pos_sign['-']       = ($data[self::F_START_LEFT_POS_SIGN] == '-') ? ' selected="selected"' : '';
        
        $twiz_move_top_pos_sign_a['+']  = ($data[self::F_MOVE_TOP_POS_SIGN_A] == '+') ? ' selected="selected"' : '';
        $twiz_move_top_pos_sign_a['-']  = ($data[self::F_MOVE_TOP_POS_SIGN_A] == '-') ? ' selected="selected"' : '';
        $twiz_move_top_pos_sign_a[' ']  = ($data[self::F_MOVE_TOP_POS_SIGN_A] == '') ? ' selected="selected"' : '';
        
        $twiz_move_left_pos_sign_a['+'] = ($data[self::F_MOVE_LEFT_POS_SIGN_A] == '+') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_a['-'] = ($data[self::F_MOVE_LEFT_POS_SIGN_A] == '-') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_a[' '] = ($data[self::F_MOVE_LEFT_POS_SIGN_A] == '') ? ' selected="selected"' : '';

        $twiz_move_top_pos_sign_b['+']  = ($data[self::F_MOVE_TOP_POS_SIGN_B] == '+') ? ' selected="selected"' : '';
        $twiz_move_top_pos_sign_b['-']  = ($data[self::F_MOVE_TOP_POS_SIGN_B] == '-') ? ' selected="selected"' : '';
        $twiz_move_top_pos_sign_b[' ']  = ($data[self::F_MOVE_TOP_POS_SIGN_B] == '') ? ' selected="selected"' : '';
        
        $twiz_move_left_pos_sign_b['+'] = ($data[self::F_MOVE_LEFT_POS_SIGN_B] == '+') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_b['-'] = ($data[self::F_MOVE_LEFT_POS_SIGN_B] == '-') ? ' selected="selected"' : '';
        $twiz_move_left_pos_sign_b[' '] = ($data[self::F_MOVE_LEFT_POS_SIGN_B] == '') ? ' selected="selected"' : '';
        
        $twiz_easing_a['swing'] = ($data[self::F_EASING_A] == 'swing') ? ' selected="selected"' : '';
        $twiz_easing_a['linear'] = ($data[self::F_EASING_A] == 'linear') ? ' selected="selected"' : '';
        $twiz_easing_b['swing'] = ($data[self::F_EASING_B] == 'swing') ? ' selected="selected"' : '';
        $twiz_easing_b['linear'] = ($data[self::F_EASING_B] == 'linear') ? ' selected="selected"' : '';
        
        $imagemove_a = $this->getDirectionalImage($data, 'a');
        $imagemove_b = $this->getDirectionalImage($data, 'b');
        
        /* reset id if it's a new copy */
        $id = ($action == self::ACTION_COPY) ? '' : $id;
        
        /* Added to be recognized by the translator */
        $ttcopy = __('Copy', 'the-welcomizer');
        
        $eventlist = $this->getHtmlEventList($data[self::F_ON_EVENT]);
        $element_type_list = $this->getHtmlElementTypeList($data[self::F_TYPE]);
        
        /* creates the form */
        $htmlform = $opendiv.'<table class="twiz-table-form" cellspacing="0" cellpadding="0">
<tr><td class="twiz-form-td-left">'.__('Status', 'the-welcomizer').': <div class="twiz-float-right"><input type="checkbox" id="twiz_'.self::F_STATUS.'" name="twiz_'.self::F_STATUS.'" '.$twiz_status.'></div></td>
<td class="twiz-form-td-right">'.__('Action', 'the-welcomizer').'<div class="twiz-green">'.__($action, 'the-welcomizer').'</div></td></tr>
<tr><td class="twiz-form-td-left">'.__('Trigger by Event', 'the-welcomizer').': <div id="twiz_div_choose_event" class="twiz-float-right">'.$eventlist.'</div><td class="twiz-form-td-right twiz-float-left">'.__('(optional)', 'the-welcomizer').'</td></tr>
<tr><td class="twiz-form-td-left">'.__('Element', 'the-welcomizer').': <div class="twiz-float-right">'.$element_type_list.'</div></td><td  class="twiz-form-td-right twiz-float-left"><input class="twiz-input-text" id="twiz_'.self::F_LAYER_ID.'" name="twiz_'.self::F_LAYER_ID.'" type="text" value="'.$data[self::F_LAYER_ID].'" maxlength="50"></td></tr>
<tr><td class="twiz-form-td-left"></td><td class="twiz-form-td-right"><div class="twiz-float-left">'.__('Start delay', 'the-welcomizer').':</div> <div class="twiz-green twiz-float-right"><input class="twiz-input-small" id="twiz_'.self::F_START_DELAY.'" name="twiz_'.self::F_START_DELAY.'" type="text" value="'.$data[self::F_START_DELAY].'" maxlength="100"><small>1000 = 1 '.__('sec', 'the-welcomizer').'</small></div></td></tr>
<tr><td class="twiz-form-td-left"><a name="twiz_starting_config" id="twiz_starting_config" class="twiz-more-configs">'.__('More configurations', 'the-welcomizer').' &#187;</a></td><td class="twiz-form-td-right"><div class="twiz-float-left">'.__('Duration', 'the-welcomizer').':</div> <div class="twiz-green twiz-float-right">2x <input class="twiz-input-small" id="twiz_'.self::F_DURATION.'" name="twiz_'.self::F_DURATION.'" type="text" value="'.$data[self::F_DURATION].'" maxlength="100"><small>1000 = 1 '.__('sec', 'the-welcomizer').'</small></div></td></tr>
<tr id="twiz_tr_starting_config">
    <td valign="top"><hr>
        <table> 
 <tr><td colspan="2" class="twiz-caption"><b>'.__('Starting Positions', 'the-welcomizer').'</b> <select name="twiz_'.self::F_OUTPUT_POS.'" id="twiz_'.self::F_OUTPUT_POS.'">
        <option value="r" '.$twiz_ouput_pos['ready'].'>'.__('OnReady', 'the-welcomizer').'</option>
        <option value="b" '.$twiz_ouput_pos['before'].'>'.__('Before the delay', 'the-welcomizer').'</option>
        <option value="a" '.$twiz_ouput_pos['after'].'>'.__('After the delay', 'the-welcomizer').'</option>
        </select></td></tr>        
            <tr><td class="twiz-td-small-left-start" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td>
            <select name="twiz_'.self::F_START_TOP_POS_SIGN.'" id="twiz_'.self::F_START_TOP_POS_SIGN.'">
                <option value="" '.$twiz_start_top_pos_sign['nothing'].'>+</option>
                <option value="-" '.$twiz_start_top_pos_sign['-'].'>-</option>
                </select><input class="twiz-input-small" id="twiz_'.self::F_START_TOP_POS.'" name="twiz_'.self::F_START_TOP_POS.'" type="text" value="'.$data[self::F_START_TOP_POS].'" maxlength="5"> '.$this->getHtmlFormatList(self::F_START_TOP_POS_FORMAT, $data[self::F_START_TOP_POS_FORMAT]).'</td></tr>
            <tr><td class="twiz-td-small-left-start" nowrap="nowrap">'.__('Left', 'the-welcomizer').':</td><td>
            <select name="twiz_'.self::F_START_LEFT_POS_SIGN.'" id="twiz_'.self::F_START_LEFT_POS_SIGN.'">
                <option value="" '.$twiz_start_left_pos_sign['nothing'].'>+</option>
                <option value="-" '.$twiz_start_left_pos_sign['-'].'>-</option>
                </select><input class="twiz-input-small" id="twiz_'.self::F_START_LEFT_POS.'" name="twiz_'.self::F_START_LEFT_POS.'" type="text" value="'.$data[self::F_START_LEFT_POS].'" maxlength="5"> '.$this->getHtmlFormatList(self::F_START_LEFT_POS_FORMAT, $data[self::F_START_LEFT_POS_FORMAT]).'</td></tr>
        <tr><td class="twiz-td-small-left-start">'.__('Position', 'the-welcomizer').':</td><td>
        <select name="twiz_'.self::F_POSITION.'" id="twiz_'.self::F_POSITION.'"><option value="'.self::POS_NO_POS.'" '.$twiz_position[self::POS_NO_POS].'></option>
        <option value="'.self::POS_ABSOLUTE.'" '.$twiz_position[self::POS_ABSOLUTE].'>'.__('absolute', 'the-welcomizer').'</option>
        <option value="'.self::POS_RELATIVE.'" '.$twiz_position[self::POS_RELATIVE].'>'.__('relative', 'the-welcomizer').'</option>
        <option value="'.self::POS_FIXED.'" '.$twiz_position[self::POS_FIXED].'>'.__('fixed', 'the-welcomizer').'</option>
        <option value="'.self::POS_STATIC.'" '.$twiz_position[self::POS_STATIC].'>'.__('static', 'the-welcomizer').'</option>
        </select>
        </td></tr>                
          <tr><td class="twiz-td-small-left-start">'.__('z-index', 'the-welcomizer').':</td><td>
        <input class="twiz-input-small" id="twiz_'.self::F_ZINDEX.'" name="twiz_'.self::F_ZINDEX.'" type="text" value="'.$data[self::F_ZINDEX].'" maxlength="5">
            </td></tr>            
        </table>
    </td>
    <td valign="top"><hr>
<table> 
 <tr><td class="twiz-caption">
    <b>'.__('JavaScript', 'the-welcomizer').'</b> <select name="twiz_'.self::F_OUTPUT.'" id="twiz_'.self::F_OUTPUT.'">
        <option value="r" '.$twiz_ouput['ready'].'>'.__('OnReady', 'the-welcomizer').'</option>
        <option value="b" '.$twiz_ouput['before'].'>'.__('Before the delay', 'the-welcomizer').'</option>
        <option value="a" '.$twiz_ouput['after'].'>'.__('After the delay', 'the-welcomizer').'</option>
        </select>
      </td></tr>            
        </table>
<textarea onclick="textarea.expand(this)" rows="1" rows="3" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_'.self::F_JAVASCRIPT.'" name="twiz_'.self::F_JAVASCRIPT.'" type="text" >'.$data[self::F_JAVASCRIPT].'</textarea>'.$this->getHtmlFunctionList($id, 'javascript', $section_id).'<br><span class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').'<br>$(\'#realid\').css({\'display\':\'block\'});</span></td>
</tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td valign="top">
        <table>
            <tr><td class="twiz-caption" colspan="3"><b>'.__('First Move', 'the-welcomizer').'</b> <select name="twiz_'.self::F_EASING_A.'" id="twiz_'.self::F_EASING_A.'"><option value="swing" '.$twiz_easing_a['swing'].'>'.__('Swing', 'the-welcomizer').'</option>
        <option value="linear" '.$twiz_easing_a['linear'].'>'.__('Linear', 'the-welcomizer').'</option>
        </select></td></tr>
            <tr><td class="twiz-td-small-left" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td nowrap="nowrap">
            <select name="twiz_'.self::F_MOVE_TOP_POS_SIGN_A.'" id="twiz_'.self::F_MOVE_TOP_POS_SIGN_A.'">
            <option value="" '.$twiz_move_top_pos_sign_a[' '].'> </option>
            <option value="+" '.$twiz_move_top_pos_sign_a['+'].'>+</option>
            <option value="-" '.$twiz_move_top_pos_sign_a['-'].'>-</option>
            </select><input class="twiz-input twiz-input-small" id="twiz_move_top_pos_a" name="twiz_move_top_pos_a" type="text" value="'.$data[self::F_MOVE_TOP_POS_A].'" maxlength="5"> '.$this->getHtmlFormatList(self::F_MOVE_TOP_POS_FORMAT_A, $data[self::F_MOVE_TOP_POS_FORMAT_A]).'</td><td rowspan="2" align="center" width="95" id="twiz_td_arrow_a">'.$imagemove_a.'</td></tr>
            <tr><td class="twiz-td-small-left" nowrap="nowrap">'.__('Left', 'the-welcomizer').':</td><td nowrap="nowrap">
            <select name="twiz_'.self::F_MOVE_LEFT_POS_SIGN_A.'" id="twiz_'.self::F_MOVE_LEFT_POS_SIGN_A.'">
            <option value="" '.$twiz_move_left_pos_sign_a[' '].'> </option>
            <option value="+" '.$twiz_move_left_pos_sign_a['+'].'>+</option>
            <option value="-" '.$twiz_move_left_pos_sign_a['-'].'>-</option>
            </select><input class="twiz-input twiz-input-small" id="twiz_'.self::F_MOVE_LEFT_POS_A.'" name="twiz_'.self::F_MOVE_LEFT_POS_A.'" type="text" value="'.$data[self::F_MOVE_LEFT_POS_A].'" maxlength="5"> '.$this->getHtmlFormatList(self::F_MOVE_LEFT_POS_FORMAT_A, $data[self::F_MOVE_LEFT_POS_FORMAT_A]).'</td></tr><tr><td></td><td><a name="twiz_more_options_a" id="twiz_more_options_a"  class="twiz-more-configs">'.__('More Options', 'the-welcomizer').' &#187;</a></td></tr></table>
            <table class="twiz-table-more-options">
                <tr><td><hr></td></tr><tr><td class="twiz-caption">'.__('Personalized options', 'the-welcomizer').'</td></tr><tr><td ><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_'.self::F_OPTIONS_A.'" name="twiz_'.self::F_OPTIONS_A.'" type="text" >'.$data[self::F_OPTIONS_A].'</textarea></td></tr>
                <tr><td id="twiz_td_full_option_a" class="twiz-td-picklist twiz-float-right"><a id="twiz_choose_options_a" name="twiz_choose_options_a">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>
                <tr><td  class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').' <br>
                opacity:0.5<br>
                width:\'200px\'
                </td></tr>        
                <tr><td><hr></td></tr>        
                <tr><td class="twiz-caption">'.__('Extra JavaScript', 'the-welcomizer').'</td></tr><tr><td ><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_'.self::F_EXTRA_JS_A.'" name="twiz_'.self::F_EXTRA_JS_A.'" type="text">'.$data[self::F_EXTRA_JS_A].'</textarea></td></tr><tr><td>'.$this->getHtmlFunctionList($id, 'javascript_a', $section_id).'</td></tr><tr><td  class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').'<br>$(\'#realid\').css({position:\'static\',<br>\'z-index\':\'1\'});</td></tr>
        </table>
</td>
<td valign="top">    
    <table>
        <tr><td class="twiz-caption" colspan="3"><b>'.__('Second Move', 'the-welcomizer').'</b> <select name="twiz_'.self::F_EASING_B.'" id="twiz_'.self::F_EASING_B.'"><option value="swing" '.$twiz_easing_b['swing'].'>'.__('Swing', 'the-welcomizer').'</option>
        <option value="linear" '.$twiz_easing_b['linear'].'>'.__('Linear', 'the-welcomizer').'</option>
        </select></td></tr>
        <tr><td class="twiz-td-small-left" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td nowrap="nowrap">
        <select name="twiz_'.self::F_MOVE_TOP_POS_SIGN_B.'" id="twiz_'.self::F_MOVE_TOP_POS_SIGN_B.'">
        <option value="" '.$twiz_move_top_pos_sign_b[' '].'> </option>
        <option value="-" '.$twiz_move_top_pos_sign_b['-'].'>-</option>
        <option value="+" '.$twiz_move_top_pos_sign_b['+'].'>+</option>
        </select><input class="twiz-input twiz-input-small" id="twiz_move_top_pos_b" name="twiz_move_top_pos_b" type="text" value="'.$data[self::F_MOVE_TOP_POS_B].'" maxlength="5"> '.$this->getHtmlFormatList(self::F_MOVE_TOP_POS_FORMAT_B, $data[self::F_MOVE_TOP_POS_FORMAT_B]).'</td><td rowspan="2" align="center" width="95" id="twiz_td_arrow_b">'.$imagemove_b.'</td></tr>
        <tr><td class="twiz-td-small-left" nowrap="nowrap">'.__('Left', 'the-welcomizer').':</td><td nowrap="nowrap">
        <select name="twiz_'.self::F_MOVE_LEFT_POS_SIGN_B.'" id="twiz_'.self::F_MOVE_LEFT_POS_SIGN_B.'">
        <option value="" '.$twiz_move_left_pos_sign_b[' '].'> </option>
        <option value="-" '.$twiz_move_left_pos_sign_b['-'].'>-</option>
        <option value="+" '.$twiz_move_left_pos_sign_b['+'].'>+</option>
        </select><input class="twiz-input twiz-input-small" id="twiz_'.self::F_MOVE_LEFT_POS_B.'" name="twiz_'.self::F_MOVE_LEFT_POS_B.'" type="text" value="'.$data[self::F_MOVE_LEFT_POS_B].'" maxlength="5"> '.$this->getHtmlFormatList(self::F_MOVE_LEFT_POS_FORMAT_B, $data[self::F_MOVE_LEFT_POS_FORMAT_B]).'</td></tr><tr><td></td><td><a name="twiz_more_options_b" id="twiz_more_options_b" class="twiz-more-configs">'.__('More Options', 'the-welcomizer').' &#187;</a></td></tr>
        </table>
        <table class="twiz-table-more-options">
            <tr><td><hr></td></tr><tr><td class="twiz-caption">'.__('Personalized options', 'the-welcomizer').'</td></tr><tr><td ><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_'.self::F_OPTIONS_B.'" name="twiz_'.self::F_OPTIONS_B.'" type="text">'.$data[self::F_OPTIONS_B].'</textarea></td></tr>
            <tr><td  id="twiz_td_full_option_b" class="twiz-td-picklist twiz-float-right"><a id="twiz_choose_options_b" name="twiz_choose_options_b">'.__('Pick from List', 'the-welcomizer').' &#187;</a></td></tr>
            <tr><td  class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').' <br> 
                opacity:1<br>
                width:\'100px\'
                </td></tr>        
            <tr><td ><hr></td></tr>
            <tr><td  class="twiz-caption">'.__('Extra JavaScript', 'the-welcomizer').'</td></tr><tr><td ><textarea onclick="textarea.expand(this)" rows="1" onkeyup="textarea.expand(this)" WRAP=OFF class="twiz-input twiz-input-large" id="twiz_'.self::F_EXTRA_JS_B.'" name="twiz_'.self::F_EXTRA_JS_B.'" type="text" value="">'.$data[self::F_EXTRA_JS_B].'</textarea></td></tr><tr><td>'.$this->getHtmlFunctionList($id, 'javascript_b', $section_id).'</td></tr><tr><td  class="twiz-td-e-g">'.__('e.g.', 'the-welcomizer').'<br>$(document).twizRepeat();<br>$(document).twizReplay();</td></tr>
        </table>
</td></tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td class="twiz-td-save" colspan="2"><span id="twiz_save_img_box" class="twiz-loading-gif-save"></span><a name="twiz_cancel" id="twiz_cancel">'.__('Cancel', 'the-welcomizer').'</a> <input type="button" name="twiz_save" id="twiz_save" class="button-primary twiz-save" value="'.__('Save', 'the-welcomizer').'" /><input type="hidden" name="twiz_'.self::F_ID.'" id="twiz_'.self::F_ID.'" value="'.$id.'"></td></tr>
</table>'.$closediv.$jsscript_open.$jsscript_autoexpand.$toggleoptions.$jsscript_hide.$jsscript_close;
    
        return $htmlform;
    }

    function getHtmlView( $id ){ 
        
        $data = '';
        
        if($id != ''){
            if(!$data = $this->getRow($id)){return false;}
        }

        $hasMovements = $this->hasMovements($data);
        $hasStartingConfigs = $this->hasStartingConfigs($data);
        

        $start_top_pos = ($data[self::F_START_TOP_POS]!='') ? $data[self::F_START_TOP_POS_SIGN].$data[self::F_START_TOP_POS].' '.$data[self::F_START_TOP_POS_FORMAT] : '';
        $start_left_pos = ($data[self::F_START_LEFT_POS]!='') ? $data[self::F_START_LEFT_POS_SIGN].$data[self::F_START_LEFT_POS].' '.$data[self::F_START_LEFT_POS_FORMAT] : '';
        $move_top_pos_a = ($data[self::F_MOVE_TOP_POS_A]!='') ? $data[self::F_MOVE_TOP_POS_SIGN_A].$data[self::F_MOVE_TOP_POS_A].' '.$data[self::F_MOVE_TOP_POS_FORMAT_A] : '';
        $move_left_pos_a = ($data[self::F_MOVE_LEFT_POS_A]!='') ? $data[self::F_MOVE_LEFT_POS_SIGN_A].$data[self::F_MOVE_LEFT_POS_A].' '.$data[self::F_MOVE_LEFT_POS_FORMAT_A] : '';
        $move_top_pos_b = ($data[self::F_MOVE_TOP_POS_B]!='') ? $data[self::F_MOVE_TOP_POS_SIGN_B].$data[self::F_MOVE_TOP_POS_B].' '.$data[self::F_MOVE_TOP_POS_FORMAT_B] : '';
        $move_left_pos_b = ($data[self::F_MOVE_LEFT_POS_B]!='') ? $data[self::F_MOVE_LEFT_POS_SIGN_B].$data[self::F_MOVE_LEFT_POS_B].' '.$data[self::F_MOVE_LEFT_POS_FORMAT_B] : '';
        
        $titleclass = ($data[self::F_STATUS]=='1') ? 'twiz-status-green' : 'twiz-status-red';
        
        $imagemove_a = $this->getDirectionalImage($data, 'a');
        $imagemove_b = $this->getDirectionalImage($data, 'b');
        $elementype = ($data[self::F_TYPE] == '') ? self::ELEMENT_TYPE_ID : $data[self::F_TYPE];
        
        
        $output_starting_pos = $this->getOutputLabel($data[self::F_OUTPUT_POS]);
        $output_javascript = $this->getOutputLabel($data[self::F_OUTPUT]);
        
        $easing_a = $this->getOutputEasingLabel($data[self::F_EASING_A]);
        $easing_b = $this->getOutputEasingLabel($data[self::F_EASING_B]);
        
        $javascript = str_replace("\n", "<br>", $data[self::F_JAVASCRIPT]);
        $extra_js_a = str_replace("\n", "<br>", $data[self::F_EXTRA_JS_A]);
        $extra_js_b = str_replace("\n", "<br>", $data[self::F_EXTRA_JS_B]);
        
        $javascript = $this->add_animation_link($data[self::F_JAVASCRIPT]);
        $extra_js_a = $this->add_animation_link($data[self::F_EXTRA_JS_A]);
        $extra_js_b = $this->add_animation_link($data[self::F_EXTRA_JS_B]);
        
        /* creates the view */
        $htmlview = '<table class="twiz-table-view" cellspacing="0" cellpadding="0">
        <tr><td class="twiz-view-td-left twiz-bold" valign="top"><span class="'.$titleclass.'">'.$elementype.'</span> = '.$data[self::F_LAYER_ID].'</td><td class="twiz-view-td-right" nowrap="nowrap">';
        
        if( ($hasStartingConfigs) or ((!$hasStartingConfigs) and (!$hasMovements)) ) {
        
            $hideclass = '';
        }else{
            $htmlview .='<a id="twiz_view_show_more_'.$data[self::F_ID].'" name="twiz_view_show_more_'.$data[self::F_ID].'" class="twiz-view-more-configs">'.__('More configurations', 'the-welcomizer').' &#187;</a>';
            $hideclass = 'class="twiz-tr-view-more"';
        }

        $htmlview .='</td></tr>
<tr '.$hideclass.'>
    <td colspan="2"><hr></td></tr>
        <tr '.$hideclass.'><td valign="top">
        <table>
         <tr><td class="twiz-caption" colspan="3" nowrap="nowrap"><b>'.__('Starting Positions', 'the-welcomizer').'</b>
         <div class="twiz-green">'.$output_starting_pos.'</div><div class="twiz-spacer"></div></td></tr>
            <tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td>'.$start_top_pos.'</td></tr>
            <tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('Left', 'the-welcomizer').':</td><td>'.$start_left_pos.'</td></tr>
            <tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('Position', 'the-welcomizer').':</td><td>'.' '.$data[self::F_POSITION].'</td></tr>
             <tr><td class="twiz-view-td-small-left" nowrap="nowrap">'.__('z-index', 'the-welcomizer').':</td><td>'.' '.$data[self::F_ZINDEX].'</td></tr>
        </table>
        </td>
        <td valign="top">
        <table>
         <tr><td class="twiz-caption"  nowrap="nowrap"><b>'.__('JavaScript', 'the-welcomizer').'</b>
         <div class="twiz-green">'.$output_javascript.'</div><div class="twiz-spacer"></div></td></tr>
             <tr><td>'.$javascript.'</td></tr>
        </table>    
        </td>
    </tr>';
    
    
        if($hasMovements) {

            $htmlview .= '<tr><td colspan="2"><hr></td></tr>
            <tr><td valign="top">
                    <table>
                        <tr><td class="twiz-caption" colspan="3" nowrap="nowrap"><b>'.__('First Move', 'the-welcomizer').'</b>
                        <div class="twiz-green">'.$easing_a.'</div><div class="twiz-spacer"></div></td></tr>
                        <tr><td class="twiz-view-td-small-left" valign="top" height="20" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td valign="top" nowrap="nowrap">'.$move_top_pos_a .'</td><td rowspan="2" align="center" width="95">'.$imagemove_a.'</td></tr>
                        <tr><td class="twiz-view-td-small-left"  nowrap="nowrap" valign="top">'.__('Left', 'the-welcomizer').':</td><td valign="top" nowrap="nowrap">'.$move_left_pos_a .'</td></tr></table>
                        <table class="twiz-view-table-more-options">
                            <tr><td><hr></td></tr>
                            <tr><td>'.str_replace("\n", "<br>",$data[self::F_OPTIONS_A]).'</td></tr>    
                            <tr><td><hr></td></tr>        
                            <tr><td>'.$extra_js_a.'</td></tr>
                    </table>
            </td>
            <td valign="top">    
                <table>
                    <tr><td class="twiz-caption" colspan="3" nowrap="nowrap"><b>'.__('Second Move', 'the-welcomizer').'</b>
                    <div class="twiz-green">'.$easing_b.'</div><div class="twiz-spacer"></div></td></tr>
                    <tr><td class="twiz-view-td-small-left" valign="top" height="20" nowrap="nowrap">'.__('Top', 'the-welcomizer').':</td><td valign="top" nowrap="nowrap">'.$move_top_pos_b.'</td><td rowspan="2" align="center" width="95">'.$imagemove_b.'</td></tr>
                    <tr><td class="twiz-view-td-small-left" nowrap="nowrap" valign="top">'.__('Left', 'the-welcomizer').':</td><td valign="top" nowrap="nowrap">'.$move_left_pos_b .'</td></tr>
                    </table>
                    <table class="twiz-view-table-more-options">
                        <tr><td><hr></td></tr>
                        <tr><td>'.str_replace("\n", "<br>", $data[self::F_OPTIONS_B]).'</td></tr>
                        <tr><td><hr></td></tr>
                        <tr><td>'.$extra_js_b.'</td></tr>
                    </table></td></tr>';
        
        }
        
        $htmlview .= '</table>';
    
        return $htmlview;
    }

    protected function getListArray( $where = '', $orderby = '' ){ 

        global $wpdb;

        $sql = "SELECT *, IF(".self::F_ON_EVENT." = '','0','1') AS event_order, IF(".self::F_ON_EVENT." = 'Manually','0','1') AS event_order_2 from ".$this->table.$where;

        switch($orderby){
        
            case '':
             
                $twiz_order_by = get_option('twiz_order_by');
                
                if( $twiz_order_by == self::F_ON_EVENT ){
                
                    $orderby = " order by event_order, event_order_2,".self::F_ON_EVENT.", ".self::F_START_DELAY.", ".self::F_LAYER_ID;
                    $code = update_option('twiz_order_by', self::F_ON_EVENT);
                    
                }else{
                
                    return $this->getListArray( $where , $twiz_order_by );
                }
                
                break;
                
            case self::F_ON_EVENT:
             
                $orderby = " order by event_order, event_order_2, ".self::F_ON_EVENT.",".self::F_START_DELAY.", ".self::F_LAYER_ID;
                $code = update_option('twiz_order_by', self::F_ON_EVENT);
                 
                break;
                
            case self::F_STATUS:
             
                $orderby = " order by ".self::F_STATUS.", ".self::F_LAYER_ID.", ".self::F_START_DELAY.", event_order, event_order_2,".self::F_ON_EVENT."";
                $code = update_option('twiz_order_by', self::F_STATUS);
                
                break;
                
            case self::F_LAYER_ID:
             
                $orderby = " order by ".self::F_LAYER_ID.", ".self::F_START_DELAY.", event_order, event_order_2,".self::F_ON_EVENT."";
                $code = update_option('twiz_order_by', self::F_LAYER_ID);
                
                break;
                
            case self::F_START_DELAY:

                $orderby = " order by CAST(".self::F_START_DELAY." AS SIGNED), ".self::F_LAYER_ID.", event_order, event_order_2,".self::F_ON_EVENT."";                
                $code = update_option('twiz_order_by', self::F_START_DELAY);                
                
                break;  
                
            case self::F_DURATION:
            
                $orderby = " order by CAST(".self::F_DURATION." AS SIGNED), ".self::F_LAYER_ID.", event_order, event_order_2,".self::F_ON_EVENT."";                  
                $code = update_option('twiz_order_by', self::F_DURATION);                
                
                break;  
        }
        
        $rows = $wpdb->get_results($sql.$orderby, ARRAY_A);

        return $rows;
    }

    function getHtmlList( $section_id = '', $id = '', $orderby = ''){ 
       
        $section_id = ( $section_id == '' ) ? $this->DEFAULT_SECTION : $section_id;
        $code = $this->updateSettingMenu( $section_id );
        
        /* from the menu */ 
        $where = " where ".self::F_SECTION_ID." = '".$section_id."'";
      
        $listarray = $this->getListArray( $where, $orderby ); // get all the data
        if(count($listarray)==0){ // if, display the default new form
            
            return $this->getHtmlForm('', self::ACTION_NEW, $section_id); 
            
        }else{ // else display the list
        
            return $this->createHtmlList($listarray, $id); // private
        }
        
    }
   
    protected function getHtmlImgStatus( $id = '', $status = '', $from = ''){
    
        if($id==''){ return ''; }
        if($status==''){ return ''; }
        $title = '';
        $prefix = '';
        
        switch($id){
        
            case 'global':
                
                $title = __('Global', 'the-welcomizer');
                break;
                
            default:
                
                if($from == ''){
                
                    $row = $this->getRow( $id ); 
                    $title = $id . ' - '.$row[self::F_EXPORT_ID];
                }else{
                    $prefix = $from."_";
                }
        }
 
        return '<img src="'.$this->pluginUrl.'/images/twiz-'.$status.'.png" id="twiz_status_img_'.$prefix.$id.'" name="twiz_status_img_'.$prefix.$id.'" title="'.$title.'" />';

    }
    
    private function getHtmlFunctionList( $id = '', $name = '', $section_id = ''){

        $where = ($section_id!='') ? " where ".self::F_SECTION_ID." = '".$section_id."'"." and ".self::F_STATUS."=1" : '';
        
        $listarray = $this->getListArray( $where, " order by ".self::F_ID ); // get all the data
        
        $select = '<select class="twiz-slc-options" name="twiz_slc_functions_'.$name.'" id="twiz_slc_functions_'.$name.'">';
        $select .= '<option value="">'.__('All animations', 'the-welcomizer').'</option>';
        
        if( $name == '' ){ return $select.'</select>'; }
        if( count($listarray) == 0 ){return $select.'</select>'; }
        
        foreach ( $listarray as $value ){
       
            $functionnames = 'twiz_'.$value[self::F_SECTION_ID] .'_'. str_replace("-","_",$value[self::F_LAYER_ID]).'_'.$value[self::F_EXPORT_ID].'();';
            $select .= '<option value="$(document).'.$functionnames.'">'.$value[self::F_ID].' - '.$functionnames.'</option>';
        }
        
        $select .= '</select>';
        
        return $select;
    }
    
    private function getHtmlElementTypeList( $type = self::ELEMENT_TYPE_ID ){
        
        $select = '<select name="twiz_'.self::F_TYPE.'" id="twiz_'.self::F_TYPE.'">';
         
        foreach ($this->array_element_type as $value){

            $selected = ($type == $value) ? ' selected="selected"' : '';
            
            $select .= '<option value="'.$value.'"'.$selected.'>'.$value.' =</option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }  
    
    function getHtmlEventList( $event = '', $extraid = '', $extraclass = ''  ){
    
        $valuelbl = '';
        $extraid = ($extraid == '') ? '' : '_'.$extraid;
        $extraclass = ($extraclass == '') ? '' : 'class="'.$extraclass.'"';
        
        $select = '<select name="twiz_'.self::F_ON_EVENT.$extraid.'" id="twiz_'.self::F_ON_EVENT.$extraid.'" '.$extraclass.'>';
        $select .= '<option value=""></option>';
            
        foreach ($this->array_on_event as $value){

            $selected = ($event == $value) ? ' selected="selected"' : '';
            $on = ($value != self::EV_MANUAL) ? 'On': '';
            $valuelbl = $this->format_on_event($value);
            $select .= '<option value="'.$value.'"'.$selected.'>'.$valuelbl.'</option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }  

    function format_on_event( $value = '' ){
    
        if($value != '' ){
            $value = ($value != self::EV_MANUAL) ? 'On'.$value: $value;
        }else{
            $value = '-';
        }
        
        return $value;
    }
    
    private function getHtmlFormatList( $name = '', $format = '' ){
        
        if( $name == '' ){ return ''; }
        
        $select = '<select name="twiz_'.$name.'" id="twiz_'.$name.'">';
         
        foreach ($this->array_format as $value){

            $selected = ($format == $value) ? ' selected="selected"' : '';
            
            $select .= '<option value="'.$value.'"'.$selected.'>'.$value.' </option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }  
    
    function getHtmlOptionList( $charid = '' ){
        
        if( $charid == '' ){ return ''; }
        
        $select = '<select class="twiz-slc-options" name="twiz_slc_options_'.$charid.'" id="twiz_slc_options_'.$charid.'">';
            
        $select .= '<option value="">'.__('Choose', 'the-welcomizer').'</option>';
            
        foreach ( $this->array_jQuery_options as $value ){
                    
            $select .= '<option value="'.$value.'">'.$value.'</option>';
        }
            
        $select .= '</select>';
            
        return $select;
    }    
    
    private function getOutputLabel( $type = '' ){
    
        switch($type){
            case 'r':
                return ''.__('OnReady', 'the-welcomizer').'';
            break;
            case 'b':
                return ''.__('Before the delay', 'the-welcomizer').'';
            break;
            case 'a':
                return ''.__('After the delay', 'the-welcomizer').'';    
            break;
        }
        
        return '';
    }
    
    private function getOutputEasingLabel( $type = '' ){
    
        switch($type){
            case 'swing':
                return ''.__('Swing', 'the-welcomizer').'';
            break;
            case 'linear':
                return ''.__('Linear', 'the-welcomizer').'';
            break; 
            break;
        }
        
        return '';
    }
    
    private function getRow( $id = '' ){ 
    
        global $wpdb;
        
        if( $id == '' ){ return false; }
    
        $sql = "SELECT * from ".$this->table." where ".self::F_ID." = '".$id."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
        
        return $row;
    }
    
    function getValue( $id = '', $column = '' ){ 
    
        global $wpdb;
        
        if($id==''){return false;}
        if($column==''){return false;}
        
        $column = ($column=="delay") ? self::F_START_DELAY : $column;
    
        $sql = "SELECT ".$column." from ".$this->table." where ".self::F_ID." = '".$id."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
      
        $value = $row[$column];
  
        return $value;
    }
    
    private function ExportidExists( $exportid = '' ){ 
    
        global $wpdb;
        
        if($exportid==''){return false;}
    
        $sql = "SELECT ".self::F_EXPORT_ID." from ".$this->table." where ".self::F_EXPORT_ID." = '".$exportid."'";
        $row = $wpdb->get_row($sql, ARRAY_A);
      
        if($row[self::F_EXPORT_ID]!=''){

            return true;
        }
  
        return false;
    }
    
    private function preloadImages(){
    
    
        $dirarray = $this->getSkinsDirectory();

        sort($dirarray);
              
        $html = '';
       
        foreach( $dirarray as $value ){
        
            $html .= '<img src="'.$this->pluginUrl.self::SKIN_PATH.$value.'/images/twiz-save.gif" class="twiz-preload-images" />';
            $html .= '<img src="'.$this->pluginUrl.self::SKIN_PATH.$value.'/images/twiz-loading.gif" class="twiz-preload-images" />';
        }
     
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-download.png" class="twiz-preload-images" />';
        $html .='<img src="'.$this->pluginUrl.'/images/twiz-inactive.png" class="twiz-preload-images" />';
        $html .='<img src="'.$this->pluginUrl.$this->skin.'/images/twiz-save.gif" class="twiz-preload-images" />';
        $html .='<img src="'.$this->pluginUrl.$this->skin.'/images/twiz-loading.gif" class="twiz-preload-images" />';        
        $html .='<img src="'.$this->pluginUrl.$this->skin.'/images/twiz-big-loading.gif" class="twiz-preload-images" />';
        $html .='<img src="'.$this->pluginUrl.$this->skin.'/images/twiz-menu-edit-bw.png" class="twiz-preload-images" />';
        $html .='<img src="'.$this->pluginUrl.$this->skin.'/images/twiz-menu-edit-color.png" class="twiz-preload-images" />';
        $html .='<img src="'.$this->pluginUrl.$this->skin.'/images/twiz-menu-delete-bw.png" class="twiz-preload-images" />';
        $html .='<img src="'.$this->pluginUrl.$this->skin.'/images/twiz-menu-delete-color.png" class="twiz-preload-images" />';
    
        return $html;
    }

    private function updateSettingMenu( $section_id = '' ){
    
        if($section_id == ''){
            $section_id = self::DEFAULT_SECTION_HOME;
        }
    
        /* update setting menu */
        $code = update_option('twiz_setting_menu', $section_id);
        
        return true;
    }
    
    private function UpdateTwizFunctions( $current_value = '', $new_value = '', $section_id = '') {

        global $wpdb;
            
        if($new_value==''){return false;}
        
        if( $current_value != $new_value ) {
        
            $current_value = str_replace("-", "_", $current_value);
            $new_value = str_replace("-", "_", $new_value);

            $sql = "SELECT * from ".$this->table. " WHERE ".self::F_SECTION_ID." = '".$section_id."'";
            $rows = $wpdb->get_results($sql, ARRAY_A);

            foreach ( $rows as $row ) {
            
                    // Replace all current ids
                    $updatesql = "UPDATE ".$this->table . " SET
                     ". self::F_JAVASCRIPT . " = replace(". self::F_JAVASCRIPT . ", '_".$current_value[self::F_LAYER_ID]."_".$current_value[self::F_EXPORT_ID]."', '_".$new_value."_".$current_value[self::F_EXPORT_ID]."') ,". self::F_EXTRA_JS_A . " = replace(". self::F_EXTRA_JS_A . ", '_".$current_value[self::F_LAYER_ID]."_".$current_value[self::F_EXPORT_ID]."', '_".$new_value."_".$current_value[self::F_EXPORT_ID]."') ,". self::F_EXTRA_JS_B . " = replace(". self::F_EXTRA_JS_B . ", '_".$current_value[self::F_LAYER_ID]."_".$current_value[self::F_EXPORT_ID]."', '_".$new_value."_".$current_value[self::F_EXPORT_ID]."') WHERE ". self::F_ID ." = '".$row[self::F_ID]."'";
                    $code = $wpdb->query($updatesql);
            } 
        }
        
        return true;
    }
    
    function save( $id = '' ){

        global $wpdb;

        $twiz_status = esc_attr(trim($_POST['twiz_'.self::F_STATUS]));
        $twiz_status = ($twiz_status=='true') ? 1 : 0;
        
		$twiz_section_id = esc_attr(trim($_POST['twiz_'.self::F_SECTION_ID]));
		 
        $twiz_layer_id = esc_attr(trim($_POST['twiz_'.self::F_LAYER_ID]));
        $twiz_layer_id = ($twiz_layer_id=='') ? '' : $twiz_layer_id;
        
        $twiz_move_top_pos_a  = esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_A]));
        $twiz_move_left_pos_a = esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_A]));
        $twiz_move_top_pos_b  = esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_B]));
        $twiz_move_left_pos_b = esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_B]));
        $twiz_start_top_pos   = esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS]));
        $twiz_start_left_pos  = esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS]));
        
        $twiz_move_top_pos_a  = ($twiz_move_top_pos_a=='') ? 'NULL' : $twiz_move_top_pos_a;
        $twiz_move_left_pos_a = ($twiz_move_left_pos_a=='') ? 'NULL' : $twiz_move_left_pos_a;
        $twiz_move_top_pos_b  = ($twiz_move_top_pos_b=='') ? 'NULL' : $twiz_move_top_pos_b;
        $twiz_move_left_pos_b = ($twiz_move_left_pos_b=='') ? 'NULL' : $twiz_move_left_pos_b;
        $twiz_start_top_pos   = ($twiz_start_top_pos=='') ? 'NULL' : $twiz_start_top_pos;
        $twiz_start_left_pos  = ($twiz_start_left_pos=='') ? 'NULL' : $twiz_start_left_pos;
        
        $twiz_options_a = esc_attr(trim($_POST['twiz_'.self::F_OPTIONS_A]));
        $twiz_options_b = esc_attr(trim($_POST['twiz_'.self::F_OPTIONS_B]));

        $twiz_extra_js_a = esc_attr(trim( $_POST['twiz_'.self::F_EXTRA_JS_A]));    
        $twiz_extra_js_b = esc_attr(trim($_POST['twiz_'.self::F_EXTRA_JS_B]));
        
        $twiz_javascript = esc_attr(trim($_POST['twiz_'.self::F_JAVASCRIPT]));
        
        $twiz_start_delay = esc_attr(trim($_POST['twiz_'.self::F_START_DELAY]));
        $twiz_duration = esc_attr(trim($_POST['twiz_'.self::F_DURATION]));
        $twiz_start_delay = ( $twiz_start_delay == '' ) ? '0' : $twiz_start_delay;
        $twiz_duration = ( $twiz_duration == '' ) ? '0' : $twiz_duration;
                        
        // a simple uniq id
        $exportid = uniqid();
                        
        if( $id == "" ){ // add new

            $sql = "INSERT INTO ".$this->table." 
                  (".self::F_EXPORT_ID."
                  ,".self::F_SECTION_ID."
                  ,".self::F_STATUS."
                  ,".self::F_TYPE."
                  ,".self::F_LAYER_ID."
                  ,".self::F_ON_EVENT."                  
                  ,".self::F_START_DELAY."
                  ,".self::F_DURATION."
                  ,".self::F_OUTPUT."
                  ,".self::F_OUTPUT_POS."
                  ,".self::F_JAVASCRIPT."
                  ,".self::F_START_TOP_POS_SIGN."
                  ,".self::F_START_TOP_POS."
                  ,".self::F_START_TOP_POS_FORMAT."
                  ,".self::F_START_LEFT_POS_SIGN."
                  ,".self::F_START_LEFT_POS."    
                  ,".self::F_START_LEFT_POS_FORMAT."    
                  ,".self::F_POSITION."    
                  ,".self::F_ZINDEX."    
                  ,".self::F_EASING_A." 
                  ,".self::F_EASING_B." 
                  ,".self::F_MOVE_TOP_POS_SIGN_A."
                  ,".self::F_MOVE_TOP_POS_A."
                  ,".self::F_MOVE_TOP_POS_FORMAT_A."
                  ,".self::F_MOVE_LEFT_POS_SIGN_A."
                  ,".self::F_MOVE_LEFT_POS_A."
                  ,".self::F_MOVE_LEFT_POS_FORMAT_A."
                  ,".self::F_MOVE_TOP_POS_SIGN_B."
                  ,".self::F_MOVE_TOP_POS_B."
                  ,".self::F_MOVE_TOP_POS_FORMAT_B."
                  ,".self::F_MOVE_LEFT_POS_SIGN_B."
                  ,".self::F_MOVE_LEFT_POS_B."
                  ,".self::F_MOVE_LEFT_POS_FORMAT_B."
                  ,".self::F_OPTIONS_A."
                  ,".self::F_OPTIONS_B."
                  ,".self::F_EXTRA_JS_A."
                  ,".self::F_EXTRA_JS_B."         
                  )values('".$exportid."'
                  ,'".$twiz_section_id."'
                  ,'".$twiz_status."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_TYPE]))."'
                  ,'".$twiz_layer_id."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_ON_EVENT]))."'
                  ,'".$twiz_start_delay."'
                  ,'".$twiz_duration."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT]))."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT_POS]))."'
                  ,'".$twiz_javascript."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS_SIGN]))."'    
                  ,".$twiz_start_top_pos."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS_FORMAT]))."'    
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS_SIGN]))."'    
                  ,".$twiz_start_left_pos."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS_FORMAT]))."' 
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_POSITION]))."'                     
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_ZINDEX]))."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_EASING_A]))."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_EASING_B]))."'                  
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A]))."'    
                  ,".$twiz_move_top_pos_a."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_A]))."'  
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A]))."'    
                  ,".$twiz_move_left_pos_a."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_A]))."' 
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_B]))."'                     
                  ,".$twiz_move_top_pos_b."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_B]))."'
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_B]))."'    
                  ,".$twiz_move_left_pos_b."
                  ,'".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_B]))."'    
                  ,'".$twiz_options_a."'                             
                  ,'".$twiz_options_b."'
                  ,'".$twiz_extra_js_a."'                             
                  ,'".$twiz_extra_js_b."'                 
                  );";
            
            $code = $wpdb->query($sql);
             
            if( $code ){ 
            
                return $wpdb->insert_id;
            }
            
            return $code;

        }else{ // update
            
            $sql = "SELECT ".self::F_EXPORT_ID.", ".self::F_LAYER_ID." from ".$this->table." where ".self::F_ID." = '".$id."'";
            $current_value = $wpdb->get_row($sql, ARRAY_A);
        
            $sql = "UPDATE ".$this->table." 
                  SET ".self::F_SECTION_ID." = '".$twiz_section_id."'
                 ,".self::F_STATUS." = '".$twiz_status."'
                 ,".self::F_TYPE."  = '".esc_attr(trim($_POST['twiz_'.self::F_TYPE]))."' 
                 ,".self::F_LAYER_ID." = '".$twiz_layer_id."'
                 ,".self::F_ON_EVENT." = '".esc_attr(trim($_POST['twiz_'.self::F_ON_EVENT]))."'
                 ,".self::F_START_DELAY." = '".$twiz_start_delay."'
                 ,".self::F_DURATION." = '".$twiz_duration."'
                 ,".self::F_OUTPUT." = '".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT]))."'
                 ,".self::F_OUTPUT_POS." = '".esc_attr(trim($_POST['twiz_'.self::F_OUTPUT_POS]))."'
                 ,".self::F_JAVASCRIPT." = '".$twiz_javascript."' 
                 ,".self::F_START_TOP_POS_SIGN." = '".esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS_SIGN]))."'
                 ,".self::F_START_TOP_POS." = ".$twiz_start_top_pos."
                 ,".self::F_START_TOP_POS_FORMAT." = '".esc_attr(trim($_POST['twiz_'.self::F_START_TOP_POS_FORMAT]))."'
                 ,".self::F_START_LEFT_POS_SIGN." = '".esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS_SIGN]))."'
                 ,".self::F_START_LEFT_POS."  = ".$twiz_start_left_pos."
                 ,".self::F_START_LEFT_POS_FORMAT." = '".esc_attr(trim($_POST['twiz_'.self::F_START_LEFT_POS_FORMAT]))."'
                 ,".self::F_POSITION."  = '".esc_attr(trim($_POST['twiz_'.self::F_POSITION]))."'                 
                 ,".self::F_ZINDEX."  = '".esc_attr(trim($_POST['twiz_'.self::F_ZINDEX]))."' 
                 ,".self::F_EASING_A."  = '".esc_attr(trim($_POST['twiz_'.self::F_EASING_A]))."' 
                 ,".self::F_EASING_B."  = '".esc_attr(trim($_POST['twiz_'.self::F_EASING_B]))."'                  
                 ,".self::F_MOVE_TOP_POS_SIGN_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_A]))."'
                 ,".self::F_MOVE_TOP_POS_A." = ".$twiz_move_top_pos_a."
                 ,".self::F_MOVE_TOP_POS_FORMAT_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_A]))."'
                 ,".self::F_MOVE_LEFT_POS_SIGN_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_A]))."'
                 ,".self::F_MOVE_LEFT_POS_A." = ".$twiz_move_left_pos_a."
                 ,".self::F_MOVE_LEFT_POS_FORMAT_A." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_A]))."'
                 ,".self::F_MOVE_TOP_POS_SIGN_B." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_SIGN_B]))."'
                 ,".self::F_MOVE_TOP_POS_B." = ".$twiz_move_top_pos_b."
                 ,".self::F_MOVE_TOP_POS_FORMAT_B." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_TOP_POS_FORMAT_B]))."'
                 ,".self::F_MOVE_LEFT_POS_SIGN_B." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_SIGN_B]))."'
                 ,".self::F_MOVE_LEFT_POS_B." = ".$twiz_move_left_pos_b."
                 ,".self::F_MOVE_LEFT_POS_FORMAT_B." = '".esc_attr(trim($_POST['twiz_'.self::F_MOVE_LEFT_POS_FORMAT_B]))."'
                 ,".self::F_OPTIONS_A." = '".$twiz_options_a."'
                 ,".self::F_OPTIONS_B." = '".$twiz_options_b."'
                 ,".self::F_EXTRA_JS_A." = '".$twiz_extra_js_a."'
                 ,".self::F_EXTRA_JS_B." = '".$twiz_extra_js_b."'                 
                  WHERE ".self::F_ID." = '".$id."';";
                    
            $code = $wpdb->query($sql);
            
            $ok = $this->UpdateTwizFunctions( $current_value, $twiz_layer_id, $twiz_section_id);
               
            return $id;
        }
    }
    
    function saveValue( $id = '', $column = '', $value = '' ){ 
        
        global $wpdb;
            
            if( $id == '' ){return false;}
            if( $column == '' ){return false;}
            
            $column = ($column=="delay") ? self::F_START_DELAY : $column;
            
            $sql = "UPDATE ".$this->table." 
                    SET ".$column." = '".$value."'                 
                    WHERE ".self::F_ID." = '".$id."';";
            $code = $wpdb->query($sql);
                        
            return $code;
    }
    
    function switchGlobalStatus(){ 

        $gstatus = get_option('twiz_global_status');
        
        $newglobalstatus = ($gstatus == '0') ? '1' : '0'; // swicth the status value
                
        $code = update_option('twiz_global_status', $newglobalstatus);
    
        $htmlstatus = ($newglobalstatus=='1') ? $this->getHtmlImgStatus('global', self::STATUS_ACTIVE) : $this->getHtmlImgStatus('global', self::STATUS_INACTIVE);

        return $htmlstatus;
    }
    
    private function getImgGlobalStatus(){ 
        
        $gstatus = get_option('twiz_global_status');
        
        $htmlstatus = ($gstatus == '1') ? $this->getHtmlImgStatus('global', self::STATUS_ACTIVE) : $this->getHtmlImgStatus('global', self::STATUS_INACTIVE);

        return $htmlstatus;
    }
       
    private function getHtmlSkinBullets() {
           
        $dirarray = $this->getSkinsDirectory();
		
        $html_open = '<div id="twiz_skin_bullet">';
        $html_img = '';
        $html_close = '</div>';
        
        sort($dirarray);
        
        foreach($dirarray as $value){
        
            $html_img .='<img src="'.$this->pluginUrl.self::SKIN_PATH.$value.'/twiz-bullet.png" id="twiz_skin_'.$value.'" class="twiz-skins" />';
        }

        return $html_open.$html_img.$html_close;
    }
    
    function switchStatus( $id ){ 
    
        global $wpdb;
        
        if( $id == '' ){return false;}
    
        $value = $this->getValue($id, self::F_STATUS);
        
        $newstatus = ($value[self::F_STATUS]=='1') ? '0' : '1'; // swicth the status value
        
        $sql = "UPDATE ".$this->table." 
                SET ".self::F_STATUS." = '".$newstatus."'
                WHERE ".self::F_ID." = '".$id."'";

        $code = $wpdb->query($sql);
        
        if($code){
        
            $htmlstatus = ($newstatus=='1') ? $this->getHtmlImgStatus($id, self::STATUS_ACTIVE) : $this->getHtmlImgStatus($id, self::STATUS_INACTIVE);
        }else{ 
        
            $htmlstatus = ($value[self::F_STATUS]=='1') ? $this->getHtmlImgStatus($id, self::STATUS_ACTIVE) : $this->getHtmlImgStatus($id, self::STATUS_INACTIVE);
        }
        
        return $htmlstatus;
    }
    
    function uninstall(){
    
        global $wpdb;

        if ($wpdb->get_var( "show tables like '".$this->table."'" ) == $this->table) {
        
            $sql = "DROP TABLE ". $this->table;
            $wpdb->query($sql);
        }
        
        delete_option('twiz_db_version');
        delete_option('twiz_global_status');
        delete_option('twiz_setting_menu');
        delete_option('twiz_sections');
        delete_option('twiz_multi_sections');
        delete_option('twiz_hardsections');
        delete_option('twiz_library');
        delete_option('twiz_admin');
        delete_option('twiz_skin');
        delete_option('twiz_order_by');
        
        return true;
    }
}
?>