<?php

if ( ! class_exists( 'bwps_admin_common' ) ) {

	class bwps_admin_common extends bit51_bwps {
		
		/**
		 * Redirects to homepage if awaymode is active
		 *
		 **/
		function awaycheck() {
		
			global $bwps;
			
			if( $bwps->checkaway() ) {
				wp_redirect( get_option( 'siteurl' ) );
				wp_clear_auth_cookie();
			}
			
		}
		
		/**
		 * Deletes BWPS options from .htaccess
		 *
		 * Deletes all possible BWPS options from .htaccess and cleans for rewrite
		 *
		 * @return int -1 for failure, 1 for success
		 *
		 **/
		function deletehtaccess( $section = 'Better WP Security' ) {
		
			global $bwpsoptions;
				
			$htaccess = ABSPATH . '.htaccess';
			
			@ini_set( 'auto_detect_line_endings', true );
			
			if ( ! file_exists( $htaccess ) ) {
				$ht = @fopen( $htaccess, 'a+' );
				@fclose( $ht );
			}
						
			$markerdata = explode( PHP_EOL, implode( '', file( $htaccess ) ) ); //parse each line of file into array
		
			if ( $markerdata ) { //as long as there are lines in the file
					
				$state = true;
						
				if ( ! $f = @fopen( $htaccess, 'w+' ) ) {
							
					@chmod( $htaccess, 0644 );
					
					if ( ! $f = @fopen( $htaccess, 'w+' ) ) {
								
						return -1;
								
					}
							
				}
						
				foreach ( $markerdata as $n => $markerline ) { //for each line in the file
						
					if ( strpos( $markerline, '# BEGIN ' . $section ) !== false ) { //if we're at the beginning of the section
						$state = false;
					}
							
					if ( $state == true ) { //as long as we're not in the section keep writing

						fwrite( $f, trim( $markerline ) . PHP_EOL );
						
					}
							
					if ( strpos( $markerline, '# END ' . $section ) !== false ) { //see if we're at the end of the section
						$state = true;
					}
							
				}
						
				@fclose( $f );
				
				if ( $bwpsoptions['st_fileperm'] == 1 ) {
					@chmod( $htaccess, 0444 );
				}
						
				return 1;
						
			}
				
			return 1; //nothing to write
					
		}
		
		/**
		 * Deletes BWPS options from wp-config
		 *
		 * Deletes all possible BWPS options from wp-config and cleans for rewrite
		 *
		 * @return int -1 for failure, 1 for success
		 *
		 **/
		function deletewpconfig() {
		
			global $bwpsoptions;
		
			$configfile = $this->getConfig();
			
			@ini_set( 'auto_detect_line_endings', true );
						
			$lines = explode( PHP_EOL, implode( '', file( $configfile ) ) );
			
			if ( isset( $lines ) ) { //as long as there are lines in the file
						
				$state = true;
								
				if ( ! $f = @fopen( $configfile, 'w+' ) ) {
							
					@chmod( $configfile, 0644 );
					
					if ( ! $f = @fopen( $configfile, 'w+' ) ) {
								
						return -1;
								
					}
							
				}
							
				foreach ( $lines as $line ) { //for each line in the file
											
					if ( ! strstr( $line, 'DISALLOW_FILE_EDIT' ) && ! strstr( $line, 'FORCE_SSL_LOGIN' ) && ! strstr( $line, 'FORCE_SSL_ADMIN' ) ) {
						
						fwrite( $f, trim( $line ) . PHP_EOL );
						
					}
														
				}
							
				@fclose( $f );
				
				if ( $bwpsoptions['st_fileperm'] == 1 ) {
					@chmod( $configfile, 0444 );
				}
							
				return 1;
							
			}
					
			return 1; //nothing to write
				
		}
				
		/**
		 * Gets location of wp-config.php
		 *
		 * Finds and returns path to wp-config.php
		 *
		 * @return string path to wp-config.php
		 *
		 **/
		function getConfig() {
		
			if ( file_exists( trailingslashit( ABSPATH ) . 'wp-config.php' ) ) {
			
				return trailingslashit( ABSPATH ) . 'wp-config.php';
				
			} else {
			
				return trailingslashit( dirname( ABSPATH ) ) . 'wp-config.php';
				
			}
			
		}
		
		/**
		 * Generates rewrite rules
		 *
		 * Generates rewrite rules for use in Apache or NGINX
		 *
		 * @return string|boolean Rewrite rules or false if unsupported server
		 *
		 **/
		function getrules() {
		
			global $bwpsoptions;
		
			@ini_set( 'auto_detect_line_endings', true );
		
			//figure out what server they're using
			if ( strstr( strtolower( $_SERVER['SERVER_SOFTWARE'] ), 'apache' ) ) {
			
				$bwpsserver = 'apache';
				
			} else if ( strstr( strtolower( $_SERVER['SERVER_SOFTWARE'] ), 'nginx' ) ) {
			
				$bwpsserver = 'nginx';
				
			} else if ( strstr( strtolower( $_SERVER['SERVER_SOFTWARE'] ), 'litespeed' ) ) {
			
				$bwpsserver = 'litespeed';
				
			} else { //unsupported server
			
				return false;
			
			}
			
			$rules = '';
			
			//remove directory indexing
			if ( $bwpsoptions['st_ht_browsing'] == 1 ) {
			
				if ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) {
				
					$rules .= "Options All -Indexes" . PHP_EOL . PHP_EOL;
				
				}
				
			}
			
			//ban hosts
			if ( $bwpsoptions['bu_enabled'] == 1 ) {
			
				$hosts = explode( PHP_EOL, $bwpsoptions['bu_banlist'] );
				
				if ( ! empty( $hosts ) && ! ( sizeof( $hosts ) == 1 && trim( $hosts[0] ) == ''  ) ) {
				
					if ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) {
					
						$rules .= "Order allow,deny" . PHP_EOL .
						"Allow from all" . PHP_EOL .
						"Deny from ";
						
					}
					
					foreach ( $hosts as $host ) {
							
						if ( strstr( trim( $host ), '*' ) ) {
						
							$parts = array_reverse ( explode( '.', trim( $host ) ) );
							$netmask = 32;
							
							foreach ( $parts as $part ) {
								
								if ( strstr( trim( $part ), '*' ) ) {
								
									$netmask = $netmask - 8;
								
								}
								
							}

							if ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) {
							
								$rules .= str_replace('*', '0', implode( '.', array_reverse( $parts ) ) ) . '/' . $netmask . ' ';
								
							} else {
							
								$rules .= "\tdeny " . str_replace('*', '0', implode( '.', array_reverse( $parts ) ) ) . '/' . $netmask . ";" . PHP_EOL;
							
							}
						
						} else {
						
							if ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) {
							
								$rules .= trim( $host ) . ' ';
								
							} else {
							
								$rules .= "\tdeny " . trim( $host ) . ";" . PHP_EOL;
							
							}
						
						}				
					
					}
				
					$rules .= PHP_EOL . PHP_EOL;					
				
				}
			
			}
			
			//lockdown files
			if ( $bwpsoptions['st_ht_files'] == 1 ) {
			
				if ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) {
				
					$rules .= 
						"<files .htaccess>" . PHP_EOL .
							"Order allow,deny" .  PHP_EOL .
							"Deny from all" . PHP_EOL .
						"</files>" . PHP_EOL . PHP_EOL .
						"<files readme.html>" . PHP_EOL .
							"Order allow,deny" . PHP_EOL .
							"Deny from all" . PHP_EOL .
						"</files>" . PHP_EOL . PHP_EOL .
						"<files readme.txt>" . PHP_EOL .
							"Order allow,deny" . PHP_EOL .
							"Deny from all" . PHP_EOL .
						"</files>" . PHP_EOL . PHP_EOL .
						"<files install.php>" . PHP_EOL .
							"Order allow,deny" . PHP_EOL .
							"Deny from all" . PHP_EOL .
						"</files>" . PHP_EOL . PHP_EOL .
						"<files wp-config.php>" . PHP_EOL .
							"Order allow,deny" . PHP_EOL .
							"Deny from all" . PHP_EOL .
						"</files>" . PHP_EOL . PHP_EOL;
					
				} else {
				
					$rules .= 
						"\tlocation ~ /\.ht {" . PHP_EOL .
						"\t\tdeny all;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tlocation ~ wp-config.php {" . PHP_EOL .
						"\t\tdeny all;". PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tlocation ~ readme.html {" . PHP_EOL .
						"\t\tdeny all;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tlocation ~ readme.txt {" . PHP_EOL .
						"\t\tdeny all;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tlocation ~ /install.php {" . PHP_EOL .
						"\t\tdeny all;". PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL;
				}
				
			}
			
			//start mod_rewrite rules
			if ( $bwpsoptions['st_ht_request'] == 1 || $bwpsoptions['st_comment'] == 1 || $bwpsoptions['st_ht_query'] == 1 || $bwpsoptions['hb_enabled'] == 1 || ( $bwpsoptions['bu_enabled'] == 1 && strlen(  $bwpsoptions['bu_banagent'] ) > 0 ) ) {
			
				if ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) {
				
					$rules .= "<IfModule mod_rewrite.c>" . PHP_EOL .
						"RewriteEngine On" . PHP_EOL . PHP_EOL;
				
				} else {
				
					$rules .= 
						"\tset \$susquery 0;" . PHP_EOL .
						"\tset \$rule_2 0;" . PHP_EOL .
						"\tset \$rule_3 0;" . PHP_EOL . PHP_EOL;
				
				}
			
			}
			
			//ban hosts and agents
			if ( $bwpsoptions['bu_enabled'] == 1 && strlen( $bwpsoptions['bu_banagent'] ) > 0 ) {
				
				$agents = explode( PHP_EOL, $bwpsoptions['bu_banagent'] );
				
				if ( ! empty( $agents ) && ! ( sizeof( $agents ) == 1 && trim( $agents[0] ) == ''  ) ) {
				
					if ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) {
					
						$count = 1;
				
						foreach ( $agents as $agent ) {
							
							$rules .= "RewriteCond %{HTTP_USER_AGENT} ^" . trim( $agent ) . "$";
							
							if ( $count < sizeof( $agents ) ) {
							
								$rules .= " [OR]" . PHP_EOL;
								$count++;
							
							} else {
							
								$rules .= PHP_EOL;
							
							}
							
						}
					
						$rules .= "RewriteRule ^(.*)$ - [F,L]" . PHP_EOL . PHP_EOL;
						
					} else {
					
						$count = 1;
						$alist = '';
						
						foreach ( $agents as $agent ) {
									
							$alist .= trim( $agent );
									
							if ( $count < sizeof( $agents ) ) {
									
								$alist .= '|';
								$count++;
									
							}
									
						}
							
						$rules .= 
							"\tif (\$http_user_agent ~* " . $alist . ") {" . PHP_EOL .
							"\t\treturn 403;" . PHP_EOL .
							"\t}" . PHP_EOL . PHP_EOL;
					}
				
				}
			
			}
			
			if ( $bwpsoptions['st_ht_files'] == 1 ) {
			
				if ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) {
				
					$rules .= "RewriteRule ^wp-admin/includes/ - [F,L]" . PHP_EOL .
						"RewriteRule !^wp-includes/ - [S=3]" . PHP_EOL .
						"RewriteCond %{SCRIPT_FILENAME} !^(.*)wp-includes/ms-files.php" . PHP_EOL .
						"RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]" . PHP_EOL .
						"RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]" . PHP_EOL .
						"RewriteRule ^wp-includes/theme-compat/ - [F,L]" . PHP_EOL . PHP_EOL;
					
				} else {
				
					$rules .= 
						"\trewrite ^wp-includes/(.*).php /not_found last;" . PHP_EOL .
						"\trewrite ^/wp-admin/includes(.*)$ /not_found last;" . PHP_EOL . PHP_EOL;
				
				}
				
			}
			
			if ( $bwpsoptions['st_ht_request'] == 1 ) {
			
				if ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) {
				
					$rules .= "RewriteCond %{REQUEST_METHOD} ^(TRACE|DELETE|TRACK) [NC]" . PHP_EOL .
						"RewriteRule ^(.*)$ - [F,L]" . PHP_EOL . PHP_EOL;
				
				} else {
				
					$rules .= 
					"\tif (\$request_method ~* \"^(TRACE|DELETE|TRACK)\"){" . PHP_EOL .
					"\t\treturn 403;" . PHP_EOL .
					"\t}" . PHP_EOL . PHP_EOL;
				
				}
				
			}
			
			if ( $bwpsoptions['st_comment'] == 1 ) {
			
				if ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) {
				
					$rules .= "RewriteCond %{REQUEST_METHOD} POST" . PHP_EOL .
						"RewriteCond %{REQUEST_URI} ^(.*)wp-comments-post\.php*" . PHP_EOL .
						"RewriteCond %{HTTP_REFERER} !^" . $this->topdomain( get_option( 'siteurl' ) ) . ".* [OR]" . PHP_EOL .
						"RewriteCond %{HTTP_USER_AGENT} ^$" . PHP_EOL . 
						"RewriteRule ^(.*)$ - [F,L]" . PHP_EOL . PHP_EOL;
				
				} else {
				
					$rules .= 
						"\tset \$rule_0 0;" . PHP_EOL . PHP_EOL .
						"\tif (\$request_method ~ \"POST\"){" . PHP_EOL .
						"\t\tset \$rule_0 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .	
						"\tif (\$uri ~ \"^(.*)wp-comments-post.php*\"){" . PHP_EOL .
						"\t\tset \$rule_0 2\$rule_0;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .	
						"\tif (\$http_user_agent ~ \"^$\"){" . PHP_EOL .
						"\t\tset \$rule_0 4\$rule_0;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .	
						"\tif (\$rule_0 = \"421\"){" . PHP_EOL .
						"\t\treturn 403;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL;
				
				}
				
			}
			
			//filter suspicious queries
			if ( $bwpsoptions['st_ht_query'] == 1 ) {
			
				if ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) {
				
					$rules .= "RewriteCond %{QUERY_STRING} \.\.\/ [NC,OR]" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} boot\.ini [NC,OR]" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} tag\= [NC,OR]" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} ftp\:  [NC,OR]" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} http\:  [NC,OR]" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} https\:  [NC,OR]" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} mosConfig_[a-zA-Z_]{1,21}(=|%3D) [NC,OR]" . PHP_EOL . 
						"RewriteCond %{QUERY_STRING} base64_encode.*\(.*\) [NC,OR]" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} ^.*(\[|\]|\(|\)|<|>|ê|\"|;|\?|\*|=$).* [NC,OR]" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} ^.*(&#x22;|&#x27;|&#x3C;|&#x3E;|&#x5C;|&#x7B;|&#x7C;).* [NC,OR]" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} ^.*(%24&x).* [NC,OR]" .  PHP_EOL .
						"RewriteCond %{QUERY_STRING} ^.*(%0|%A|%B|%C|%D|%E|%F|127\.0).* [NC,OR]" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} ^.*(globals|encode|localhost|loopback).* [NC,OR]" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} ^.*(request|select|concat|insert|union|declare).* [NC]" . PHP_EOL .
						"RewriteCond %{HTTP_COOKIE} !^.*wordpress_logged_in_.*$" . PHP_EOL .
						"RewriteRule ^(.*)$ - [F,L]" . PHP_EOL . PHP_EOL;
				
				} else {
				
					$rules .= 
					
						"\tif (\$args ~* \"\\.\\./\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"boot.ini\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"tag=\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .			
						"\tif (\$args ~* \"ftp:\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"http:\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"https:\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"(<|%3C).*script.*(>|%3E)\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"mosConfig_[a-zA-Z_]{1,21}(=|%3D)\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"base64_encode\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"(%24&x)\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"(\\[|\\]|\\(|\\)|<|>|ê|\\\"|;|\?|\*|=$)\"){" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"(&#x22;|&#x27;|&#x3C;|&#x3E;|&#x5C;|&#x7B;|&#x7C;|%24&x)\"){" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"(%0|%A|%B|%C|%D|%E|%F|127.0)\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"(globals|encode|localhost|loopback)\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" .PHP_EOL . PHP_EOL .
						"\tif (\$args ~* \"(request|select|insert|concat|union|declare)\") {" . PHP_EOL .
						"\t\tset \$susquery 1;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL;
				
				}
				
			}
			
			if ( $bwpsserver == 'nginx' ) {
			
				$rules .= 
					"\tif (\$http_cookie !~* \"wordpress_logged_in_\" ) {" . PHP_EOL .
					"\t\tset \$susquery \"\${susquery}2\";" . PHP_EOL .
					"\t\tset \$rule_2 1;" . PHP_EOL .
					"\t\tset \$rule_3 1;" . PHP_EOL .
					"\t}" . PHP_EOL . PHP_EOL;
			
			}
			
			if ( $bwpsoptions['st_ht_query'] == 1 ) {
			
				if ( $bwpsserver == 'nginx' ) {
			
					$rules .= 
						"\tif (\$susquery = 12) {" . PHP_EOL .
						"\t\treturn 403;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL;
						
				}
				
			}
			
			//hide backend rules	
			if ( $bwpsoptions['hb_enabled'] == 1 ) {
					
				//get the slugs
				$login = $bwpsoptions['hb_login'];
				$admin = $bwpsoptions['hb_admin'];
				$register = $bwpsoptions['hb_register'];
							
				//generate the key
				$key = $bwpsoptions['hb_key'];
					
				//get the domain without subdomain
				$reDomain = $this->topdomain( get_option( 'siteurl' ) );
				
				$siteurl = explode( '/', get_option( 'siteurl' ) );

				if ( isset ( $siteurl[3] ) ) {

					$dir = '/' . $siteurl[3] . '/';
       
				} else {

					$dir = '/';

				}
			
				//hide wordpress backend
				if ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) {
					
					$rules .= "RewriteRule ^" . $login . "/?$ " . $dir . "wp-login.php?" . $key . " [R,L]" . PHP_EOL . PHP_EOL .
						"RewriteCond %{HTTP_COOKIE} !^.*wordpress_logged_in_.*$" . PHP_EOL .
						"RewriteRule ^" . $admin . "/?$ " . $dir . "wp-login.php?" . $key . "&redirect_to=" . $dir . "wp-admin/ [R,L]" . PHP_EOL . PHP_EOL .
						"RewriteRule ^" . $admin . "/?$ " . $dir . "wp-admin/?" . $key . " [R,L]" . PHP_EOL . PHP_EOL .
						"RewriteRule ^" . $register . "/?$ " . $dir . "wp-login.php?" . $key . "&action=register [R,L]" . PHP_EOL . PHP_EOL .
						"RewriteCond %{SCRIPT_FILENAME} !^(.*)admin-ajax\.php" . PHP_EOL . 
						"RewriteCond %{HTTP_REFERER} !^" . $reDomain . $dir . "wp-admin" . PHP_EOL .
						"RewriteCond %{HTTP_REFERER} !^" . $reDomain . $dir . "wp-login\.php" . PHP_EOL .
						"RewriteCond %{HTTP_REFERER} !^" . $reDomain . $dir . $login . PHP_EOL .
						"RewriteCond %{HTTP_REFERER} !^" . $reDomain . $dir . $admin . PHP_EOL .
						"RewriteCond %{HTTP_REFERER} !^" . $reDomain . $dir . $register . PHP_EOL .
						"RewriteCond %{QUERY_STRING} !^" . $key . PHP_EOL .
						"RewriteCond %{QUERY_STRING} !^action=logout" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} !^action=rp" . PHP_EOL .
						"RewriteCond %{QUERY_STRING} !^action=register" . PHP_EOL .
						"RewriteCond %{HTTP_COOKIE} !^.*wordpress_logged_in_.*$" . PHP_EOL .
						"RewriteRule ^.*wp-admin/?|^.*wp-login\.php not_found [L]" . PHP_EOL . PHP_EOL .
						"RewriteCond %{QUERY_STRING} ^loggedout=true" . PHP_EOL .
						"RewriteRule ^.*$ " . $dir . "wp-login.php?" . $key . " [R,L]" . PHP_EOL;
							
				} else {
					
					$rules .= 
						"\trewrite ^" . $dir . $login . "/?$ " . $dir . "wp-login.php?" . $key . " redirect;" . PHP_EOL . PHP_EOL .
						"\tif (\$rule_2 = 1) {" . PHP_EOL .
						"\t\trewrite ^" . $dir . $admin . "/?$ " . $dir . "wp-login.php?" . $key . "&redirect_to=/wp-admin/ redirect;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$rule_2 = 0) {" . PHP_EOL .
						"\t\trewrite ^" . $dir . $admin . "/?$ " . $dir . "wp-admin/?" . $key . " redirect;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\trewrite ^" . $dir . $register . "/?$ " . $dir . "wp-login.php?" . $key . "&action=register redirect;" . PHP_EOL . PHP_EOL .
						"\tif (\$uri !~ \"^(.*)admin-ajax.php\") {" . PHP_EOL .
						"\t\tset \$rule_3 \"\${rule_3}1\";" . PHP_EOL .
						 "\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$http_referer !~* wp-admin ) {" . PHP_EOL .
						"\t\tset \$rule_3 \"\${rule_3}1\";" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$http_referer !~* wp-login.php ) {" . PHP_EOL .
						"\t\tset \$rule_3 \"\${rule_3}1\";" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$http_referer !~* " . $login . " ) {" . PHP_EOL .
						"\t\tset \$rule_3 \"\${rule_3}1\";" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$http_referer !~* " . $admin . " ) {" . PHP_EOL .
						"\t\tset \$rule_3 \"\${rule_3}1\";" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$http_referer !~* " . $register . " ) {" . PHP_EOL .
						"\t\tset \$rule_3 \"\${rule_3}1\";" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args !~ \"^action=logout\") {" . PHP_EOL .
						"\t\tset \$rule_3 \"\${rule_3}1\";" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args !~ \"^" . $key . "\") {" . PHP_EOL .
						"\t\tset \$rule_3 \"\${rule_3}1\";" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$args !~ \"^action=rp\") {" . PHP_EOL .
						"\t\tset \$rule_3 \"\${rule_3}1\";" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL .
						"\tif (\$rule_3 = 1111111111) {" . PHP_EOL .
						"\t\trewrite ^(.*/)?wp-login.php " . $dir . "not_found last;" . PHP_EOL .
						"\t\trewrite ^" . $dir . "wp-admin(.*)$ " . $dir . "not_found last;" . PHP_EOL .
						"\t}" . PHP_EOL . PHP_EOL;
				
				}
	
			}
			
			//close mod_rewrite
			if ( $bwpsoptions['st_ht_request'] == 1 || $bwpsoptions['st_comment'] == 1 || $bwpsoptions['st_ht_query'] == 1 || $bwpsoptions['hb_enabled'] == 1 || ( $bwpsoptions['bu_enabled'] == 1 && strlen(  $bwpsoptions['bu_banagent'] ) > 0 ) ) {
			
				if ( ( $bwpsserver == 'apache' || $bwpsserver == 'litespeed' ) ) {
				
					$rules .= "</IfModule>" . PHP_EOL;
				
				}
			
			}
			
			//add markers if we have rules
			if ( $rules != '' ) {
				$rules = "# BEGIN Better WP Security" . PHP_EOL . $rules . PHP_EOL . "# END Better WP Security" . PHP_EOL;
			}
				
			return $rules;
		
		}
		
		/**
		 * Generates wp-confing rules
		 *
		 * Generates wp-confing rules
		 *
		 * @return string wp-confing rules
		 *
		 **/
		function getwpcontent() {
		
			global $bwpsoptions;
			
			@ini_set( 'auto_detect_line_endings', true );
			
			if ( $bwpsoptions['st_fileedit'] == 1 || $bwpsoptions['ssl_forcelogin'] == 1 || $bwpsoptions['ssl_forceadmin'] == 1 ) {
			
			$rules = "//BEGIN Better WP Security" . PHP_EOL;
			
			if ( $bwpsoptions['st_fileedit'] == 1 ) {
			
				$rules .= "define('DISALLOW_FILE_EDIT', true);" . PHP_EOL;
			
			}
			
			if ( $bwpsoptions['ssl_forcelogin'] == 1 ) {
			
				$rules .= "define('FORCE_SSL_LOGIN', true);" . PHP_EOL;
			
			}
			
			if ( $bwpsoptions['ssl_forceadmin'] == 1 ) {
			
				$rules .= "define('FORCE_SSL_ADMIN', true);" . PHP_EOL;
			
			}
			
			$rules .= "//END Better WP Security" . PHP_EOL;
			
			} else {
			
				$rules = '';
				
			}
			
			return $rules;
		
		}
		
		/**
		 * Generates secret key
		 *
		 * Generates secret key for hide backend function
		 *
		 * @return string key
		 *
		 **/
		function hidebe_genKey() {
		
			$size = 20; //length of key
			$chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; //available characters
			srand( ( double ) microtime() * 1000000 ); //random seed
			$pass = '' ;
				
			for ( $i = 0; $i <= $size; $i++ ) {
			
				$num = rand() % 33;
				$tmp = substr( $chars, $num, 1 );
				$pass = $pass . $tmp;
				
			}
			
			return $pass;	
			
		}
				
		/**
		 * Return primary domain from given url
		 *
		 * Returns primary domsin name (without subdomains) of given URL
		 *
		 * @param string $address address to filter
		 * @return string domain name
		 *
		 **/		
		function topdomain( $address ) {
		
			preg_match( "/^(http:\/\/)?([^\/]+)/i", $address, $matches );
			$host = $matches[2];
			preg_match( "/[^\.\/]+\.[^\.\/]+$/", $host, $matches );
			$newAddress =  "(.*)" . $matches[0] ;
			
			return $newAddress;
			
		}
		
		/**
		 * Checks if user exists
		 *
		 * Checks to see if WordPress user with given username exists
		 *
		 * @param string $username login username of user to check
		 * @return bool true if user exists otherwise false
		 *
		 **/
		function user_exists( $username ) {
		
			global $wpdb;
			
			//return false if username is null
			if ( $username == '' ) {
				return false;
			}
			
			//queary the user table to see if the user is there
			$user = $wpdb->get_var( "SELECT user_login FROM `" . $wpdb->users . "` WHERE user_login='" . sanitize_text_field( $username ) . "';" );
			
			if ( $user == $username ) {
				return true;
			} else {
				return false;
			}
			
		}	
		
		/**
		 * Writes .htaccess options
		 *
		 * Writes various Better WP Security options to the .htaccess file
		 *
		 * @return int Write results -1 for error, 1 for success
		 *
		 **/
		function writehtaccess() {
		
			global $bwpsoptions;
			
			//clean up old rules first
			if ( $this->deletehtaccess() == -1 ) {
			
				return -1; //we can't write to the file
			
			}
			
			$htaccess = ABSPATH . '.htaccess';
			
			//get the subdirectory if it is installed in one
			$siteurl = explode( '/', get_option( 'siteurl' ) );
			
			if ( isset ( $siteurl[3] ) ) {
			
				$dir = '/' . $siteurl[3] . '/';
				
			} else {
			
				$dir = '/';
			
			}		
						
			if ( ! $f = @fopen( $htaccess, 'a+' ) ) {
						
				@chmod( $htaccess, 0644 );
				
				if ( ! $f = @fopen( $htaccess, 'a+' ) ) {
							
					return -1;
							
				}
						
			}
			
			@ini_set( 'auto_detect_line_endings', true );
			
			$ht = explode( PHP_EOL, implode( '', file( $htaccess ) ) ); //parse each line of file into array
			
			$rules = $this->getrules();	
			
			$rulesarray = explode( PHP_EOL, $rules );
			
			$contents = array_merge( $rulesarray, $ht );
			 
			if ( ! $f = @fopen( $htaccess, 'w+' ) ) {
				
				return -1; //we can't write to the file
				
			}
			
			$blank = false;
			
			//write each line to file
			foreach ( $contents as $insertline ) {
			
				if ( trim( $insertline ) == '' ) {
					if ( $blank == false ) {
					
						fwrite( $f, PHP_EOL . trim( $insertline ) );
						
					}
					
					$blank = true;
				
				} else {
					
					$blank = false;
					
					fwrite( $f, PHP_EOL . trim( $insertline ) );
					
				}
				
			}
				
			@fclose( $f );
			
			if ( $bwpsoptions['st_fileperm'] == 1 ) {
				@chmod( $htaccess, 0444 );
			}
			
			return 1; //success
		
		}
		
		/**
		 * Writes wp-config.php options
		 *
		 * Writes various Better WP Security options to the wp-config.php file
		 *
		 * @return int Write results -1 for error, 1 for success
		 *
		 **/
		function writewpconfig() {
		
			global $bwpsoptions;
		
			//clear the old rules first
			if ( $this->deletewpconfig() == -1 ) {
			
				return -1; //we can't write to the file
			
			}
			
			$lines = '';
			
			$configfile = $this->getconfig();
			
			@ini_set( 'auto_detect_line_endings', true );
			
			$config = explode( PHP_EOL, implode( '', file( $configfile ) ) );
			
			if ( $bwpsoptions['st_fileedit'] == 1 ) {
			
				$lines .= "define('DISALLOW_FILE_EDIT', true);" . PHP_EOL . PHP_EOL;
			
			}
			
			if ( $bwpsoptions['ssl_forcelogin'] == 1 ) {
			
				$lines .= "define('FORCE_SSL_LOGIN', true);" . PHP_EOL;
			
			}
			
			if ( $bwpsoptions['ssl_forceadmin'] == 1 ) {
			
				$lines .= "define('FORCE_SSL_ADMIN', true);" . PHP_EOL . PHP_EOL;
			
			}
			
			if ( ! $f = @fopen( $configfile, 'w+' ) ) {
						
				@chmod( $configfile, 0644 );
				
				if ( ! $f = @fopen( $configfile, 'w+' ) ) {
							
					return -1;
							
				}
						
			}
			
			$blank = false;
			
			//rewrite each appropriate line
			foreach ($config as $line) {
			
				if ( strstr( $line, "/* That's all, stop editing! Happy blogging. */" ) ) {
				
					$line = $lines . $line; //paste ending 
				
				}
				
				if ( trim( $line ) == '' ) {
					if ( $blank == false ) {
					
						fwrite( $f, PHP_EOL . trim( $line ) );
						
					}
					
					$blank = true;
				
				} else {
					
					$blank = false;
									
					if ( strstr( $line, '<?php' ) ) {
						fwrite( $f, trim( $line ) );
					} else {	
						fwrite( $f, PHP_EOL . trim( $line ) );
					}
					
				}
				
			}
			
			@fclose( $f );
			
			if ( $bwpsoptions['st_fileperm'] == 1 ) {
				@chmod( $configfile, 0444 );
			}
			
			return 1; //success
		
		}
			
	}	
	
}
