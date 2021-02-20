<?php 
/*
    Plugin Name: WP TXT Sitemap
    Plugin URI: https://kuegy.com/wordpress/plugins/kuegy-soft
    Description: WP TXT Sitemap creates an TXT Sitemap For your website. <a href="options-reading.php#wptxtsitemap">Options</a>.
    Version: 1.3
    Author: kuegy
    Author URI: https://kuegy.com
    Requires at least: 3.5
    Tested up to: 5.4
    Text Domain: kgytxtsitemap
*/
if(!defined('ABSPATH'))
    exit;
	
define('TXT_FILENAME', 'sitemap-fresh-post.xml'); //задаём имя файла	
	

// Setting Registration & Load textdomain
include 'includes/init.php';
// Section & Fields Callbacks
include 'includes/callbacks.php';

/* ------------------------------------------------------------------------ *
 * Activation Hook
 * ------------------------------------------------------------------------ */
if ( !function_exists( 'kuegy_txtsitemap_activation' ) ):
    function kuegy_txtsitemap_activation(){
        do_action( 'kuegy_txtsitemap_default_options' );
    }
    register_activation_hook( __FILE__, 'kuegy_txtsitemap_activation' );
endif;
/* ------------------------------------------------------------------------ *
 * Load TextDomain
 * ------------------------------------------------------------------------ */
if ( !function_exists( 'kgytxtsitemap_load_plugin_textdomain' ) ):
    function kgytxtsitemap_load_plugin_textdomain() {
        load_plugin_textdomain( 'kgytxtsitemap', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }
    add_action( 'plugins_loaded', 'kgytxtsitemap_load_plugin_textdomain' );
endif;
/* ------------------------------------------------------------------------ *
 * Set default values and create sitemap for first time [Fired On Plugin Activation]
 * ------------------------------------------------------------------------ */
if ( !function_exists( 'kuegy_txtsitemap_default_values' ) ):
    function kuegy_txtsitemap_default_values(){
        // Form settings
        update_option('kuegy_txtsitemap_include_categories',0);
        update_option('kuegy_txtsitemap_include_posts',1);
        update_option('kuegy_txtsitemap_include_pages',0);
        
        update_option('kuegy_txtsitemap_ping_google',1);
        update_option('kuegy_txtsitemap_ping_bing',1);
        
        // delete old options
        delete_option('kuegy_txtsitemap_include_homepage');
        delete_option('kuegy_txtsitemap_publish');
        delete_option('kuegy_txtsitemap_save_post');
        // run for first time
        add_action("admin_init", "kuegy_txtsitemap_full_txt_sitemap");
        
    }
    add_action( 'kuegy_txtsitemap_default_options', 'kuegy_txtsitemap_default_values' );
endif;
/* ------------------------------------------------------------------------ *
 * Start Saving TXT-Sitemap
 * ------------------------------------------------------------------------ */
if ( !function_exists( 'kuegy_txtsitemap_full_txt_sitemap' ) ):
    function kuegy_txtsitemap_full_txt_sitemap() {
        
        $sitemap = home_url();
		/* создаём урл главной */
		$sitemap = '<url>'. PHP_EOL .
      '<loc>'. $sitemap .'</loc>'. PHP_EOL .
      '<lastmod>'. current_time( 'Y-m-d' ) .'</lastmod>'. PHP_EOL .
      '<changefreq>daily</changefreq>'. PHP_EOL .
      '<priority>0.8</priority>'. PHP_EOL .
    '</url>'. PHP_EOL;
		

        if (get_option('kuegy_txtsitemap_include_categories')):
            
            $categories = get_categories( array(
                    'orderby' => 'name',
                    'order'   => 'ASC'
            ) );
            foreach( $categories as $category ){
                $sitemap .= "\n".get_category_link( $category->term_id );
            } 

        endif;//kuegy_txtsitemap_include_categories

        if (get_option('kuegy_txtsitemap_include_pages')):
                $cpt = array('post','page');
            else:
                $cpt = array('post');
        endif;//kuegy_txtsitemap_include_pages
        
        $kuegy_txtsitemap_urls = get_posts(array(
            //'numberposts' => -1,
			'numberposts' => 1,
            'orderby' => 'modified',
            'post_type'  => $cpt,
            'order'    => 'DESC'
        ));

       /* foreach($kuegy_txtsitemap_urls as $post) {
            setup_postdata($post);
            $sitemap .= "\n";
            $sitemap .= get_permalink($post->ID);
        }*/
		
		foreach($kuegy_txtsitemap_urls as $post) {
            setup_postdata($post);
			$postdate = explode(" ", $post->post_modified);            
			
			$sitemap .= '<url>'. PHP_EOL .
      '<loc>'. get_permalink($post->ID) .'</loc>'. PHP_EOL .
      '<lastmod>'. $postdate[0] .'</lastmod>'. PHP_EOL .
      '<changefreq>daily</changefreq>'. PHP_EOL .
      '<priority>0.8</priority>'. PHP_EOL .
    '</url>'. PHP_EOL;	
			
        }
		
		
		$sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL. '
  <urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL
  . $sitemap . PHP_EOL
  . '</urlset>';
  

		
		
		
		
        // save txt file
        $txtfile = ABSPATH . TXT_FILENAME;
        // In our example we're opening $filename in append mode.
        // The file pointer is at the bottom of the file hence
        // that's where $somecontent will go when we fwrite() it.
        if (!$handle = fopen($txtfile, 'w')) {
            add_action('admin_notices', 'kuegy_txtsitemap_error_notice_not_writable');
            exit;
        }

        // Write $somecontent to our opened file.
        if (fwrite($handle, $sitemap) === FALSE) {
            add_action('admin_notices', 'kuegy_txtsitemap_error_notice_not_writable');
            exit;
        }

        add_action('admin_notices', 'kuegy_txtsitemap_success_notice');
        update_option('kuegy_txtsitemap_last_updated',current_time( 'mysql' ));
        fclose($handle);

        // ping search engines
        if (get_option('kuegy_txtsitemap_ping_bing')):
            $pingBing = 'http://www.bing.com/webmaster/ping.aspx?siteMap='.home_url().'/'.TXT_FILENAME;
            wp_remote_get( $pingBing );
        endif;//kuegy_txtsitemap_ping_bing
        
        if (get_option('kuegy_txtsitemap_ping_google')):
            $pingGoogle = 'https://www.google.com/webmasters/sitemaps/ping?sitemap='.home_url().'/'.TXT_FILENAME;
            wp_remote_get( $pingGoogle );
        endif;//kuegy_txtsitemap_ping_google
    }

        add_action( 'kuegy_txtsitemap_event','kuegy_txtsitemap_full_txt_sitemap' );
        add_action("save_post", "kuegy_txtsitemap_cron");
        add_action( 'updated_option', 'kuegy_txtsitemap_cron', 10, 3 );
endif;
/* ------------------------------------------------------------------------ *
 * Show Successs Notice After Sitemap Creation
 * ------------------------------------------------------------------------ */
if ( !function_exists( 'kuegy_txtsitemap_cron' ) ):
    function kuegy_txtsitemap_cron(){
        wp_schedule_single_event( time(), 'kuegy_txtsitemap_event' );
    }
endif;
/* ------------------------------------------------------------------------ *
 * Show Successs Notice After Sitemap Creation
 * ------------------------------------------------------------------------ */
if ( !function_exists( 'kuegy_txtsitemap_success_notice' ) ):
    function kuegy_txtsitemap_success_notice(){
        ?>
        <div class="updated notice is-dismissible">
            <p>
                <span class="dashicons dashicons-networking"></span>
                <a href="<?php home_url(); ?>/<?php echo TXT_FILENAME; ?>" target="_blank"><?php echo TXT_FILENAME; ?></a>
                <?php _e('auto generated, Go to <a href="options-reading.php#wptxtsitemap">WP TXT Sitemap settings.</a>','kgytxtsitemap'); ?>
            </p>
        </div>
        <?php
    }
endif;
/* ------------------------------------------------------------------------ *
 * Show Error Notice IF Sitemap Not Created
 * ------------------------------------------------------------------------ */
if ( !function_exists( 'kuegy_txtsitemap_error_notice_not_writable' ) ):
    function kuegy_txtsitemap_error_notice_not_writable(){
        ?>
        <div class="error notice is-dismissible">
            <p>
                <span class="dashicons dashicons-networking"></span>
                <a href="<?php home_url(); ?>/<?php echo TXT_FILENAME; ?>" target="_blank"><?php echo TXT_FILENAME; ?></a>
                <?php _e(' not writable, ask your hosting provider to set correct permessions.','kgytxtsitemap'); ?>
            </p>
        </div>
        <?php
    }
endif;