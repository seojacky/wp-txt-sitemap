<?php
/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */

/**
 * This function provides a simple description for the General Options page. 
 *
 * It is called from the 'kuegy_txtsitemap_init_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
 if ( !function_exists( 'kuegy_txtsitemap_general_options_callback' ) ):
    function kuegy_txtsitemap_general_options_callback() {
        echo '<div id="wptxtsitemap"><p>';
        echo '<span class="dashicons dashicons-external"></span> ';

        if (get_option('kuegy_txtsitemap_last_updated')):
            echo '<a href="'.home_url().'/'.TXT_FILENAME.'" target="_blank">'.TXT_FILENAME.'</a> ';
            _e('Last Updated:','kgytxtsitemap');
            echo get_option('kuegy_txtsitemap_last_updated');
            echo '</p>';
        else: 
            _e('WP TXT Sitemap Not Created Yet','kgytxtsitemap');
        endif;

        echo '</p></div>';
    } // end kuegy_txtsitemap_general_options_callback
endif;

if ( !function_exists( 'kuegy_txtsitemap_ping_options_callback' ) ):
    function kuegy_txtsitemap_ping_options_callback() {
        // echo '<p>Auto Ping Google and Bing when sitemap updated.</p>';
        _e('<p>Auto Ping Google and Bing when sitemap updated.</p>','kgytxtsitemap');
    } // end kuegy_txtsitemap_general_options_callback
endif;
if ( !function_exists( 'kuegy_txtsitemap_description_callback' ) ):
    function kuegy_txtsitemap_description_callback() {
        // echo '<p>Auto Ping Google and Bing when sitemap updated.</p>';
        _e('<p>Choose when WP TXT Sitemap plugin update sitemap.txt file.</p>','kgytxtsitemap');
    } // end kuegy_txtsitemap_description_callback
endif;

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */
if ( !function_exists( 'kgytxtsitemap_callbacks' ) ):
    function kgytxtsitemap_callbacks($args) {

        // posts
        $html = '<input type="checkbox" id="kuegy_txtsitemap_include_posts" name="kuegy_txtsitemap_include_posts" value="1" ' . checked(1, get_option('kuegy_txtsitemap_include_posts'), false) . '/>'; 
        $html .= '<label for="kuegy_txtsitemap_include_posts"> '  .  __('Posts') . '</label><br><br>'; 
        // pages
        $html .= '<input type="checkbox" id="kuegy_txtsitemap_include_pages" name="kuegy_txtsitemap_include_pages" value="1" ' . checked(1, get_option('kuegy_txtsitemap_include_pages'), false) . '/>'; 
        $html .= '<label for="kuegy_txtsitemap_include_pages"> '  .  __('Pages') . '</label><br><br>'; 
        // categories
        $html .= '<input type="checkbox" id="kuegy_txtsitemap_include_categories" name="kuegy_txtsitemap_include_categories" value="1" ' . checked(1, get_option('kuegy_txtsitemap_include_categories'), false) . '/>'; 
        $html .= '<label for="kuegy_txtsitemap_include_categories"> '  . __('Categories') . '</label>'; 
        echo $html;
    } // end kgytxtsitemap_callbacks
endif;


if ( !function_exists( 'kuegy_txtsitemap_ping_callback' ) ):
    function kuegy_txtsitemap_ping_callback($args) {
        $html = '<input type="checkbox" id="kuegy_txtsitemap_ping_google" name="kuegy_txtsitemap_ping_google" value="1" ' . checked(1, get_option('kuegy_txtsitemap_ping_google'), false) . '/>'; 
        $html .= '<label for="kuegy_txtsitemap_ping_google"> '  . __('Google') . '</label><br><br>'; 
        $html .= '<input type="checkbox" id="kuegy_txtsitemap_ping_bing" name="kuegy_txtsitemap_ping_bing" value="1" ' . checked(1, get_option('kuegy_txtsitemap_ping_bing'), false) . '/>'; 
        $html .= '<label for="kuegy_txtsitemap_ping_bing"> '  . __('Bing') . '</label>'; 
        echo $html;  
    } // end kuegy_txtsitemap_ping_callback
endif;