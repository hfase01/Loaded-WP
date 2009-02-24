<?php
/**
 * Misc WordPress Administration API.
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @return unknown
 */
function got_mod_rewrite() {
	$got_rewrite = apache_mod_loaded('mod_rewrite', true);
	return apply_filters('got_rewrite', $got_rewrite);
}

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param unknown_type $filename
 * @param unknown_type $marker
 * @return array An array of strings from a file (.htaccess ) from between BEGIN and END markers.
 */
function extract_from_markers( $filename, $marker ) {
	$result = array ();

	if (!file_exists( $filename ) ) {
		return $result;
	}

	if ( $markerdata = explode( "\n", implode( '', file( $filename ) ) ));
	{
		$state = false;
		foreach ( $markerdata as $markerline ) {
			if (strpos($markerline, '# END ' . $marker) !== false)
				$state = false;
			if ( $state )
				$result[] = $markerline;
			if (strpos($markerline, '# BEGIN ' . $marker) !== false)
				$state = true;
		}
	}

	return $result;
}

/**
 * {@internal Missing Short Description}}
 *
 * Inserts an array of strings into a file (.htaccess ), placing it between
 * BEGIN and END markers. Replaces existing marked info. Retains surrounding
 * data. Creates file if none exists.
 *
 * @since unknown
 *
 * @param unknown_type $filename
 * @param unknown_type $marker
 * @param unknown_type $insertion
 * @return bool True on write success, false on failure.
 */
function insert_with_markers( $filename, $marker, $insertion ) {
	if (!file_exists( $filename ) || is_writeable( $filename ) ) {
		if (!file_exists( $filename ) ) {
			$markerdata = '';
		} else {
			$markerdata = explode( "\n", implode( '', file( $filename ) ) );
		}

		$f = fopen( $filename, 'w' );
		$foundit = false;
		if ( $markerdata ) {
			$state = true;
			foreach ( $markerdata as $n => $markerline ) {
				if (strpos($markerline, '# BEGIN ' . $marker) !== false)
					$state = false;
				if ( $state ) {
					if ( $n + 1 < count( $markerdata ) )
						fwrite( $f, "{$markerline}\n" );
					else
						fwrite( $f, "{$markerline}" );
				}
				if (strpos($markerline, '# END ' . $marker) !== false) {
					fwrite( $f, "# BEGIN {$marker}\n" );
					if ( is_array( $insertion ))
						foreach ( $insertion as $insertline )
							fwrite( $f, "{$insertline}\n" );
					fwrite( $f, "# END {$marker}\n" );
					$state = true;
					$foundit = true;
				}
			}
		}
		if (!$foundit) {
			fwrite( $f, "\n# BEGIN {$marker}\n" );
			foreach ( $insertion as $insertline )
				fwrite( $f, "{$insertline}\n" );
			fwrite( $f, "# END {$marker}\n" );
		}
		fclose( $f );
		return true;
	} else {
		return false;
	}
}

/**
 * Updates the htaccess file with the current rules if it is writable.
 *
 * Always writes to the file if it exists and is writable to ensure that we
 * blank out old rules.
 *
 * @since unknown
 */
function save_mod_rewrite_rules() {
	global $wp_rewrite;

	$home_path = get_home_path();
	$htaccess_file = $home_path.'.htaccess';

	// If the file doesn't already exists check for write access to the directory and whether of not we have some rules.
	// else check for write access to the file.
	if ((!file_exists($htaccess_file) && is_writable($home_path) && $wp_rewrite->using_mod_rewrite_permalinks()) || is_writable($htaccess_file)) {
		if ( got_mod_rewrite() ) {
			$rules = explode( "\n", $wp_rewrite->mod_rewrite_rules() );
			return insert_with_markers( $htaccess_file, 'WordPress', $rules );
		}
	}

	return false;
}

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param unknown_type $file
 */
function update_recently_edited( $file ) {
	$oldfiles = (array ) get_option( 'recently_edited' );
	if ( $oldfiles ) {
		$oldfiles = array_reverse( $oldfiles );
		$oldfiles[] = $file;
		$oldfiles = array_reverse( $oldfiles );
		$oldfiles = array_unique( $oldfiles );
		if ( 5 < count( $oldfiles ))
			array_pop( $oldfiles );
	} else {
		$oldfiles[] = $file;
	}
	update_option( 'recently_edited', $oldfiles );
}

/**
 * If siteurl or home changed, flush rewrite rules.
 *
 * @since unknown
 *
 * @param unknown_type $old_value
 * @param unknown_type $value
 */
function update_home_siteurl( $old_value, $value ) {
	global $wp_rewrite;

	if ( defined( "WP_INSTALLING" ) )
		return;

	// If home changed, write rewrite rules to new location.
	$wp_rewrite->flush_rules();
}

add_action( 'update_option_home', 'update_home_siteurl', 10, 2 );
add_action( 'update_option_siteurl', 'update_home_siteurl', 10, 2 );

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param unknown_type $url
 * @return unknown
 */
function url_shorten( $url ) {
	$short_url = str_replace( 'http://', '', stripslashes( $url ));
	$short_url = str_replace( 'www.', '', $short_url );
	if ('/' == substr( $short_url, -1 ))
		$short_url = substr( $short_url, 0, -1 );
	if ( strlen( $short_url ) > 35 )
		$short_url = substr( $short_url, 0, 32 ).'...';
	return $short_url;
}

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param unknown_type $vars
 */
function wp_reset_vars( $vars ) {
	for ( $i=0; $i<count( $vars ); $i += 1 ) {
		$var = $vars[$i];
		global $$var;

		if (!isset( $$var ) ) {
			if ( empty( $_POST["$var"] ) ) {
				if ( empty( $_GET["$var"] ) )
					$$var = '';
				else
					$$var = $_GET["$var"];
			} else {
				$$var = $_POST["$var"];
			}
		}
	}
}

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param unknown_type $message
 */
function show_message($message) {
	if( is_wp_error($message) ){
		if( $message->get_error_data() )
			$message = $message->get_error_message() . ': ' . $message->get_error_data();
		else
			$message = $message->get_error_message();
	}
	echo "<p>$message</p>\n";
}

function wp_doc_link_parse( $content ) {
	if ( !is_string( $content ) || empty( $content ) )
		return array();
		
	$tokens = token_get_all( $content );
	$functions = array();
	$ignore_functions = array();
	for ( $t = 0, $count = count( $tokens ); $t < $count; $t++ ) {
		if ( !is_array( $tokens[$t] ) ) continue;
		if ( T_STRING == $tokens[$t][0] && ( '(' == $tokens[ $t + 1 ] || '(' == $tokens[ $t + 2 ] ) ) {
			// If it's a function or class defined locally, there's not going to be any docs available
			if ( 'class' == $tokens[ $t - 2 ][1] || 'function' == $tokens[ $t - 2 ][1] || T_OBJECT_OPERATOR == $tokens[ $t - 1 ][0] ) {
				$ignore_functions[] = $tokens[$t][1];
			}
			// Add this to our stack of unique references
			$functions[] = $tokens[$t][1];
		}
	}
	
	$functions = array_unique( $functions );
	sort( $functions );
	$ignore_functions = apply_filters( 'documentation_ignore_functions', $ignore_functions );
	$ignore_functions = array_unique( $ignore_functions );
	
	$out = array();
	foreach ( $functions as $function ) {
		if ( in_array( $function, $ignore_functions ) )
			continue;
		$out[] = $function;
	}
	
	return $out;
}

/**
 * Determines the language to use for CodePress syntax highlighting,
 * based only on a filename.
 * 
 * @since 2.8
 * 
 * @param string $filename The name of the file to be highlighting
**/
function codepress_get_lang( $filename ) {
	$codepress_supported_langs = apply_filters( 'codepress_supported_langs', 
									array( '.css' => 'css',
											'.js' => 'javascript', 
											'.php' => 'php', 
											'.html' => 'html', 
											'.htm' => 'html', 
											'.txt' => 'text' 
											) );
	$extension = substr( $filename, strrpos( $filename, '.' ) );
	if ( $extension && array_key_exists( $extension, $codepress_supported_langs ) )
		return $codepress_supported_langs[$extension];
	
	return 'generic';
}

/**
 * Adds Javascript required to make CodePress work on the theme/plugin editors.
 * 
 * This code is attached to the action admin_print_footer_scripts.
 * 
 * @since 2.8
**/
function codepress_footer_js() {
	// Script-loader breaks CP's automatic path-detection, thus CodePress.path
	// CP edits in an iframe, so we need to grab content back into normal form
	?><script type="text/javascript">
/* <![CDATA[ */
var codepress_path = '<?php echo includes_url('js/codepress/'); ?>';
jQuery('#template').submit(function(){
	if (jQuery('#newcontent_cp').length)
		jQuery('#newcontent_cp').val(newcontent.getCode()).removeAttr('disabled');
});
/* ]]> */
</script>
<?php
}

?>
