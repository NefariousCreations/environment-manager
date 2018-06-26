<?php

/**
 * Add Options Page
 */
if( function_exists('acf_add_options_page') ) {

  acf_add_options_page(array(
    'page_title' 	=> 'Environment Manager',
    'menu_title'	=> 'Environment Manager',
    'menu_slug' 	=> 'environment-manager',
    'capability'	=> 'edit_posts',
    'redirect'		=> false
  ));

}
