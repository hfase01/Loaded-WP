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
    
class TwizAjax extends Twiz{

    function __construct(){
    
        parent::__construct();
    }

    function getAjaxHeader(){
    $header = 'jQuery(document).ready(function($) {
 $.ajaxSetup({ cache: false });
 var twiz_skin =  "'.$this->skin.'";
 if((twiz_skin == "")||(twiz_skin == "'.parent::SKIN_PATH.'")){ twiz_skin = "'.parent::SKIN_PATH.''.parent::DEFAULT_SKIN.'";}
 var twiz_view_id = null;
 var twiz_current_section_id = "'.$this->DEFAULT_SECTION.'";
 if(twiz_current_section_id == ""){ twiz_current_section_id = "'.parent::DEFAULT_SECTION_HOME.'";}
 var twiz_default_section_id = "'.parent::DEFAULT_SECTION_HOME.'";
 var twiz_array_view_id = new Array();
 var twiz_ads_active = true;
 var twiz_import_file = new qq.FileUploader({
    element: document.getElementById("twiz_import_container"),
    action: "'.$this->pluginUrl.'/includes/import/server/php.php",
    debug: false,
    id: "twiz_import",
    label: "'.__('Import', 'the-welcomizer').'",
    allowedExtensions: ["'.parent::EXT_TWZ.'", "'.parent::EXT_TWIZ.'", "'.parent::EXT_XML.'"],
    sizeLimit: '.parent::IMPORT_MAX_SIZE.', // max size   
    minSizeLimit: 1, // min size
    onSubmit: function (){ twiz_import_file.setParams({action: "twiz_ajax_callback", twiz_nonce: "'.$this->nonce.'", twiz_action: "'.parent::ACTION_IMPORT.'", twiz_section_id: twiz_current_section_id }); },
    onComplete: function (){$("#twiz_export_url").html(""); twizPostMenu(twiz_current_section_id);},
    messages: {
        typeError: "'.__('{file} has invalid extension. Only {extensions} are allowed.', 'the-welcomizer').'",
        sizeError: "'.__('{file} is too large, maximum file size is {sizeLimit}.', 'the-welcomizer').'",
        minSizeError: "'.__('{file} is too small, minimum file size is {minSizeLimit}.', 'the-welcomizer').'",
        emptyError: "'.__('{file} is empty, please select files again without it.', 'the-welcomizer').'",
        onLeave: "'.__('The files are being uploaded, if you leave now the upload will be cancelled.', 'the-welcomizer').'",
        showMessage: function(message){ alert(message); }
    }
 });      
 var twiz_upload_file = new qq.FileUploader({
    element: document.getElementById("twiz_library_upload"),
    action: "'.$this->pluginUrl.'/includes/import/server/php.php",
    debug: false,
    id: "twiz_upload",
    label: "'.__('Upload', 'the-welcomizer').'",
    allowedExtensions: ["'.parent::EXT_JS.'", "'.parent::EXT_CSS.'"],
    sizeLimit: '.parent::IMPORT_MAX_SIZE.', // max size   
    minSizeLimit: 1, // min size
    onSubmit: function (){ twiz_upload_file.setParams({action: "twiz_ajax_callback", twiz_nonce: "'.$this->nonce.'", twiz_action: "'.parent::ACTION_UPLOAD_LIBRARY.'"});},
    onComplete: function (){$("#twiz_export_url").html(""); twizPostLibrary();},
    messages: {
        typeError: "'.__('{file} has invalid extension. Only {extensions} are allowed.', 'the-welcomizer').'",
        sizeError: "'.__('{file} is too large, maximum file size is {sizeLimit}.', 'the-welcomizer').'",
        minSizeError: "'.__('{file} is too small, minimum file size is {minSizeLimit}.', 'the-welcomizer').'",
        emptyError: "'.__('{file} is empty, please select files again without it.', 'the-welcomizer').'",
        onLeave: "'.__('The files are being uploaded, if you leave now the upload will be cancelled.', 'the-welcomizer').'",
        showMessage: function(message){ alert(message); }
    }
 });   
 var bind_twiz_New = function() {
     $("#twiz_new").click(function(){
     $("#twiz_add_sections").html("");  
     twiz_view_id = "edit";
     $(this).fadeOut("fast");
     $("#twiz_container").slideToggle("fast");
        $.post(ajaxurl,  {
        "action": "twiz_ajax_callback",
        "twiz_action": "'.parent::ACTION_NEW.'",
        "twiz_section_id": twiz_current_section_id,
        "twiz_nonce": "'.$this->nonce.'"
     }, function(data) {
            $("#twiz_container").html(data);
            $("#twiz_container").slideToggle("fast");
            twiz_view_id = null;
            bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
            bind_twiz_More_Configs();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
            bind_twiz_DynArrows();
        });
    });
 }
 function twizRightPanel(twiz_id){
    if((twiz_view_id != twiz_id)&&(twiz_view_id!="edit")&&(twiz_id!="global")&&(twiz_current_section_id!="library")){
        twiz_view_id = twiz_id;
        $("#twiz_right_panel").html(\'<div\' + \' class="twiz-panel-loading"></div>\');
        $("#twiz_right_panel").fadeIn("fast");    
        if(twiz_array_view_id[twiz_id]===undefined){
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": "'.parent::ACTION_VIEW.'",
            "twiz_id": twiz_id
            }, function(data) {
                $("#twiz_right_panel").html(data);
                twiz_array_view_id[twiz_id] = data;
                $(".twiz-view-more-configs").unbind("click");
                bind_twiz_view();
            });    
        }else{
            $("#twiz_right_panel").html(twiz_array_view_id[twiz_id]);
            $(".twiz-view-more-configs").unbind("click");
            bind_twiz_view();
        }
    }
 }
 var bind_twiz_Status = function() { 
    $("img[name^=twiz_status_img]").click(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_numid = twiz_textid.substring(16,twiz_textid.length);
        var twiz_c_action = "'.parent::ACTION_STATUS.'";
        if(twiz_current_section_id=="library"){
            twiz_c_action = "'.parent::ACTION_LIBRARY_STATUS.'";
        }
        var twiz_menuid = twiz_numid.substring(0,5);
        switch(twiz_menuid){
            case "vmenu":
            $(this).hide();
            $("#twiz_status_img_" + twiz_numid.replace("vmenu_", "menu_")).hide();
            $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif" />\');            
            $("#twiz_status_img_" + twiz_numid.replace("vmenu_", "menu_")).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif" />\');                              
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": "'.parent::ACTION_VMENU_STATUS.'",
            "twiz_id": twiz_numid
            }, function(data) {
                $("img[name^=twiz_status_img]").unbind("click");
                $("#twiz_status_" + twiz_numid).html(data);
                $("#twiz_status_" + twiz_numid.replace("vmenu_", "menu_")).html(data.replace(/vmenu_/g, "menu_"));                
                bind_twiz_Status();
            });
            break;    
        case "menu_":
            $(this).hide();
            $("#twiz_status_img_" + twiz_numid.replace("menu_", "vmenu_")).hide();    
            $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif" />\');   
            $("#twiz_status_img_" + twiz_numid.replace("menu_", "vmenu_")).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif" />\');   
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": "'.parent::ACTION_MENU_STATUS.'",
            "twiz_id": twiz_numid
            }, function(data) {
                $("img[name^=twiz_status_img]").unbind("click");
                $("#twiz_status_" + twiz_numid).html(data);
                $("#twiz_status_" + twiz_numid.replace("menu_", "vmenu_")).html(data.replace(/menu_/g, "vmenu_"));
                bind_twiz_Status();
            });
            break;
        default:
             switch(twiz_numid){
                case "global":
                    $(this).hide();
                    $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif" />\');        
                    $.post(ajaxurl, {
                    "action": "twiz_ajax_callback",
                    "twiz_nonce": "'.$this->nonce.'", 
                    "twiz_action": "'.parent::ACTION_GLOBAL_STATUS.'"
                    }, function(data) {
                        $("img[name^=twiz_status_img]").unbind("click");
                        $("#twiz_global_status").html(data);
                        bind_twiz_Status();
                    });
                    break;
                default:
                    $(this).hide();
                    $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif" />\');      
                    $.post(ajaxurl, {
                    "action": "twiz_ajax_callback",
                    "twiz_nonce": "'.$this->nonce.'", 
                    "twiz_action": twiz_c_action,
                    "twiz_id": twiz_numid
                    }, function(data) {
                        $("img[name^=twiz_status_img]").unbind("click");
                        $("#twiz_td_status_" + twiz_numid).html(data);
                        twiz_array_view_id[twiz_numid]=undefined;
                        twiz_view_id = null;
                        if((twiz_view_id != twiz_numid)&&(twiz_view_id!="edit")&&(twiz_current_section_id!="library")){
                            twiz_view_id = twiz_numid;
                            if(twiz_array_view_id[twiz_numid]===undefined){
                                $.post(ajaxurl, {
                                "action": "twiz_ajax_callback",
                                "twiz_nonce": "'.$this->nonce.'", 
                                "twiz_action": "'.parent::ACTION_VIEW.'",
                                "twiz_id": twiz_numid
                                }, function(data) {
                                    $("#twiz_right_panel").html(data);
                                    twiz_array_view_id[twiz_numid] = data;
                                });    
                            }else{
                                $("#twiz_right_panel").html(twiz_array_view_id[twiz_numid]);
                            }
                        }                
                        bind_twiz_Status();
                    });
                    break;
                }
        }            
    });
 }
 var bind_twiz_Edit = function() {
    $("img[name^=twiz_edit]").mouseover(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_numid = twiz_textid.substring(10,twiz_textid.length);
        $(".twiz_list_tr_action").css("visibility", "hidden");
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible");        
        twizRightPanel(twiz_numid);
    });   
    $(".twiz-edit").click(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_textidtemp = twiz_textid.substring(10,twiz_textid.length);
        var twiz_numid = "";
        if(twiz_textidtemp.substring(0,1) == "a"){
            twiz_numid = twiz_textid.substring(12,twiz_textid.length);
        }else{
            twiz_numid = twiz_textidtemp;
        }
        twiz_view_id = "edit";
        if(twiz_textidtemp.substring(0,1) == "a"){
            $("#twiz_list_tr_action_" + twiz_numid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
        }else{
            $(this).hide();
            $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif-action" />\');  
        }    
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_EDIT.'",
        "twiz_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data) {
            $("img[name^=twiz_status_img]").unbind("click");
            $(".twiz-edit").unbind("click");
            $("img[name^=twiz_edit]").unbind("mouseover");
            $(".twiz-copy").unbind("click");
            $("img[name^=twiz_copy]").unbind("mouseover");              
            $(".twiz-delete").unbind("click");
            $("img[name^=twiz_delete]").unbind("mouseover");            
            $("#twiz_cancel").unbind("click");
            $("#twiz_container").html(data);
            twiz_view_id = null;
            $("#twiz_container").show("slow");
            bind_twiz_Status();bind_twiz_Save();bind_twiz_Cancel();bind_twiz_Number_Restriction();
            bind_twiz_More_Configs();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
            bind_twiz_DynArrows();
        });
    });
 }
 var bind_twiz_Copy = function() {
    $("img[name^=twiz_copy]").mouseover(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_numid = twiz_textid.substring(10,twiz_textid.length);
        $(".twiz_list_tr_action").css("visibility", "hidden");
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible");
        twizRightPanel(twiz_numid);
    });   
    $(".twiz-copy").click(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_textidtemp = twiz_textid.substring(10,twiz_textid.length);
        var twiz_numid = "";
        if(twiz_textidtemp.substring(0,1) == "a"){
            twiz_numid = twiz_textid.substring(12,twiz_textid.length);
        }else{
            twiz_numid = twiz_textidtemp;
        }
        twiz_view_id = "edit";
        if(twiz_textidtemp.substring(0,1) == "a"){
            $("#twiz_list_tr_action_" + twiz_numid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
        }else{
            $(this).hide();
            $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif-action" />\'); 
        }  
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_COPY.'",
        "twiz_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data) {
            $("img[name^=twiz_status_img]").unbind("click");
            $(".twiz-edit").unbind("click");
            $("img[name^=twiz_edit]").unbind("mouseover");
            $(".twiz-copy").unbind("click");
            $("img[name^=twiz_copy]").unbind("mouseover");            
            $(".twiz-delete").unbind("click");
            $("img[name^=twiz_delete]").unbind("mouseover");
            $("#twiz_cancel").unbind("click");
            $("#twiz_container").html(data);
            twiz_view_id = null;
            $("#twiz_container").show("slow");
            bind_twiz_Status();bind_twiz_Save();bind_twiz_Cancel();bind_twiz_Number_Restriction();
            bind_twiz_More_Configs();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
            bind_twiz_DynArrows();
        });
    });
 } 
 var bind_twiz_Delete = function() {
    $("img[name^=twiz_delete]").mouseover(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_numid = twiz_textid.substring(12,twiz_textid.length);
        $(".twiz_list_tr_action").css("visibility", "hidden");
        $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible");
        twizRightPanel(twiz_numid);
    });  
    $(".twiz-delete").click(function(){
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")) {
            var twiz_textid = $(this).attr("name");
            var twiz_textidtemp = twiz_textid.substring(12,twiz_textid.length);
            var twiz_numid = "";            
            if(twiz_textidtemp.substring(0,1) == "a"){
                twiz_numid = twiz_textid.substring(14,twiz_textid.length);
            }else{
                twiz_numid = twiz_textidtemp;
            } 
            var twiz_c_action = "'.parent::ACTION_DELETE.'";
            if(twiz_current_section_id=="library"){
                twiz_c_action = "'.parent::ACTION_DELETE_LIBRARY.'";
            }            
            if(twiz_textidtemp.substring(0,1) == "a"){
                $("#twiz_list_tr_action_" + twiz_numid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
            }else{
                $(this).hide();
            $(this).after(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-save.gif" class="twiz-loading-gif-action" />\');  
            }  
            $("#twiz_right_panel").fadeOut("slow");
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": twiz_c_action,
            "twiz_id": twiz_numid 
             }, function(data) {        
                 $("#twiz_list_tr_" + twiz_numid).fadeOut();
                 twiz_array_view_id[twiz_numid] = undefined;
            });
        }
   });
 }
 var bind_twiz_Cancel = function() {
    $("#twiz_cancel").click(function(){
    $("#twiz_container").slideToggle("fast");
    $.post(ajaxurl, {
    "action": "twiz_ajax_callback",
    "twiz_nonce": "'.$this->nonce.'", 
    "twiz_action": "'.parent::ACTION_CANCEL.'",
    "twiz_section_id": twiz_current_section_id
    }, function(data) {
        $("img[name^=twiz_status_img]").unbind("click");
        $("#twiz_cancel").unbind("click");
        $("#twiz_save").unbind("click");
        $("#twiz_container").html(data);
        $("#twiz_container").slideToggle("fast");
        twiz_view_id = "";
        bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();bind_twiz_DynArrows();
        bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
        bind_twiz_More_Configs();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
        bind_twiz_Ajax_TD();bind_twiz_TR_View();bind_twiz_Order_by(); 
    });
   });
 }
 var bind_twiz_Save = function() {
    $("#twiz_save").click(function(){
    $("#twiz_save").attr({"disabled" : "true"});
    $("#twiz_save_img_box").html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
    var twiz_numid = $("#twiz_id").val();
    $.post(ajaxurl, {
         "action": "twiz_ajax_callback",
         "twiz_nonce": "'.$this->nonce.'", 
         "twiz_action": "'.parent::ACTION_SAVE.'", ';
         
         $i=0;
         $count_array = count($this->array_fields);
         
         foreach($this->array_fields as $value){
         
             $comma = ( $count_array != $i ) ? ','."\n" : '';
          
             switch($value){
                 
                 case parent::F_EXPORT_ID: // Skipped
                 
                    break;
                    
                 case parent::F_SECTION_ID:
                 
                    $header.= '"twiz_'.$value.'": twiz_current_section_id'.$comma;
                    break;  
                                   
                 case parent::F_ID:
                 
                    $header.= '"twiz_'.$value.'": twiz_numid'.$comma;
                    break;
                    
                 case parent::F_STATUS:
                 
                    $header.= '"twiz_'.$value.'": $("#twiz_'.$value.'").is(":checked")'.$comma;
                    break;
                                      
                 default:
                 
                    $header.= '"twiz_'.$value.'": $("#twiz_'.$value.'").val()'.$comma;
             }
             
             $i++;
         }
         
        $header.= '}, function(data) {
        $("img[name^=twiz_status_img]").unbind("click");
        $("#twiz_cancel").unbind("click");
        $("#twiz_save").unbind("click");
        $("#twiz_container").html(data);
        twiz_array_view_id[twiz_numid] = undefined;
        bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();
        bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
        bind_twiz_More_Configs();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
        bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();bind_twiz_Order_by(); 
    });
   });
  }
  var bind_twiz_AdminSave = function() {
    $("#twiz_admin_save").click(function(){
    $("#twiz_admin_save").attr({"disabled" : "true"});
    $("#twiz_admin_save_img_box").show();  
    $("#twiz_admin_save_img_box").html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');   
    var twiz_numid = $("#twiz_id").val();
    $.post(ajaxurl, {
         "action": "twiz_ajax_callback",
         "twiz_nonce": "'.$this->nonce.'", 
         "twiz_action": "'.parent::ACTION_SAVE_ADMIN.'",
         "twiz_register_jquery": $("#twiz_register_jquery").is(":checked"),
         "twiz_output_compression": $("#twiz_output_compression").is(":checked"),
         "twiz_slc_output": $("#twiz_slc_output").val(),
         "twiz_number_posts": $("#twiz_number_posts").val(),
         "twiz_min_rolelevel": $("#twiz_min_rolelevel").val(),
         "twiz_starting_position": $("#twiz_starting_position").val(),
         "twiz_delete_all": $("#twiz_delete_all").is(":checked")
    }, function(data) {
        $("#twiz_admin_save_img_box").html(\'<img\' + \' src="'.$this->pluginUrl.'\' +  \'/images/twiz-success.gif"\' + \'/>\');    
        $("#twiz_admin_save_img_box").fadeOut("slow");         
        $("#twiz_admin_save").removeAttr("disabled");
    });
   });
  }
  var bind_twiz_Number_Restriction = function() {
    $("#twiz_start_top_pos").keypress(function (e){
    if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_start_left_pos").keypress(function (e){
    if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_zindex").keypress(function (e){
    if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {if( e.which!=45 ) { return false; }}});
    $("#twiz_move_top_pos_a").keypress(function (e){
    if( e.which!=8 && e.which!=0 && e.which!=45 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_move_left_pos_a").keypress(function (e){
    if( e.which!=8 && e.which!=0 && e.which!=45 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_move_top_pos_b").keypress(function (e){
    if( e.which!=8 && e.which!=0 && e.which!=45 && (e.which<48 || e.which>57))
    {return false;}});
    $("#twiz_move_left_pos_b").keypress(function (e){
    if( e.which!=8 && e.which!=0 && e.which!=45 && (e.which<48 || e.which>57))
    {return false;}});
  }
  var bind_twiz_More_Configs = function() {
    $(".twiz-more-configs").click(function(){
        var twiz_textname = $(this).attr("name");
        if(twiz_textname == "twiz_starting_config"){
            $("#twiz_tr_starting_config").toggle();
        }else{
            $(".twiz-table-more-options").toggle();
        }
    });
  }
  var bind_twiz_Select_Id = function() {
    $("#twiz_slc_id").change(function(){
        $("#twiz_layer_id").attr({"value" : $(this).val()});
    });
  }    
  var bind_twiz_Choose_Options = function() {
    $("a[name^=twiz_choose_options]").click(function(){
        var twiz_textid = $(this).attr("name");
        var twiz_charid = twiz_textid.substring(20,twiz_textid.length);
        $("#twiz_td_full_option_" + twiz_charid).html(\'<img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
        $.post(ajaxurl, { 
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_OPTIONS.'",
        "twiz_charid": twiz_charid
        }, function(data) {
        $("#twiz_td_full_option_" + twiz_charid).html(data);
        bind_twiz_Select_Options(twiz_charid);
        });
    });
  }    
  var bind_twiz_Select_Options = function(twiz_charid) {
    $("#twiz_slc_options_" + twiz_charid).change(function(){
        var twiz_curval = $("#twiz_options_" + twiz_charid).val();
        if($(this).val()!=""){
            if(twiz_curval!=""){ twiz_curval = twiz_curval + "\n";}
            var twiz_optionstring =  $(this).val() + ":";
            $("#twiz_options_" + twiz_charid).attr({"value" : twiz_curval + twiz_optionstring}) 
        }
    });    
  }    
  var bind_twiz_Select_Functions = function() {
    $("#twiz_slc_functions_javascript").change(function(){
        var twiz_curval = $("#twiz_javascript").val();
        if($(this).val()!=""){
            if(twiz_curval!=""){ twiz_curval = twiz_curval + "\n";}
            var twiz_optionstring =  $(this).val();
            $("#twiz_javascript").attr({"value" : twiz_curval + twiz_optionstring}) 
        }
    });   
    $("#twiz_slc_functions_javascript_a").change(function(){
        var twiz_curval = $("#twiz_extra_js_a").val();
        if($(this).val()!=""){
            if(twiz_curval!=""){ twiz_curval = twiz_curval + "\n";}
            var twiz_optionstring =  $(this).val();
            $("#twiz_extra_js_a").attr({"value" : twiz_curval + twiz_optionstring}) 
        }
    }); 
    $("#twiz_slc_functions_javascript_b").change(function(){
        var twiz_curval = $("#twiz_extra_js_b").val();
        if($(this).val()!=""){
            if(twiz_curval!=""){ twiz_curval = twiz_curval + "\n";}
            var twiz_optionstring =  $(this).val();
            $("#twiz_extra_js_b").attr({"value" : twiz_curval + twiz_optionstring}) 
        }
    });     
  }    
  var bind_twiz_Ajax_TD = function() {
    $("div[id^=twiz_ajax_td_val]").mouseover(function(){
            $(this).attr({"title" : "'.__('Edit', 'the-welcomizer').'"});
            $(this).css({cursor:"pointer"});
    });        
    $("[name^=twiz_on_event]").change(function (){
        var twiz_textid = $(this).attr("name");        
        var twiz_numid = twiz_textid.substring(14,twiz_textid.length);
        var twiz_txtval = $("#twiz_on_event_" + twiz_numid).val();
        $("#twiz_ajax_td_edit_on_event_" + twiz_numid).hide();        
        $("#twiz_ajax_td_loading_on_event_" + twiz_numid).html(\'<img\' + \' name="twiz_img_loading_delay_\' + twiz_numid + \'[]" id="twiz_img_loading_delay_\' + twiz_numid + \'[]" src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');        
        $.post(ajaxurl, { 
                "action": "twiz_ajax_callback",
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.parent::ACTION_EDIT_TD.'",
                "twiz_id": twiz_numid, 
                "twiz_column": "on_event", 
                "twiz_value": twiz_txtval
                }, function(data) {
                    $("#twiz_ajax_td_loading_on_event_" + twiz_numid).html("");
                    $("[name^=twiz_on_event]").unbind("focusout");
                    $("[name^=twiz_on_event]").unbind("change");
                    $("div[id^=twiz_ajax_td_val]").unbind("click");
                    $("div[id^=twiz_ajax_td_val]").unbind("mouseover");
                    $("input[name^=twiz_input]").unbind("keypress");
                    $("input[name^=twiz_input]").unbind("blur");
                    $("#twiz_ajax_td_val_on_event_" + twiz_numid).html(data);
                    $("#twiz_ajax_td_val_on_event_" + twiz_numid).fadeIn("fast");
                    $("#twiz_ajax_td_val_on_event_" + twiz_numid).css({color:"green"});
                    bind_twiz_Ajax_TD();bind_twiz_TR_View();
                });
    });    
    $("[name^=twiz_on_event]").focusout(function (){
        var twiz_textid = $(this).attr("name");        
        var twiz_numid = twiz_textid.substring(14,twiz_textid.length);
        var twiz_columnName = "on_event";
        var twiz_txtval = $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).html();
        $("#twiz_ajax_td_edit_" + twiz_columnName + "_" + twiz_numid).hide();
        $("#twiz_input_duration" + twiz_columnName + "_" + twiz_numid).attr({"value" : twiz_txtval});
        $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).fadeIn("fast");  
    }); 
    $("input[name^=twiz_input]").blur(function (){
        var twiz_textid = $(this).attr("name");
        var twiz_columnName = twiz_textid.substring(11,16);
        var twiz_numid = "";
        switch(twiz_columnName){
            case "delay":        
                twiz_numid = twiz_textid.substring(17,twiz_textid.length);
                break;
            case "durat": 
                twiz_columnName = "duration";
                twiz_numid = twiz_textid.substring(20,twiz_textid.length);         
                break;  
        }
        var twiz_txtval = $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).html();
        $("#twiz_ajax_td_edit_" + twiz_columnName + "_" + twiz_numid).hide();
        $("#twiz_input_duration" + twiz_columnName + "_" + twiz_numid).attr({"value" : twiz_txtval});
        $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).fadeIn("fast");   
    });
    $("input[name^=twiz_input]").keypress(function (e){
        var twiz_textid = $(this).attr("name");
        var twiz_columnName = twiz_textid.substring(11,16);
        var twiz_numid = "";        
        switch(twiz_columnName){
            case "delay": 
                twiz_numid = twiz_textid.substring(17,twiz_textid.length);
                break;
            case "durat":
                twiz_columnName = "duration";
                twiz_numid = twiz_textid.substring(20,twiz_textid.length);
                break;
        }
        switch(e.keyCode){
            case 27:
                $("#twiz_ajax_td_edit_" + twiz_columnName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).fadeIn("fast");
            break;
            case 13:
                var twiz_txtval = $("#twiz_input_" + twiz_columnName + "_" + twiz_numid).val();
                $("#twiz_ajax_td_edit_" + twiz_columnName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).html("");
                $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_loading_" + twiz_columnName + "_" + twiz_numid).html(\'<img\' + \' name="twiz_img_loading_delay_\' + twiz_numid + \'[]" id="twiz_img_loading_delay_\' + twiz_numid + \'[]" src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
                $.post(ajaxurl, { 
                "action": "twiz_ajax_callback",
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.parent::ACTION_EDIT_TD.'",
                "twiz_id": twiz_numid, 
                "twiz_column": twiz_columnName, 
                "twiz_value": twiz_txtval
                }, function(data) {
                    $("#twiz_ajax_td_loading_" + twiz_columnName + "_" + twiz_numid).html("");
                    $("[name^=twiz_on_event]").unbind("change");
                    $("[name^=twiz_on_event]").unbind("focusout");
                    $("input[name^=twiz_input]").unbind("keypress");
                    $("input[name^=twiz_input]").unbind("blur");
                    $("div[id^=twiz_ajax_td_val]").unbind("click");
                    $("div[id^=twiz_ajax_td_val]").unbind("mouseover");
                    $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).html(data);
                    $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).fadeIn("fast");
                    $("#twiz_ajax_td_val_" + twiz_columnName + "_" + twiz_numid).css({color:"green"});
                    bind_twiz_Ajax_TD();bind_twiz_TR_View();
                });
            break;
        }            
    });
    $("div[id^=twiz_ajax_td_val]").click(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_columnName = twiz_textid.substring(17,22);
        var twiz_columnRealName = "";
        var twiz_numid = "";
        var twiz_tdvalue = $(this).html();
        if(twiz_tdvalue.substring(0, 2) == "On" ){
           twiz_tdvalue =  twiz_tdvalue.substring(2, twiz_tdvalue.length);
        }
        switch(twiz_columnName){
            case "delay": 
                twiz_columnRealName = "delay";    
                twiz_numid = twiz_textid.substring(23,twiz_textid.length);
                $("#twiz_ajax_td_val_" + twiz_columnRealName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_edit_" + twiz_columnRealName + "_" + twiz_numid).fadeIn("fast");                
                $("#twiz_input_" + twiz_columnRealName + "_" + twiz_numid).focus();
                break;
            case "durat":
                twiz_columnRealName = "duration";            
                twiz_numid = twiz_textid.substring(26,twiz_textid.length);
                $("#twiz_ajax_td_val_" + twiz_columnRealName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_edit_" + twiz_columnRealName + "_" + twiz_numid).fadeIn("fast");         
                $("#twiz_input_" + twiz_columnRealName + "_" + twiz_numid).focus();                
                break;
            case "on_ev":
                twiz_columnRealName = "on_event"; 
                twiz_numid = twiz_textid.substring(26,twiz_textid.length);
                $("#twiz_ajax_td_edit_" + twiz_columnRealName + "_" + twiz_numid).html(\'<img\' + \' name="twiz_img_loading_delay_\' + twiz_numid + \'[]" id="twiz_img_loading_delay_\' + twiz_numid + \'[]" src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
                $("#twiz_ajax_td_val_" + twiz_columnRealName + "_" + twiz_numid).hide();
                $("#twiz_ajax_td_edit_" + twiz_columnRealName + "_" + twiz_numid).fadeIn("fast");                
                $.post(ajaxurl, {
                "action": "twiz_ajax_callback",
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.parent::ACTION_GET_EVENT_LIST.'",
                "twiz_event": twiz_tdvalue,
                "twiz_id": twiz_numid
                }, function(data) {
                    $("#twiz_ajax_td_edit_on_event_" + twiz_numid ).html(data);
                    $("[name^=twiz_on_event]").unbind("change");
                    $("[name^=twiz_on_event]").unbind("focusout");
                    $("input[name^=twiz_input]").unbind("keypress");
                    $("input[name^=twiz_input]").unbind("blur");
                    $("div[id^=twiz_ajax_td_val]").unbind("click");
                    $("div[id^=twiz_ajax_td_val]").unbind("mouseover");
                    bind_twiz_Ajax_TD();bind_twiz_TR_View();                  
                    $("#twiz_" + twiz_columnRealName + "_" + twiz_numid).focus();
                }); 
                break;                
        }                
    });
  }
  var bind_twiz_TR_View = function() {    
    $(".twiz_list_tr").mouseover(function(){
    if(twiz_current_section_id!="library"){
        var twiz_textid = $(this).attr("name");
        var twiz_numid = twiz_textid.substring(13, twiz_textid.length);
        $("#twiz_vertical_menu").hide();   
        $("#twiz_right_panel").css({"position":"relative", "top":$(this).offset().top - 203});
        if((twiz_view_id != twiz_numid)&&(twiz_view_id!="edit")){
            $("#twiz_list_tr_action_" + twiz_view_id).css("visibility", "hidden");
            $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible");
            twiz_view_id = twiz_numid;
            $("#twiz_right_panel").html(\'<div\' + \' class="twiz-panel-loading"></div>\');
            $("#twiz_right_panel").fadeIn("fast");    
            if(twiz_array_view_id[twiz_numid]===undefined){
                $.post(ajaxurl, {
                "action": "twiz_ajax_callback",
                "twiz_nonce": "'.$this->nonce.'", 
                "twiz_action": "'.parent::ACTION_VIEW.'",
                "twiz_id": twiz_numid
                }, function(data) {
                    $("#twiz_right_panel").html(data);
                    twiz_array_view_id[twiz_numid] = data;
                    $(".twiz-view-more-configs").unbind("click");
                    bind_twiz_view();
                });    
            }else{
                $("#twiz_list_tr_action_" + twiz_numid).css("visibility", "visible");
                $("#twiz_right_panel").html(twiz_array_view_id[twiz_numid]);
                $(".twiz-view-more-configs").unbind("click");
                bind_twiz_view();
                twiz_skip_view = 0;
            }
        }
    }
    });
  } 
  var bind_twiz_Menu = function() {
    $("#twiz_cancel_section").click(function(){
        $("#twiz_add_sections").slideToggle("fast");  
    });
    $("#twiz_add_menu").click(function(){    
        $("#twiz_add_sections").html("");  
        $("#twiz_add_sections").show("fast");  
        twizGetMultiSection("", "'.parent::ACTION_NEW.'");
        twiz_view_id = null;
    });   
    $("#twiz_edit_menu").click(function(){    
        $("#twiz_add_sections").html("");  
        $("#twiz_add_sections").show("fast");  
        twizGetMultiSection(twiz_current_section_id, "'.parent::ACTION_EDIT.'");
        twiz_view_id = null;
    });     
    $("#twiz_delete_menu").click(function(){  
        if (confirm("'.__('Are you sure to delete?', 'the-welcomizer').'")) {
            $.post(ajaxurl, {
            "action": "twiz_ajax_callback",
            "twiz_nonce": "'.$this->nonce.'", 
            "twiz_action": "'.parent::ACTION_DELETE_SECTION.'",
            "twiz_section_id": twiz_current_section_id
            }, function(data) {                
            if(twiz_current_section_id!=twiz_default_section_id){
                twiz_current_section_id = twiz_default_section_id;
                twizGetMenu();           
            }
            $("#qq_upload_list li").remove();
            $("#twiz_export_url").html(""); 
            twizPostMenu(twiz_default_section_id);
            });
        }
    });     
    $("div[id^=twiz_menu_]").click(function(){
        var twiz_textid = $(this).attr("id");
        twiz_current_section_id = twiz_textid.substring(10,twiz_textid.length);
        $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
        $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
        $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});
        $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
        $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
        $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});
        $("#qq_upload_list li").remove(); 
        $("#twiz_export_url").html(""); 
        twizPostMenu(twiz_current_section_id);
    });   
    $("div[id^=twiz_vmenu_]").click(function(){
        var twiz_textid = $(this).attr("id");
        twiz_current_section_id = twiz_textid.substring(11,twiz_textid.length);
        $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
        $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
        $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});
        $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
        $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
        $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});
        $("#qq_upload_list li").remove(); 
        $("#twiz_export_url").html(""); 
        twizPostMenu(twiz_current_section_id);
    });
  }    
  var bind_twiz_Order_by = function() {
    $("a[id^=twiz_order_by_]").click(function(){
        var twiz_textid = $(this).attr("id");
        twiz_order_by = twiz_textid.substring(14,twiz_textid.length);
        twizPostMenu(twiz_current_section_id, twiz_order_by);
    });  
  } 
  function twizPostMenu(twiz_section_id, twiz_order_by){
   $("#twiz_add_sections").html("");  
   $("#twiz_library_menu").attr({"class" : "twiz-menu twiz-display-none"});
   $("#twiz_admin_menu").attr({"class" : "twiz-menu twiz-display-none"});
   $("#twiz_container").slideToggle("fast"); 
   $("#twiz_library_upload").fadeOut("fast");
   $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_MENU.'",
        "twiz_section_id": twiz_section_id,
        "twiz_order_by": twiz_order_by
        }, function(data) {
            $("img[name^=twiz_status_img]").unbind("click");
            $("#twiz_cancel").unbind("click");
            $("a[id^=twiz_order_by_]").unbind("click");
            $("#twiz_save").unbind("click");
            $(".twiz-edit").unbind("click");
            $("img[name^=twiz_edit]").unbind("mouseover");
            $(".twiz-copy").unbind("click");
            $("img[name^=twiz_copy]").unbind("mouseover");                  
            $(".twiz-delete").unbind("click");
            $("img[name^=twiz_delete]").unbind("mouseover");
            $("#twiz_container").html(data);
            $("#twiz_container").slideToggle("fast");  
            twiz_view_id = null;
            twiz_array_view_id = new Array();
            bind_twiz_Status();bind_twiz_Copy();bind_twiz_Delete();bind_twiz_Edit();
            bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
            bind_twiz_More_Configs();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
            bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();bind_twiz_Order_by();          
        });
  }
  var bind_twiz_Save_Section = function() {
    $("#twiz_shortcode").keyup(function (e){
	    $("#twiz_shortcode_sample").html("[twiz id=\"" + $("#twiz_shortcode").val() + "\"]");
    });
    $("input[name=twiz_output_choice]").click(function(){
        var twiz_blockid = $(this).val();
        $(".twiz-custom-message").html("");
        $(".twiz-block-ouput").hide();
        $("#" + twiz_blockid).show();
    });
    $("#twiz_slc_multi_sections").dblclick(function(){
        var twiz_selectval = $("#twiz_slc_multi_sections option:selected").text();
        $("input[id=twiz_section_name]").attr({"value" : twiz_selectval});
        $("#twiz_section_name").css("color", "#000000");
    });
    $("#twiz_slc_sections").change(function(){
        var twiz_section = $("#twiz_slc_sections option:selected").val();
        if((twiz_section!="")){
            $("input[id=twiz_section_name]").attr({"value" : $("#twiz_slc_sections option:selected").text()});
             $("#twiz_section_name").css("color", "#000000");
        }
    });
    $("#twiz_section_name").click(function(){
        $("#twiz_section_name").css("color", "#000000");
    });    
    $("#twiz_save_section").click(function(){
        var twiz_sectionid = [];
        var twiz_validsection = true;
        var twiz_sectiontovalid = "";
        var twiz_sectionok = "";
        if(($("#twiz_section_name").val() == "'.__('Give the section a name', 'the-welcomizer').'")
        || ($("#twiz_section_name").val() == "")){
            $("#twiz_section_name").val("'.__('Give the section a name', 'the-welcomizer').'");
            $("#twiz_section_name").css("color", "#BC0B0B");
            twiz_validsection = false;
        }
        $("input[name=twiz_output_choice]:radio").each(function(){
        if ($(this).attr("checked")){
            twiz_sectiontovalid = $(this).val();
        }});
        switch(twiz_sectiontovalid){
            case "twiz_shortcode_output":
                if($("#twiz_shortcode").val()== ""){
                    $("#twiz_custom_message_0").html("' .__('Please type a short code.', 'the-welcomizer').'");
                    $("#twiz_loading_menu").html("");
                     twiz_validsection = false;
                }else{
                    $("#twiz_custom_message_0").html("");
                }              
                break; 				
            case "twiz_single_output":
                twiz_sectionid[0] = $("#twiz_slc_sections").val();
                if(twiz_sectionid[0] == ""){
                    $("#twiz_custom_message_1").html("' .__('Please choose an output option.', 'the-welcomizer').'");
                    $("#twiz_loading_menu").html("");
                     twiz_validsection = false;
                }else{
                    $("#twiz_custom_message_1").html("");
                }              
                break;
            case "twiz_multiple_output":
                if( $("#twiz_slc_multi_sections :selected").length > 0){
                    $("#twiz_slc_multi_sections :selected").each(function(i, selected) {
                        twiz_sectionid[i] = $(selected).val();
                    });
                }            
                if(twiz_sectionid.length == 0){
                    $("#twiz_custom_message_2").html("' .__('Please select at least one option.', 'the-welcomizer').'");
                    $("#twiz_loading_menu").html("");
                     twiz_validsection = false;
                }else{
                    $("#twiz_custom_message_2").html("");
                }     
            break;
            case "twiz_logic_output":
                if($("#twiz_custom_logic").val()== ""){
                    $("#twiz_custom_message_3").html("' .__('Please type a custom logic.', 'the-welcomizer').'");
                    $("#twiz_loading_menu").html("");
                     twiz_validsection = false;
                }else{
                    $("#twiz_custom_message_3").html("");
                }              
                break;    
        }
        if(twiz_validsection == true){
            $("#twiz_loading_menu").html(\'<div\' + \' class="twiz-menu twiz-noborder-right"><img\' + \' name="twiz_img_loading_add_sections"\' + \' id="twiz_img_loading_add_sections"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" /></div>\');
            $("input[id=twiz_save_section]").attr({"disabled" : "true"});
            $("#twiz_add_sections").hide();
            $.post(ajaxurl, {
                 "action": "twiz_ajax_callback",
                 "twiz_nonce": "'.$this->nonce.'", 
                 "twiz_action": "'.parent::ACTION_SAVE_SECTION.'",
                 "twiz_section_name": $("#twiz_section_name").val(),
                 "twiz_current_section_id": $("#twiz_section_id").val(),
                 "twiz_output_choice": $("input[name=twiz_output_choice]:checked").val(),
                 "twiz_custom_logic": $("#twiz_custom_logic").val(),
                 "twiz_shortcode": $("#twiz_shortcode").val(),
                 "twiz_section_id": JSON.stringify(twiz_sectionid)
                }, function(data) {
                    twiz_current_section_id = data; 
                    twiz_sectionok = twizGetMenu();
                    twizPostMenu(twiz_current_section_id);
                    $("#twiz_loading_menu").html("");
                });
        }
    });
  }  
  var bind_twiz_DynArrows = function() {
   $("select[id^=twiz_'.parent::F_MOVE_LEFT_POS_SIGN_A.']").change(function(){twizChangeDirectionImage("a");});  
   $("select[id^=twiz_'.parent::F_MOVE_TOP_POS_SIGN_A.']").change(function(){twizChangeDirectionImage("a");});   
   $("input[name^=twiz_'.parent::F_MOVE_TOP_POS_A.']").blur(function(){twizChangeDirectionImage("a");});
   $("input[name^=twiz_'.parent::F_MOVE_LEFT_POS_A.']").blur(function(){twizChangeDirectionImage("a");}); 
   $("select[id^=twiz_'.parent::F_MOVE_LEFT_POS_SIGN_B.']").change(function(){twizChangeDirectionImage("b");});
   $("select[id^=twiz_'.parent::F_MOVE_TOP_POS_SIGN_B.']").change(function(){twizChangeDirectionImage("b");});
   $("input[name^=twiz_'.parent::F_MOVE_TOP_POS_B.']").blur(function(){twizChangeDirectionImage("b");});
   $("input[name^=twiz_'.parent::F_MOVE_LEFT_POS_B.']").blur(function(){twizChangeDirectionImage("b");});    
   function twizChangeDirectionImage(ab) {
      var twiz_top_sign  = $("#twiz_move_top_pos_sign_" + ab).val();
      var twiz_top_val   = $("#twiz_move_top_pos_" + ab).val();
      var twiz_left_sign = $("#twiz_move_left_pos_sign_" + ab).val();
      var twiz_left_val  = $("#twiz_move_left_pos_" + ab).val();
      var twiz_direction = "";
      var twiz_htmlimage = "";
      if((twiz_top_sign!="=")&&(twiz_left_sign!="=")){
          switch(true){
             case ((twiz_top_val!="")&&(twiz_top_sign=="-")&&(twiz_left_val=="")): 
                twiz_direction = "'.parent::DIMAGE_N.'";
                break;
             case ((twiz_top_val!="")&&(twiz_top_sign=="-")&&(twiz_left_val!="")&&(twiz_left_sign=="+")):
                twiz_direction = "'.parent::DIMAGE_NE.'";
                break;        
             case ((twiz_top_val=="")&&(twiz_left_val!="")&&(twiz_left_sign=="+")): 
                twiz_direction = "'.parent::DIMAGE_E.'";
                break;         
             case ((twiz_top_val!="")&&(twiz_top_sign=="+")&&(twiz_left_val!="")&&(twiz_left_sign=="+")): 
                twiz_direction = "'.parent::DIMAGE_SE.'";    
                break;     
             case ((twiz_top_val!="")&&(twiz_top_sign=="+")&&(twiz_left_val=="")): 
                twiz_direction = "'.parent::DIMAGE_S.'";    
                break; 
             case ((twiz_top_val!="")&&(twiz_top_sign=="+")&&(twiz_left_val!="")&&(twiz_left_sign=="-")): 
                twiz_direction = "'.parent::DIMAGE_SW.'";    
                break;    
             case ((twiz_top_val=="")&&(twiz_left_val!="")&&(twiz_left_sign=="-")): 
                twiz_direction = "'.parent::DIMAGE_W.'";    
                break;          
             case ((twiz_top_val!="")&&(twiz_top_sign=="-")&&(twiz_left_val!="")&&(twiz_left_sign=="-")): 
                twiz_direction = "'.parent::DIMAGE_NW.'";    
                break;           
          }
          if(twiz_direction!=""){ 
              twiz_htmlimage = \'<div\' + \' class="twiz-arrow twiz-arrow-\' + twiz_direction + \'"></div>\';
          }
      }
      $("#twiz_td_arrow_" + ab).html(twiz_htmlimage);
    }
   }
    var bind_twiz_Library_New_Order = function() {
        $("div[name^=twiz_new_order_up]").click(function(){
            var twiz_textid = $(this).attr("id");
            var twiz_numid = twiz_textid.substring(18,twiz_textid.length);
            $("#twiz_list_td_" + twiz_numid).html(\'<img\' + \' name="twiz_img_loading_delay_\' + twiz_numid + \'[]"\' + \' id="twiz_img_loading_delay_\' + twiz_numid + \'[]"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
            twizOrderLibrary("'.parent::LB_ORDER_UP.'", twiz_numid);
        });
        $("div[name^=twiz_new_order_down]").click(function(){
            var twiz_textid = $(this).attr("id");
            var twiz_numid = twiz_textid.substring(20, twiz_textid.length);
            $("#twiz_list_td_" + twiz_numid).html(\'<img\' + \' name="twiz_img_loading_delay_\' + twiz_numid + \'[]"\' + \' id="twiz_img_loading_delay_\' + twiz_numid + \'[]"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
            twizOrderLibrary("'.parent::LB_ORDER_DOWN.'", twiz_numid);
        });        
    }
    function twizOrderLibrary(twiz_order, twiz_id){
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_ORDER_LIBRARY.'",
        "twiz_order": twiz_order,
        "twiz_id": twiz_id
        }, function(data) {                
            $("div[name^=twiz_new_order_up]").unbind("click");
            $("div[name^=twiz_new_order_down]").unbind("click");
            $(".twiz-delete").unbind("click");            
            $("#twiz_container").html(data);
            bind_twiz_Status();bind_twiz_Delete();
            bind_twiz_Library_New_Order();
        });   
  }
  var bind_twiz_FooterMenu = function() {
    $("#twiz_export").click(function(){
        $("#twiz_export_url").html(\'<img\' + \' name="twiz_img_loading_export"\' + \' id="twiz_img_loading_export"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" />\');
        $("#qq_upload_list li").remove(); 
        var twiz_animid = $("#twiz_id").val();
        if(twiz_animid===undefined){
           twiz_animid = "";
        }
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_EXPORT.'",
        "twiz_section_id": twiz_current_section_id,
        "twiz_id": twiz_animid
        }, function(data) {
           $("#twiz_export_url").html(data);
        });
    });   
    $("div[id^=twiz_library]").click(function(){
        $("#twiz_add_sections").hide();
        twizSwitchFooterMenu();
        twizPostLibrary();
    });     
    $("div[id^=twiz_admin]").click(function(){
      $("#twiz_add_sections").hide();
      twizSwitchFooterMenu();
      $("#twiz_container").slideToggle("fast"); 
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_ADMIN.'"
        }, function(data) {                
            $("#twiz_container").html(data);
            $("#twiz_container").slideToggle("fast"); 
            $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
            $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
            $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});
            $("#twiz_admin_menu").attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
            $("#twiz_library_menu").attr({"class" : "twiz-menu twiz-display-none"});
            bind_twiz_AdminSave();
            twiz_current_section_id = "admin";
        }); 
    });     
  }  
  function twizSwitchFooterMenu(){
      $("#twiz_export").fadeOut("fast");
      $("#twiz_import").fadeOut("fast");
      $("#qq_upload_list li").remove();
      $("#twiz_export_url").html(""); 
  }  
  function twizPostLibrary(){
      $("#twiz_container").slideToggle("fast"); 
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_LIBRARY.'"
        }, function(data) {                
            $("img[name^=twiz_status]").unbind("click");
            $(".twiz-delete").unbind("click");            
            $("#twiz_container").html(data);
            $("#twiz_container").slideToggle("fast"); 
            twiz_current_section_id = "library";
            $("#twiz_admin_menu").attr({"class" : "twiz-menu twiz-display-none"});
            $("div[id^=twiz_menu_]").attr({"class" : "twiz-menu twiz-display-none"});
            $("div[id^=twiz_vmenu_]").attr({"class" : "twiz-menu"});
            $("div[id^=twiz_status_menu_]").attr({"class" : "twiz-display-none"});            
            $("#twiz_library_menu").attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
            bind_twiz_Status();bind_twiz_Delete();
            bind_twiz_Library_New_Order();
        });   
  }
  function twizGetMenu(){
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'",       
        "twiz_action": "'.parent::ACTION_GET_MENU.'",
        "twiz_section_id": twiz_current_section_id
        }, function(datamenu) {   
              $.post(ajaxurl, {
                "action": "twiz_ajax_callback",
                "twiz_nonce": "'.$this->nonce.'",       
                "twiz_action": "'.parent::ACTION_GET_VMENU.'",
                "twiz_section_id": twiz_current_section_id
                }, function(data) {                
                    $("#twiz_ajax_menu").html(datamenu);              
                    $("div[id^=twiz_menu_]").unbind("click");
                    $("div[id^=twiz_vmenu_]").unbind("click");
                    $("#twiz_add_menu").unbind("click");
                    $("#twiz_delete_menu").unbind("click");
                    $("#twiz_cancel_section").unbind("click");
                    $("#twiz_vertical_menu").html(data);
                    $("img[name^=twiz_status_img]").unbind("click");
                    bind_twiz_Menu(); 
                    bind_twiz_Status();                    
                    $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
                    $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
                    $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});                    
                    return true;
                });  
        });   
  }  
  function twizGetMultiSection(twiz_section_id, twiz_action_lbl){
      $("#twiz_loading_menu").html(\'<div\' + \' class="twiz-menu twiz-noborder-right"><img\' + \' name="twiz_img_loading_add_sections"\' + \' id="twiz_img_loading_add_sections"\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" /></div>\');
      $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'",
        "twiz_action": "'.parent::ACTION_GET_MULTI_SECTION.'",
        "twiz_action_lbl": twiz_action_lbl,
        "twiz_section_id": twiz_section_id
        }, function(data) {                
            $("div[id^=twiz_menu_]").unbind("click");
            $("div[id^=twiz_vmenu_]").unbind("click");
            $("#twiz_add_menu").unbind("click");
            $("#twiz_edit_menu").unbind("click");
            $("#twiz_delete_menu").unbind("click");
            $("#twiz_cancel_section").unbind("click");
            $("#twiz_save_section").unbind("click");
            $("#twiz_section_name").unbind("click");
            $("#twiz_slc_sections").unbind("change");
            $("#twiz_loading_menu").html("");
            $("#twiz_add_sections").html(data);
            $("img[name^=twiz_status_img]").unbind("click");
            bind_twiz_Menu();     
            bind_twiz_Status();             
            bind_twiz_Save_Section();
        });   
  }  
  var bind_twiz_view = function() {
    $(".twiz-view-more-configs").click(function(){   
        $(".twiz-tr-view-more").toggle();
    }); 
    $(".twiz-anim-link").click(function(){
        var twiz_textid = $(this).attr("id");
        var twiz_numid  = twiz_textid.substring(15,twiz_textid.length-2);
        twiz_view_id = "edit";
        $(this).hide();
        $(this).after(\'<br><img\' + \' src="'.$this->pluginUrl.'\' + twiz_skin + \'/images/twiz-loading.gif" /><br>\');  
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_EDIT.'",
        "twiz_id": twiz_numid,
        "twiz_section_id": twiz_current_section_id
        }, function(data) {
            $("img[name^=twiz_status_img]").unbind("click");
            $(".twiz-edit").unbind("click");
            $("img[name^=twiz_edit]").unbind("mouseover");
            $(".twiz-copy").unbind("click");
            $("img[name^=twiz_copy]").unbind("mouseover");              
            $(".twiz-delete").unbind("click");
            $("img[name^=twiz_delete]").unbind("mouseover");            
            $("#twiz_cancel").unbind("click");
            $("#twiz_container").html(data);
            twiz_view_id = null;
            $("#twiz_container").show("slow");
            bind_twiz_Status();bind_twiz_Save();bind_twiz_Cancel();bind_twiz_Number_Restriction();
            bind_twiz_More_Configs();bind_twiz_Choose_Options();bind_twiz_Select_Functions();
            bind_twiz_DynArrows();
        });
    });    
  }
  $("#twiz_footer_menu").mouseover(function(){
     $("#twiz_right_panel").fadeOut("fast");   
     $(".twiz_list_tr_action").css("visibility", "hidden");
     twiz_view_id = null;
  });    
  $("#twiz_footer").mouseover(function(){
     $("#twiz_right_panel").fadeOut("fast");   
     $(".twiz_list_tr_action").css("visibility", "hidden");
     twiz_view_id = null;
  });  
  $("#twiz_header").mouseover(function(){
     $("#twiz_right_panel").fadeOut("fast");   
     $(".twiz_list_tr_action").css("visibility", "hidden");
     twiz_view_id = null;
  });    
  $("#twiz_menu").mouseover(function(){   
     $("#twiz_right_panel").fadeOut("fast");   
     $(".twiz_list_tr_action").css("visibility", "hidden");
     twiz_view_id = null;
  });   
  $("#twiz_more_menu").click(function(){   
     $("#twiz_vertical_menu").toggle();     
  });
  $("#twiz_vertical_menu").mouseenter(function(){   
     $("#twiz_right_panel").fadeOut("fast");   
     $(".twiz_list_tr_action").css("visibility", "hidden");
     twiz_view_id = null; 
  });  
  $("#twiz_head_logo").click(function (){   
    if($("#twiz_skin_bullet").css("top")=="22px"){
        $("#twiz_skin_bullet").stop().animate({top:"-=22px"},1000, function(){
        $("#twiz_skin_bullet").css("z-index","1");});
    }
    if($("#twiz_skin_bullet").css("top")=="0px"){
        $("#twiz_skin_bullet").css("z-index","-1");
        $("#twiz_skin_bullet").stop().animate({top:"+=22px"},500, function(){});
    }    
  });       
  $(".twiz-skins").click(function(){   
      var twiz_textid = $(this).attr("id");
      var twiz_skinname = twiz_textid.substring(10, twiz_textid.length); 
      var twiz_css_skin = "'.$this->pluginUrl.parent::SKIN_PATH.'" + twiz_skinname + "/twiz-style.css";
      twiz_skin = "'.parent::SKIN_PATH.'" + twiz_skinname;
      $("#twiz-'.$this->cssVersion.'-a-css").attr("href",twiz_css_skin);
      $.post(ajaxurl, {
      "action": "twiz_ajax_callback",
      "twiz_skin": twiz_skinname, 
      "twiz_nonce": "'.$this->nonce.'", 
      "twiz_action": "'.parent::ACTION_SAVE_SKIN.'"
      }, function(data) { }); 
  });   
  var bind_twiz_ads = function() {
      $("#twiz_show_more_ads").click(function (){   
          $("#twiz_show_more_ads").hide();
          $("#twiz_more_ads").toggle("slow");
          $("#twiz_show_less_ads").show();
      });   
      $("#twiz_show_less_ads").click(function (){   
          $("#twiz_show_less_ads").hide();
          $("#twiz_more_ads").toggle("slow");
          $("#twiz_show_more_ads").show();
      });     
      $(".twiz-ads-img").mouseenter(function (){   
          $(this).stop().animate({opacity:1},100, function(){});   
      });  
      $(".twiz-ads-img").mouseout(function (){   
          $(this).stop().animate({opacity:0.3},50, function(){});   
      });    
  }
  function twiz_skin_bullet(){  
    if($("#twiz_skin_bullet").css("top")=="0px"){
       $("#twiz_skin_bullet").css("z-index","-1");
       setTimeout(function(){$("#twiz_skin_bullet").stop().animate({top:"+=22px"},1000, function(){});}, 550);  
    } 
  }
  function twiz_ads(){
    if(twiz_ads_active == true){ 
        $.post(ajaxurl, {
        "action": "twiz_ajax_callback",
        "twiz_nonce": "'.$this->nonce.'", 
        "twiz_action": "'.parent::ACTION_GET_MAIN_ADS.'"
        }, function(data) { 
          $("#twiz_footer").html(data);
          bind_twiz_ads();
          setTimeout(function(){$("#twiz_more_ads").toggle("slow"); 
          $("#twiz_show_more_ads").show();}, 2750); 
        });  
    }else{
        $("#twiz_footer").html("");
    }
  }
  twiz_skin_bullet();twiz_ads();
  $("#twiz_menu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected twiz-display-block"});
  $("#twiz_status_menu_" + twiz_current_section_id).attr({"class" : "twiz-status-menu twiz-display-block"});
  $("#twiz_vmenu_" + twiz_current_section_id).attr({"class" : "twiz-menu twiz-menu-selected"});
  bind_twiz_Status();bind_twiz_New();bind_twiz_Edit();bind_twiz_Copy();bind_twiz_Delete();
  bind_twiz_Cancel();bind_twiz_Save();bind_twiz_Number_Restriction();
  bind_twiz_More_Configs();bind_twiz_Choose_Options();bind_twiz_Order_by();
  bind_twiz_Ajax_TD();bind_twiz_DynArrows();bind_twiz_TR_View();bind_twiz_Menu();
  bind_twiz_Save_Section();bind_twiz_FooterMenu();bind_twiz_Select_Functions();
  $("#twiz_container").slideToggle("slow");
 });';
       return $header;
    }
}?>