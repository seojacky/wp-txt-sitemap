<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('kuegy_txtsitemap_include_homepage');
delete_option('kuegy_txtsitemap_include_categories');
delete_option('kuegy_txtsitemap_include_posts');
delete_option('kuegy_txtsitemap_include_pages');

delete_option('kuegy_txtsitemap_ping_google');
delete_option('kuegy_txtsitemap_ping_bing');

delete_option('kuegy_txtsitemap_publish');
delete_option('kuegy_txtsitemap_save_post');
delete_option('kuegy_txtsitemap_last_updated');