var topWin=window.dialogArguments||opener||parent||top,uploader,uploader_init;function fileDialogStart(){jQuery("#media-upload-error").empty()}function fileQueued(a){jQuery(".media-blank").remove();if(jQuery("form.type-form #media-items").children().length==1&&jQuery(".hidden","#media-items").length>0){jQuery(".describe-toggle-on").show();jQuery(".describe-toggle-off").hide();jQuery(".slidetoggle").slideUp(200).siblings().removeClass("hidden")}jQuery("#media-items").append('<div id="media-item-'+a.id+'" class="media-item child-of-'+post_id+'"><div class="progress"><div class="bar"></div></div><div class="filename original"><span class="percent"></span> '+a.name+"</div></div>");jQuery(".progress","#media-item-"+a.id).show();jQuery("#insert-gallery").prop("disabled",true)}function uploadStart(a){try{if(typeof topWin.tb_remove!="undefined"){topWin.jQuery("#TB_overlay").unbind("click",topWin.tb_remove)}}catch(b){}return true}function uploadProgress(e,b,d){var a=jQuery("#media-items").width()-2,c=jQuery("#media-item-"+e.id);jQuery(".bar",c).width(a*b/d);jQuery(".percent",c).html(Math.ceil(b/d*100)+"%");if(b==d){jQuery(".bar",c).html('<strong class="crunching">'+pluploadL10n.crunching+"</strong>")}}function updateMediaForm(){var b=jQuery("form.type-form #media-items").children(),a=jQuery("#media-items").children();if(b.length==1){jQuery(".slidetoggle",b).slideDown(500).siblings().addClass("hidden").filter(".toggle").toggle()}if(a.not(".media-blank").length>0){jQuery(".savebutton").show()}else{jQuery(".savebutton").hide()}if(a.length>1){jQuery(".insert-gallery").show()}else{jQuery(".insert-gallery").hide()}}function uploadSuccess(b,a){if(a.match("media-upload-error")){jQuery("#media-item-"+b.id).html(a);return}prepareMediaItem(b,a);updateMediaForm();if(jQuery("#media-item-"+b.id).hasClass("child-of-"+post_id)){jQuery("#attachments-count").text(1*jQuery("#attachments-count").text()+1)}}function prepareMediaItem(c,a){var d=(typeof shortform=="undefined")?1:2,b=jQuery("#media-item-"+c.id);jQuery(".bar",b).remove();jQuery(".progress",b).hide();try{if(typeof topWin.tb_remove!="undefined"){topWin.jQuery("#TB_overlay").click(topWin.tb_remove)}}catch(g){}if(isNaN(a)||!a){b.append(a);prepareMediaItemInit(c)}else{b.load("async-upload.php",{attachment_id:a,fetch:d},function(){prepareMediaItemInit(c);updateMediaForm()})}}function prepareMediaItemInit(b){var a=jQuery("#media-item-"+b.id);jQuery(".thumbnail",a).clone().attr("class","pinkynail toggle").prependTo(a);jQuery(".filename.original",a).replaceWith(jQuery(".filename.new",a));jQuery("a.toggle",a).click(function(){jQuery(this).siblings(".slidetoggle").slideToggle(350,function(){var d=jQuery(window).height(),e=jQuery(this).offset().top,f=jQuery(this).height(),c;if(d&&e&&f){c=e+f;if(c>d&&(f+48)<d){window.scrollBy(0,c-d+13)}else{if(c>d){window.scrollTo(0,e-36)}}}});jQuery(this).siblings(".toggle").andSelf().toggle();jQuery(this).siblings("a.toggle").focus();return false});jQuery("a.delete",a).click(function(){jQuery.ajax({url:"admin-ajax.php",type:"post",success:deleteSuccess,error:deleteError,id:b.id,data:{id:this.id.replace(/[^0-9]/g,""),action:"trash-post",_ajax_nonce:this.href.replace(/^.*wpnonce=/,"")}});return false});jQuery("a.undo",a).click(function(){jQuery.ajax({url:"admin-ajax.php",type:"post",id:b.id,data:{id:this.id.replace(/[^0-9]/g,""),action:"untrash-post",_ajax_nonce:this.href.replace(/^.*wpnonce=/,"")},success:function(d,e){var c=jQuery("#media-item-"+b.id);if(type=jQuery("#type-of-"+b.id).val()){jQuery("#"+type+"-counter").text(jQuery("#"+type+"-counter").text()-0+1)}if(c.hasClass("child-of-"+post_id)){jQuery("#attachments-count").text(jQuery("#attachments-count").text()-0+1)}jQuery(".filename .trashnotice",c).remove();jQuery(".filename .title",c).css("font-weight","normal");jQuery("a.undo",c).addClass("hidden");jQuery("a.describe-toggle-on, .menu_order_input",c).show();c.css({backgroundColor:"#ceb"}).animate({backgroundColor:"#fff"},{queue:false,duration:500,complete:function(){jQuery(this).css({backgroundColor:""})}}).removeClass("undo")}});return false});jQuery("#media-item-"+b.id+".startopen").removeClass("startopen").slideToggle(500).siblings(".toggle").toggle()}function wpQueueError(a){jQuery("#media-upload-error").show().html('<div class="error"><p>'+a+"</p></div>")}function wpFileError(d,c){var b=jQuery("#media-item-"+d.id),a=jQuery(".filename",b).text();b.html('<div class="error-div"><a class="dismiss" href="#">'+pluploadL10n.dismiss+"</a><strong>"+pluploadL10n.error_uploading.replace("%s",a)+"</strong><br />"+c+"</div>");b.find("a.dismiss").click(function(){jQuery(this).parents(".media-item").slideUp(200,function(){jQuery(this).remove()})})}function itemAjaxError(d,b){var c=jQuery("#media-item-"+d),a=jQuery(".filename",c).text();c.html('<div class="error-div"><a class="dismiss" href="#">'+pluploadL10n.dismiss+"</a><strong>"+pluploadL10n.error_uploading.replace("%s",a)+"</strong><br />"+b+"</div>");c.find("a.dismiss").click(function(){jQuery(this).parents(".media-item").slideUp(200,function(){jQuery(this).remove()})})}function deleteSuccess(b,d){if(b=="-1"){return itemAjaxError(this.id,"You do not have permission. Has your session expired?")}if(b=="0"){return itemAjaxError(this.id,"Could not be deleted. Has it been deleted already?")}var c=this.id,a=jQuery("#media-item-"+c);if(type=jQuery("#type-of-"+c).val()){jQuery("#"+type+"-counter").text(jQuery("#"+type+"-counter").text()-1)}if(a.hasClass("child-of-"+post_id)){jQuery("#attachments-count").text(jQuery("#attachments-count").text()-1)}if(jQuery("form.type-form #media-items").children().length==1&&jQuery(".hidden","#media-items").length>0){jQuery(".toggle").toggle();jQuery(".slidetoggle").slideUp(200).siblings().removeClass("hidden")}jQuery(".toggle",a).toggle();jQuery(".slidetoggle",a).slideUp(200).siblings().removeClass("hidden");a.css({backgroundColor:"#faa"}).animate({backgroundColor:"#f4f4f4"},{queue:false,duration:500}).addClass("undo");jQuery(".filename:empty",a).remove();jQuery(".filename .title",a).css("font-weight","bold");jQuery(".filename",a).append('<span class="trashnotice"> '+pluploadL10n.deleted+" </span>").siblings("a.toggle").hide();jQuery(".filename",a).append(jQuery("a.undo",a).removeClass("hidden"));jQuery(".menu_order_input",a).hide();return}function deleteError(c,b,a){}function uploadComplete(){jQuery("#insert-gallery").prop("disabled",false)}function switchUploader(b){var c=document.getElementById("flash-upload-ui"),a=document.getElementById("html-upload-ui");if(b){c.style.display="block";a.style.display="none"}else{c.style.display="none";a.style.display="block"}}function dndHelper(a){var b=document.getElementById("dnd-helper");if(a){b.style.display="block"}else{b.style.display="none"}}function uploadError(b,c,a){switch(c){case plupload.FAILED:wpFileError(b,pluploadL10n.upload_failed);break;case plupload.FILE_EXTENSION_ERROR:wpFileError(b,pluploadL10n.invalid_filetype);break;case plupload.FILE_SIZE_ERROR:wpQueueError(pluploadL10n.file_exceeds_size_limit.replace("%s",b.name));break;case plupload.IMAGE_FORMAT_ERROR:wpFileError(b,pluploadL10n.not_an_image);break;case plupload.IMAGE_MEMORY_ERROR:wpFileError(b,pluploadL10n.image_memory_exceeded);break;case plupload.IMAGE_DIMENSIONS_ERROR:wpFileError(b,pluploadL10n.image_dimensions_exceeded);break;case plupload.GENERIC_ERROR:wpQueueError(pluploadL10n.upload_failed);break;case plupload.IO_ERROR:wpQueueError(pluploadL10n.io_error);break;case plupload.HTTP_ERROR:wpQueueError(pluploadL10n.http_error);break;case plupload.INIT_ERROR:switchUploader(0);jQuery(".upload-html-bypass").hide();break;case plupload.SECURITY_ERROR:wpQueueError(pluploadL10n.security_error);break;default:wpFileError(b,pluploadL10n.default_error)}}jQuery(document).ready(function(a){a('input[type="radio"]',"#media-items").live("click",function(){var b=a(this).closest("tr");if(a(b).hasClass("align")){setUserSetting("align",a(this).val())}else{if(a(b).hasClass("image-size")){setUserSetting("imgsize",a(this).val())}}});a("button.button","#media-items").live("click",function(){var b=this.className||"";b=b.match(/url([^ '"]+)/);if(b&&b[1]){setUserSetting("urlbutton",b[1]);a(this).siblings(".urlfield").val(a(this).attr("title"))}});uploader_init=function(){uploader=new plupload.Uploader(wpUploaderInit);uploader.bind("Init",function(b){if(b.features.dragdrop){a("#plupload-upload-ui").addClass("drag-drop");a("#drag-drop-area").bind("dragover.wp-uploader",function(){a(this).css("border-color","#ccff55")}).bind("dragleave.wp-uploader, drop.wp-uploader",function(){a(this).css("border-color","")})}});uploader.init();uploader.bind("FilesAdded",function(b,c){a.each(c,function(e,d){fileQueued(d)});jQuery("#media-upload-error").html("");b.refresh();b.start()});uploader.bind("BeforeUpload",function(b,c){uploadStart(c)});uploader.bind("UploadProgress",function(b,c){uploadProgress(c,c.loaded,c.size)});uploader.bind("Error",function(b,c){uploadError(c.file,c.code,c.message);b.refresh()});uploader.bind("FileUploaded",function(b,d,c){uploadSuccess(d,c.response)});uploader.bind("UploadComplete",function(b,c){uploadComplete()})};if(typeof(wpUploaderInit)=="object"){uploader_init()}});