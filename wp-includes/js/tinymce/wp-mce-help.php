<?php require_once('../../../wp-config.php');
header('Content-Type: text/html; charset=' . get_bloginfo('charset'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title><?php _e('Rich Editor Help') ?></title>
<script type="text/javascript" src="tiny_mce_popup.js"></script>
<?php 
wp_admin_css( 'css/global' );
wp_admin_css();
?>
<style type="text/css">
	body {
        background-color: #eaf3ea;
    }
    #wphead {
		padding-top: 2px;
		padding-left: 15px;
		font-size: 80%;
		border-top: 0;
		background-color: #eaf3ea;
	}
	#adminmenu {
		padding-top: 2px;
		padding-left: 15px;
		font-size: 80%;
		background-color: #eaf3ea;
	}
	#user_info {
		right: 5%;
		top: 5px;
	}
	h2 {
		font-size: 2em;
		border-bottom-width: .5em;
		margin-top: 12px;
		margin-bottom: 2px;
	}
	h3 {
		font-size: 1.1em;
		margin-top: 20px;
		margin-bottom: 0px;
	}
	#flipper {
		margin: 0;
		padding: 5px 20px 10px;
		background-color: #fff;
		border-left: 1px solid #c6d9e9;
		border-bottom: 1px solid #c6d9e9;
	}
	* html {
        overflow-x: hidden;
        overflow-y: scroll;
    }
	#flipper div p {
		margin-top: 0.4em;
		margin-bottom: 0.8em;
		text-align: justify;
	}
	th {
		text-align: center;
	}
	.top th {
		text-decoration: underline;
	}
	.top .key {
		text-align: center;
		width: 36px;
	}
	.top .action {
		text-align: left;
	}
	.align {
		border-left: 3px double #333;
		border-right: 3px double #333;
	}
	#keys p {
		display: inline-block;
		margin: 0px;
		padding: 0px;
	}
	#keys .left { text-align: left; }
	#keys .center { text-align: center; }
	#keys .right { text-align: right; }
	td b {
		font-family: "Times New Roman" Times serif;
	}
	#buttoncontainer {
		text-align: center;
		margin-bottom: 20px;
	}
	#buttoncontainer a, #buttoncontainer a:hover {
		border-bottom: 0px;
	}
</style>
<?php if ( ('rtl' == $wp_locale->text_direction) ) : ?>
<style type="text/css">
	#wphead, #adminmenu {
		padding-left: auto;
		padding-right: 15px;
	}
	#flipper {
		margin: 5px 0 3px 10px;
	}
	#keys .left, .top, .action { text-align: right; }
	#keys .right { text-align: left; }
	td b { font-family: Tahoma, "Times New Roman", Times, serif }
</style>
<?php endif; ?>
<script type="text/javascript">
	window.onkeydown = window.onkeypress = function (e) {
		e = e ? e : window.event;
		if ( e.keyCode == 27 && !e.shiftKey && !e.controlKey && !e.altKey ) {
			window.close();
		}
	}

	function d(id) { return document.getElementById(id); }

	function flipTab(n) {
		for (i=1;i<=4;i++) {
			c = d('content'+i.toString());
			t = d('tab'+i.toString());
			if ( n == i ) {
				c.className = '';
				t.className = 'current';
			} else {
				c.className = 'hidden';
				t.className = '';
			}
		}
	}

    function init() {
        document.getElementById('version').innerHTML = tinymce.majorVersion + "." + tinymce.minorVersion;
        document.getElementById('date').innerHTML = tinymce.releaseDate;
    }
    tinyMCEPopup.onInit.add(init);

    // For modal dialogs in IE
    if (tinymce.isIE)
	   document.write('<base target="_self" />');
</script>
</head>
<body>
<div class="zerosize"></div>
<div id="wphead"><h1><?php echo get_bloginfo('blogtitle'); ?></h1></div>

<ul id="adminmenu">
	<li><a id="tab1" href="javascript:flipTab(1)" title="<?php _e('Basics of Rich Editing') ?>" accesskey="1" class="current"><?php _e('Basics') ?></a></li>
	<li><a id="tab2" href="javascript:flipTab(2)" title="<?php _e('Advanced use of the Rich Editor') ?>" accesskey="2"><?php _e('Advanced') ?></a></li>
	<li><a id="tab3" href="javascript:flipTab(3)" title="<?php _e('Hotkeys') ?>" accesskey="3"><?php _e('Hotkeys') ?></a></li>
	<li><a id="tab4" href="javascript:flipTab(4)" title="<?php _e('About the software') ?>" accesskey="4"><?php _e('About') ?></a></li>
</ul>

<div id="flipper" class="wrap">

<div id="content1">
	<h2><?php _e('Rich Editing Basics') ?></h2>
	<p><?php _e('<em>Rich editing</em>, also called WYSIWYG for What You See Is What You Get, means your text is formatted as you type. The rich editor creates HTML code behind the scenes while you concentrate on writing. Font styles, links and images all appear approximately as they will on the internet.') ?></p>
	<p><?php _e('WordPress includes a rich HTML editor that works well in most web browsers used today. It is powerful but it has limitations. Pasting text from other word processors may not give the results you expect. For best compatibility, use the "Paste as Plain Text" or "Paste from Word" buttons located on the extended (second) toolbar row.') ?></p>
	<p><?php _e('While using the editor, most basic keyboard shortcuts work like in any other text editor. For example: Shift+Enter inserts line break, Ctrl+C = copy, Ctrl+X = cut, Ctrl+Z = undo, Ctrl+Y = redo, Ctrl+B = bold, Ctrl+I = italic, Ctrl+U = underline, etc. (on Mac use the Apple key instead of Ctrl).') ?></p>
    <p><?php _e('If you do not like the way the rich editor works, you may turn it off in the Your Profile and Personal Options form, under Users in the admin menu.') ?></p>
</div>

<div id="content2" class="hidden">
	<h2><?php _e('Advanced Rich Editing') ?></h2>
	<h3><?php _e('Images and Attachments') ?></h3>
	<p><?php _e('There is a button in the editor toolbar for inserting images that are already hosted somewhere on the internet. If you have a URL for an image, click this button and enter the URL in the box which appears.') ?></p>
	<p><?php _e('If you need to upload an image or sound file from your computer, you can use the uploading tool below the editor. The tool will attempt to create a thumbnail-sized image when you upload an image. To insert your uploaded image into the post, first click on the thumbnail to reveal a menu of options. Clicking on a "Using.." or "Linked..." option will change that option. For instance, you might want to use the thumbnail in the post and link it to a page showing the original with a caption. When you have selected the options you like, click "Send to Editor" and your image or file will appear in the post you are editing.') ?></p>
	<h3><?php _e('HTML in the Rich Editor') ?></h3>
	<p><?php _e('Any HTML entered directly into the rich editor will show up as text when the post is viewed. What you see is what you get. When you want to include HTML elements that cannot be generated with the toolbar buttons, you must enter it by hand in the HTML editor. Examples are tables and &lt;code&gt;. To do this, click the HTML button and edit the code, then click Update. If the code is valid and understood by the editor, you should see it rendered immediately.') ?></p>
</div>

<div id="content3" class="hidden">
	<h2><?php _e('Writing at Full Speed') ?></h2>
    <p><?php _e('Rather than reaching for your mouse to click on the toolbar, use these access keys. Windows and Linux use Alt+&lt;letter>. Macintosh uses Ctrl+&lt;letter>.') ?></p>
	<table id="keys" width="100%" border="0">
		<tr class="top"><th class="key center"><?php _e('Letter') ?></th><th class="left"><?php _e('Action') ?></th><th class="key center"><?php _e('Letter') ?></th><th class="left"><?php _e('Action') ?></th></tr>
		<tr><th>n</th><td><?php _e('Check Spelling') ?></td><th>f</th><td class="align left"><?php _e('Align Left') ?></td></tr>
		<tr><th>j</th><td><?php _e('Justify Text') ?></td><th>c</th><td class="align center"><?php _e('Align Center') ?></td></tr>
		<tr><th>k</th><td><strike><?php _e('Strikethrough') ?></strike></td><th>r</th><td class="align right"><?php _e('Align Right') ?></td></tr>
		<tr><th>l</th><td><b>&bull;</b> <?php _e('List') ?></td><th>a</th><td><?php _e('Insert <span class="anchor">Anchor</span>') ?></td></tr>
		<tr><th>o</th><td>1. <?php _e('List') ?></td><th>s</th><td><?php _e('Unlink Anchor') ?></td></tr>
		<tr><th>q</th><td>&rarr;<?php _e('Quote/Indent') ?></td><th>m</th><td><?php _e('Insert Image') ?></td></tr>
		<tr><th>w</th><td>&larr;<?php _e('Unquote/Outdent') ?></td><th>t</th><td><?php _e('Insert "More" Tag') ?></td></tr>
		<tr><th>u</th><td><?php _e('Undo') ?></td><th>e</th><td><?php _e('Edit HTML') ?></td></tr>
		<tr><th>y</th><td><?php _e('Redo') ?></td><th>h</th><td><?php _e('Open Help') ?></td></tr>
	</table>
</div>

<div id="content4" class="hidden">
	<h2><?php _e('About TinyMCE'); ?></h2>

    <p><?php _e('Version:'); ?> <span id="version"></span> (<span id="date"></span>)</p>
	<p><?php printf(__('TinyMCE is a platform independent web based Javascript HTML WYSIWYG editor control released as Open Source under %sLGPL</a>	by Moxiecode Systems AB. It has the ability to convert HTML TEXTAREA fields or other HTML elements to editor instances.'), '<a href="'.get_bloginfo('url').'/wp-includes/js/tinymce/license.txt" target="_blank" title="'.__('GNU Library General Public Licence').'">') ?></p>
	<p><?php _e('Copyright &copy; 2003-2007, <a href="http://www.moxiecode.com" target="_blank">Moxiecode Systems AB</a>, All rights reserved.') ?></p>
	<p><?php _e('For more information about this software visit the <a href="http://tinymce.moxiecode.com" target="_blank">TinyMCE website</a>.') ?></p>

	<div id="buttoncontainer">
		<a href="http://www.moxiecode.com" target="_new"><img src="themes/advanced/img/gotmoxie.png" alt="<?php _e('Got Moxie?') ?>" border="0" /></a>
		<a href="http://sourceforge.net/projects/tinymce/" target="_blank"><img src="themes/advanced/img/sflogo.png" alt="<?php _e('Hosted By Sourceforge') ?>" border="0" /></a>
		<a href="http://www.freshmeat.net/projects/tinymce" target="_blank"><img src="themes/advanced/img/fm.gif" alt="<?php _e('Also on freshmeat') ?>" border="0" /></a>
	</div>

</div>
</div>

<div class="mceActionPanel">
	<div style="margin: 8px auto; text-align: center;padding-bottom: 10px;">
		<input type="button" id="cancel" name="cancel" value="<?php _e('Close'); ?>" title="<?php _e('Close'); ?>" onclick="tinyMCEPopup.close();" />
	</div>
</div>

</body>
</html>
