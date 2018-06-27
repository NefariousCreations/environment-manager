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

/**
 * Add the options page fields
 */
if( function_exists('acf_add_local_field_group') ):

  acf_add_local_field_group(array(
    'key' => 'group_5b323fdecd5db',
    'title' => 'Environment Manager',
    'fields' => array(
      array(
        'key' => 'field_5b3240092cdad',
        'label' => 'Environment',
        'name' => 'environment',
        'type' => 'flexible_content',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'layouts' => array(
          '5b3243a0405aa' => array(
            'key' => '5b3243a0405aa',
            'name' => 'environment_by_url',
            'label' => 'Detect Environment by URL',
            'display' => 'block',
            'sub_fields' => array(
              array(
                'key' => 'field_5b3240422cdae',
                'label' => 'Environment Name',
                'name' => 'environment_name',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
              ),
              array(
                'key' => 'field_5b3240662cdaf',
                'label' => 'Site Url',
                'name' => 'environment_site_url',
                'type' => 'url',
                'instructions' => 'The Site Address (URL), as defined in General Settings',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
              ),
              array(
                'key' => 'field_5b3240f42cdb0',
                'label' => 'Blacklisted Plugins',
                'name' => 'environment_blacklisted_plugins',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '',
                  'class' => '',
                  'id' => '',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => '',
                'sub_fields' => array(
                  array(
                    'key' => 'field_5b3241932cdb1',
                    'label' => 'Plugin Name',
                    'name' => 'environment_blacklisted_plugin_name',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                      'width' => '',
                      'class' => '',
                      'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                  ),
                ),
              ),
            ),
            'min' => '',
            'max' => '',
          ),
        ),
        'button_label' => 'Add Environment',
        'min' => '',
        'max' => '',
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'environment-manager',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
  ));

endif;
