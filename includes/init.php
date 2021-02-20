<?php
if(!defined('ABSPATH'))
    exit;

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */
 
/**
 * This function is registered with the 'admin_init' hook.
 */
if ( !function_exists( 'kuegy_txtsitemap_init_options' ) ):
    add_action('admin_init', 'kuegy_txtsitemap_init_options',99999999);
    function kuegy_txtsitemap_init_options() {
        // First, we register a section. This is necessary since all future options must belong to one. 
        add_settings_section(
            'general_settings_section',         // ID used to identify this section and with which to register options
            __( 'WP TXT Sitemap Options', 'kgytxtsitemap' ),                 // Title to be displayed on the administration page
            'kuegy_txtsitemap_general_options_callback', // Callback used to render the description of the section
            'reading'                       // Page on which to add this section of options
        );
        
        // Next, we will introduce the fields for toggling the visibility of content elements.
        add_settings_field( 
            'kgytxtsitemap_callbacks',                      // ID used to identify the field throughout the theme
            __( 'TXT Sitemap Content', 'kgytxtsitemap' ),                          // The label to the left of the option interface element
            'kgytxtsitemap_callbacks',   // The name of the function responsible for rendering the option interface
            'reading',                          // The page on which this option will be displayed
            'general_settings_section',         // The name of the section to which this field belongs
            array(                              // The array of arguments to pass to the callback. In this case, just a description.
                __( 'kgytxtsitemap callbacks', 'kgytxtsitemap' ),
            )
        );

    // First, we register a section. This is necessary since all future options must belong to one. 
        add_settings_section(
            'kuegy_txtsitemap_ping_settings_section',         // ID used to identify this section and with which to register options
            __( 'Sitemap Ping Options', 'kgytxtsitemap' ),                  // Title to be displayed on the administration page
            'kuegy_txtsitemap_ping_options_callback', // Callback used to render the description of the section
            'reading'                           // Page on which to add this section of options
        );
        add_settings_field( 
            'kuegy_txtsitemap_ping',                      
            __( 'Auto ping', 'kgytxtsitemap' ),
            'kuegy_txtsitemap_ping_callback',   
            'reading',                          
            'kuegy_txtsitemap_ping_settings_section',         
            array(                              
                __( 'Auto ping Google', 'kgytxtsitemap' ),
            )
        );

        // First, we register a section. This is necessary since all future options must belong to one. 
        // add_settings_section(
        //     'kuegy_txtsitemap_time_section',         // ID used to identify this section and with which to register options
        //     __( 'Sitemap Update Options', 'kgytxtsitemap' ),                  // Title to be displayed on the administration page
        //     'kuegy_txtsitemap_description_callback', // Callback used to render the description of the section
        //     'reading'                           // Page on which to add this section of options
        // );
        // add_settings_field( 
        //     'kuegy_txtsitemap_time',                      
        //     __( 'Update Sitemap', 'kgytxtsitemap' ),
        //     'kuegy_txtsitemap_time_callback',   
        //     'reading',                          
        //     'kuegy_txtsitemap_time_section',         
        //     array(                              
        //         __( 'Sitemap Update Options', 'kgytxtsitemap' ),
        //     )
        // );

        // Finally, we register the fields with WordPress

        register_setting(
            'reading',
            'kuegy_txtsitemap_include_categories'
        );
        
        register_setting(
            'reading',
            'kuegy_txtsitemap_include_pages'
        );

        register_setting(
            'reading',
            'kuegy_txtsitemap_include_posts'
        );

        register_setting(
            'reading',
            'kuegy_txtsitemap_ping_google'
        );
        register_setting(
            'reading',
            'kuegy_txtsitemap_ping_bing'
        );
        
        register_setting(
            'reading',
            'kuegy_txtsitemap_last_updated'
        );

    } // end kuegy_txtsitemap_init_options
endif;