<?php
define('WP_ADMIN', true);

$_SERVER['PHP_SELF'] = $PHP_SELF = '/wp-admin/'.preg_replace( '/(\?.*)?$/', '', $_SERVER["REQUEST_URI"] );

if ( defined('ABSPATH') ){
    require_once(ABSPATH . 'wp-load.php');
}
else{
    require_once('../../../../../../wp-load.php');
}
        
// Set the multi-language file, english is the standard.
load_plugin_textdomain( 'the-welcomizer', false, dirname( plugin_basename( __FILE__ ) ).'/../../../languages/' ); 


// Info: http://wordpress.org/support/topic/fatal-error-call-to-undefined-function-wp_verify_nonce
require_once(ABSPATH .'wp-includes/pluggable.php'); 

/* Require Twiz Class */
require_once(dirname(__FILE__).'/../../twiz.class.php'); 
require_once(dirname(__FILE__).'/../../twiz.library.class.php'); 

$_POST['twiz_nonce']      = (!isset($_POST['twiz_nonce'])) ? '' : $_POST['twiz_nonce'];
$_GET['twiz_nonce']       = (!isset($_GET['twiz_nonce'])) ? '' : $_GET['twiz_nonce'];
$_POST['twiz_action']     = (!isset($_POST['twiz_action'])) ? '' : $_POST['twiz_action'];
$_GET['twiz_action']      = (!isset($_GET['twiz_action'])) ? '' : $_GET['twiz_action']; 
$_POST['twiz_section_id'] = (!isset($_POST['twiz_section_id'])) ? '' : $_POST['twiz_section_id'];
$_GET['twiz_section_id']  = (!isset($_GET['twiz_section_id'])) ? '' : $_GET['twiz_section_id']; 

$nonce = ($_POST['twiz_nonce'] == '') ? $_GET['twiz_nonce'] : $_POST['twiz_nonce'];
$action = ($_POST['twiz_action'] == '') ? $_GET['twiz_action'] : $_POST['twiz_action'];

/* Nonce security import security check */
if (! wp_verify_nonce($nonce, 'twiz-nonce') ) {
    die("Security check"); 
}

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception( __('Getting content length is not supported.', 'the-welcomizer'));
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader extends TwizLibrary{
    private $allowedExtensions = array();
    private $sizeLimit = 8388608;
    private $file;
    private $action;
    
    function __construct(array $allowedExtensions = array(), $sizeLimit = 8388608, $action=''){        
        
        parent::__construct();
        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        $this->action = $action;
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'".__('php.ini increase post_max_size and upload_max_filesize to ', 'the-welcomizer').$size."'}");    
        }      
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
                           
        /* twiz class */
        if (!is_writable($uploadDirectory)){
            @mkdir($uploadDirectory, 755); 
        }
        
        if (!is_writable($uploadDirectory)){
            return array('error' => __('You must first create this directory', 'the-welcomizer')." '".$this->import_path_message."'");
        }
        
        if (!$this->file){
            return array('error' => __('No files were uploaded.', 'the-welcomizer'));
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => __('File is empty', 'the-welcomizer') );
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => __('File is too large', 'the-welcomizer'));
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $this->file->getName(); 
        
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => __('File has an invalid extension, it should be one of ', 'the-welcomizer'). $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (@file_exists($uploadDirectory . $filename )){
                $filename = rand(10, 99).$filename;
            }
        }
        
        if ($this->file->save($uploadDirectory . $filename )){
                 
            switch($this->action){
            
                case parent::ACTION_UPLOAD_LIBRARY:
                    
                    $maxorder = $this->getMax(parent::KEY_ORDER) + 1;
                    $maxid = $this->getMax(parent::F_ID) + 1;
                    
                    $library = array(parent::F_ID         => $maxid,
                                     parent::F_STATUS     => 0, 
                                     parent::KEY_ORDER    => $maxorder,  
                                     parent::KEY_FILENAME => $filename);
                                     
                    if(! $code = $this->addLibrary($library)){

                        return array('error' => __('File is corrupted and unreadable, the import was cancelled.', 'the-welcomizer'));
                    }

                    return array('success' => true);
                     
                    break;
                    
                case parent::ACTION_IMPORT:
                
                    $return_array = 'w';
                    
                     /* get section id */
                     $sectionid = ($_POST['twiz_section_id']=='') ? $_GET['twiz_section_id'] : $_POST['twiz_section_id'];

                    /* import list data */
                    if( !$code = $this->import($sectionid)){
                    
                        $return_array = array('error' => __('File is corrupted and unreadable, the import was cancelled.', 'the-welcomizer'));
                    }
                    
                    if(@file_exists($uploadDirectory . $filename)) {
                    
                        unlink($uploadDirectory . $filename );
                        
                        $return_array = array('success' => true);
                    }      
                    
                    return $return_array;
                    
                    break;
            } 
        }
        
        /* delete file */
        if(@file_exists($uploadDirectory . $filename)) {
        
            unlink($uploadDirectory . $filename );
        }
        
        return array('error'=> __('Could not save uploaded file. The upload was cancelled, or server error encountered', 'the-welcomizer'));
    }    
}

    // list of valid extensions, ex. array("jpeg", "xml", "bmp")
    switch($action){

        case Twiz::ACTION_UPLOAD_LIBRARY:
        
            $allowedExtensions = array(Twiz::EXT_JS, Twiz::EXT_CSS);
            
            break;
            
        case Twiz::ACTION_IMPORT:
        
            $allowedExtensions = array(Twiz::EXT_TWZ, Twiz::EXT_TWIZ, Twiz::EXT_XML);
            
            break;
            
       default:
       
            die("Security check");
    }

    // max file size in bytes
    $sizeLimit = Twiz::IMPORT_MAX_SIZE;

    $uploader = new qqFileUploader($allowedExtensions, $sizeLimit, $action);
    $result = $uploader->handleUpload(WP_CONTENT_DIR.Twiz::IMPORT_PATH);

    // to pass data through iframe you will need to encode all html tags
    $htmlresponse = htmlspecialchars(json_encode($result), ENT_NOQUOTES); 
    
    echo($htmlresponse);
?>