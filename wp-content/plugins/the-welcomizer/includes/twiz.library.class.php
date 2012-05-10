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

class TwizLibrary extends Twiz{
	
    /* variable declaration */
    public $array_library;
	
    function __construct(){
    
        parent::__construct();
                 
        $this->loadLibrary();
    }

    function getHtmlLibrary(){
    
        $html = '<div id="twiz_library_master">';
        
        $html .= $this->getHtmlLibraryList();

        $html .= '</div>';
        
        return $html;
    }
    
    private function getHtmlLibraryList(){

        $rowcolor = '';
        
         /* hide element */
         $jquery = '<script>
//<![CDATA[
jQuery(document).ready(function($) {
    $("#twiz_new").fadeOut("slow");
    $("#twiz_add_menu").fadeOut("slow");
    $("#twiz_edit_menu").fadeOut("slow");
    $("#twiz_delete_menu").fadeOut("slow");
    $("#twiz_add_sections").fadeOut("slow"); 
    $("#twiz_right_panel").fadeOut("slow");
    $("#twiz_library_upload").fadeIn("slow");   
});
//]]>
</script>';


        $html = '<table class="twiz-table-list" cellspacing="0">';
        
        $html.= '<tr class="twiz-table-list-tr-h">
<td class="twiz-table-list-td-h twiz-td-center twiz-td-status">'.__('Status', 'the-welcomizer').'</td>
<td class="twiz-table-list-td-h twiz-td-left">'.__('Filename', 'the-welcomizer').'</td>
<td class="twiz-table-list-td-h twiz-td-lib-order twiz-td-center">'.__('Order', 'the-welcomizer').'</td>
<td class="twiz-table-list-td-h twiz-td-lib-action twiz-td-right">'.__('Action', 'the-welcomizer').'</td>
</tr>';
      
        
        foreach( $this->array_library as $value ){
             
            if( $this->libraryExists($value[parent::KEY_FILENAME], $value[parent::F_ID]) ) {
            
            if(!isset($value[parent::KEY_ORDER])) $value[parent::KEY_ORDER] = '0';

                $rowcolor = ($rowcolor=='twiz-row-color-1') ? 'twiz-row-color-2' : 'twiz-row-color-1';
            
                $statushtmlimg = ($value[parent::F_STATUS]=='1') ? $this->getHtmlImgStatus($value[parent::F_ID], parent::STATUS_ACTIVE, 'library') : $this->getHtmlImgStatus($value[parent::F_ID], parent::STATUS_INACTIVE, 'library');
                        
                $html.= '
    <tr class="'.$rowcolor.'" id="twiz_list_tr_'.$value[parent::F_ID].'"><td class="twiz-td-status twiz-td-center" id="twiz_td_status_library_'.$value[parent::F_ID].'">'.$statushtmlimg.'</td>
    <td class="twiz-table-list-td"><a href="'.WP_CONTENT_URL.parent::IMPORT_PATH.$value[parent::KEY_FILENAME].'" target="_blank">'.$value[parent::KEY_FILENAME].'</a></td>
     <td class="twiz-table-list-td twiz-td-center" id="twiz_list_td_'.$value[parent::F_ID].'"><div class="twiz-arrow-lib twiz-arrow-lib-n" name="twiz_new_order_up_'.$value[parent::F_ID].'" id="twiz_new_order_up_'.$value[parent::F_ID].'"></div><div class="twiz-arrow-lib twiz-arrow-lib-s" name="twiz_new_order_down_'.$value[parent::F_ID].'" id="twiz_new_order_down_'.$value[parent::F_ID].'"></div></td>
    <td class="twiz-table-list-td twiz-td-right"><img height="25" src="'.$this->pluginUrl.'/images/twiz-delete.gif" id="twiz_delete_'.$value[parent::F_ID].'" name="twiz_delete_'.$value[parent::F_ID].'" alt="'.__('Delete', 'the-welcomizer').'" title="'.__('Delete', 'the-welcomizer').'" class="twiz-delete" /></td></tr>';
            }
        }
        
        $html.= '</table>'.$jquery;
                 
        return $html;
        
    }
    
    protected function addLibrary( $lib = array() ){
        
        if( count($lib)==0 ){return false;}
                
        $this->array_library[] = $lib;
        
        $code = update_option('twiz_library', $this->array_library);
        
        return $code;

    }
    
    function deleteLibrary( $id = '' ){
    
        $library = $this->array_library;
 
        foreach( $library as $key => $value ){
        
            $file = WP_CONTENT_DIR . parent::IMPORT_PATH . $value[parent::KEY_FILENAME];
         
            if( $value[parent::F_ID] == $id ){
    
                if( @file_exists($file) ){
                
                    unlink($file);
                }
  
                $this->array_library[$key] = '';
                
                unset($this->array_library[$key]);
                
                $code = update_option('twiz_library', $this->array_library);
                
                return $code;

            }
        }
        
        return true;
    }
    
    private function getLibraryArrayKey( $keyneeded = '' ){
        
        $result = '';
        $i = 1;
        foreach( $this->array_library as $value ){
        
            if(!isset($value[$keyneeded])) $value[$keyneeded] = $i;
            
            $result[] = $value[$keyneeded];
            $i++;
        }
        
        if(!is_array($result)){ $result = array();}
        
        return $result;
    }
    
    private function getLibraryValue( $id = '', $field ) {

        foreach( $this->array_library as $value ){
        
            if( $value[parent::F_ID] == $id ){
       
                return $value[$field];
            }
        }
        
        return '';
    }
    
    protected function getMax( $key = '' ){
        
        $id = '';
        $i = 0;
        
        foreach( $this->array_library as $value ){
           
            if(!isset($value[$key])) $value[$key] = $i;

            $id[] = $value[$key];
            $i++;
        }

        if( !is_array($id) ){
        
            $id = array($i);
        }
        if( $key == parent::KEY_ORDER ){
            $max = max($id);
            $max = ($max < count($id)) ? count($this->array_library) : max($id);
  
        }else{
        
            $max = max($id);
        }
        
        return $max;
    }
    
    private function loadLibrary(){
    
        $this->array_library = get_option('twiz_library');
            
        if( !is_array($this->array_library) ){
        
            $this->array_library = array();
        }
        
        /* get libray filename array */
        $array_filename = $this->getLibraryArrayKey(parent::KEY_FILENAME);
        
        /* synchronize js files from /wp-content/twiz/ */
        $filearray = $this->getImportDirectory(array(parent::EXT_JS, parent::EXT_CSS));
        
        foreach( $filearray as $filename ){
            
            /* synchronize new js files */
            if(!in_array($filename, $array_filename)){
            
                $library = array(parent::F_ID         => $this->getMax(parent::F_ID) + 1,
                                 parent::F_STATUS     => 0, 
                                 parent::KEY_ORDER    => $this->getMax(parent::KEY_ORDER) + 1,  
                                 parent::KEY_FILENAME => $filename);
                                      
                $code = $this->addLibrary($library);
            }
        }
        
        /* get library order array */
        $array_order = $this->getLibraryArrayKey(parent::KEY_ORDER);
        
        array_multisort($array_order, SORT_ASC, SORT_NUMERIC, $this->array_library);
    }
    
    private function libraryExists( $filename = '', $id = '' ){
    
        if( $filename == '' ){return false;}
        if( $id == '' ){return false;}
        
        $file = WP_CONTENT_DIR.parent::IMPORT_PATH.$filename;
        
        if( @file_exists( $file ) ){
            
            return true;
           
        }else{
            
            if( $this->deleteLibrary($id) ){
            
                return false;
            }
        }
    }
    
    function switchLibraryStatus( $id = '' ){ 
    
        global $wpdb;
        
        if( $id=='' ){return false;}
    
		$cleanid = str_replace('library_','',$id);
		   
        $value = $this->getLibraryValue($cleanid, parent::F_STATUS);
        
        $newstatus = ( $value == '1' ) ? '0' : '1'; // swicth the status value
        
        $code = $this->updateLibraryValue($cleanid, parent::F_STATUS, $newstatus);
        
        if( $code == true ){
        
            $html = ( $newstatus == '1' ) ? $this->getHtmlImgStatus($cleanid, parent::STATUS_ACTIVE, 'library') : $this->getHtmlImgStatus($cleanid, parent::STATUS_INACTIVE, 'library');
        }else{ 
        
            $html = ( $value[parent::F_STATUS] == '1' ) ? $this->getHtmlImgStatus($cleanid, parent::STATUS_ACTIVE, 'library') : $this->getHtmlImgStatus($cleanid, parent::STATUS_INACTIVE, 'library');
            
        }
        
        return $html;
    }
      
    private function updateLibraryValue( $id = '', $field = '', $newvalue = '' ) {

        $library = $this->array_library;
 
        foreach( $library  as $key => $value ){
        
            if( $value[parent::F_ID] == $id ){
       
                $this->array_library[$key][$field] = $newvalue;
                
                $code = update_option('twiz_library', $this->array_library);
                
                return $code;
          
            }
        }
        
        return false;
    }
    
    function updateLibraryOrder( $id = '', $order = '' ) {
        
        $library = $this->array_library;
        $neworder = 1;
        $array_id = '';
        $max = '';
        $i = 1;
        
        // Update/set order
        foreach( $library as $key => $value ){
 
            $array_id[$i] = $value[parent::F_ID];
            
            // Check the id
            if( $value[parent::F_ID] == $id ){
            
                $ibase = $i; // We keep the key
                
                if(!isset($value[parent::KEY_ORDER]))$value[parent::KEY_ORDER] = $this->getMax( parent::KEY_ORDER ) + 1;             
                $maxkeyorder = $this->getMax( parent::KEY_ORDER );
 
                $neworder = ( $order == parent::LB_ORDER_UP ) ? $value[parent::KEY_ORDER] - 1 : $value[parent::KEY_ORDER] + 1;
                $neworder = ( $neworder < 1 ) ? 1 : $neworder;
                
                if( $maxkeyorder > 1 ){
                
                    $neworder = ( $neworder > $maxkeyorder ) ? $maxkeyorder : $neworder;
                }
              
                $ok = $this->updateLibraryValue( $value[parent::F_ID], parent::KEY_ORDER, $neworder );
            }
            $i++;
        }
        
        // Switch order
        switch($order){

            case parent::LB_ORDER_UP:
            
                $ibase--;
                $neworder++;
                
                break;
                
            case parent::LB_ORDER_DOWN:
            
                $ibase++;
                $neworder--;
                
                break;                    
        }

        if( $ibase < 1 ){ // no actions
        
            return true;
        }
       
        if( $ibase > $maxkeyorder ){  // no actions
        
            return true;
        }
        
        $neworder = ( $neworder < 1 ) ? 1 : $neworder;
          
        if( $maxkeyorder > 1 ){
        
           $neworder = ( $neworder > $maxkeyorder ) ? $maxkeyorder : $neworder;
        }
        
        if( !isset($array_id[$ibase]) ){
        
            $ibase = $id;
        }
        
        $ok = $this->updateLibraryValue( $array_id[$ibase] , parent::KEY_ORDER, $neworder);

        /* get library order array */
        $array_order = $this->getLibraryArrayKey(parent::KEY_ORDER);
        
        /* resort Library */
        array_multisort($array_order, SORT_ASC, SORT_NUMERIC, $this->array_library);
        
        return true;
    }    
}
?>