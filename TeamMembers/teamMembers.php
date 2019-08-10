<?php
/*
Plugin Name: TeamMembers Plugin
Description: Creates TeamMembers CPT and page template to show members
Version: 1.0
Author: Gabriel Nejoiu
Author URI: http://art-web.ro/wp/
*/




//create page template

class TeamMembers {
	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;
	/**
	 * The array of templates that this plugin tracks.
	 */
	protected $templates;
	/**
	 * Returns an instance of this class.
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new TeamMembers();
		}
		return self::$instance;
	}
	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {

		/* START Team Members Custom Post Type */
			function teamMembers_post_type() {

				$labels = array(
					'name'                  => _x( 'Team Members', 'Post Type General Name', 'text_domain' ),
					'singular_name'         => _x( 'Team Member', 'Post Type Singular Name', 'text_domain' ),
					'menu_name'             => __( 'Team Members', 'text_domain' ),
					'name_admin_bar'        => __( 'Team Member', 'text_domain' ),
					'archives'              => __( 'Item Archives', 'text_domain' ),
					'attributes'            => __( 'Item Attributes', 'text_domain' ),
					'parent_item_colon'     => __( 'Parent Team Member:', 'text_domain' ),
					'all_items'             => __( 'All Team Members', 'text_domain' ),
					'add_new_item'          => __( 'Add New Team Member', 'text_domain' ),
					'add_new'               => __( 'New Team Member', 'text_domain' ),
					'new_item'              => __( 'New Item', 'text_domain' ),
					'edit_item'             => __( 'Edit Team Member', 'text_domain' ),
					'update_item'           => __( 'Update Team Member', 'text_domain' ),
					'view_item'             => __( 'View Team Member', 'text_domain' ),
					'view_items'            => __( 'View Items', 'text_domain' ),
					'search_items'          => __( 'Search Team Members', 'text_domain' ),
					'not_found'             => __( 'No Team Members found', 'text_domain' ),
					'not_found_in_trash'    => __( 'No Team Members found in Trash', 'text_domain' ),
					'featured_image'        => __( 'Featured Image', 'text_domain' ),
					'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
					'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
					'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
					'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
					'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
					'items_list'            => __( 'Items list', 'text_domain' ),
					'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
					'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
				);
				$args = array(
					'label'                 => __( 'Team Member', 'text_domain' ),
					'description'           => __( 'Team Member information pages.', 'text_domain' ),
					'labels'                => $labels,
					'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields' ),
					'taxonomies'            => array( 'type' ),
					'hierarchical'          => false,
					'public'                => true,
					'show_ui'               => true,
					'show_in_menu'          => true,
					'menu_position'         => 5,
					'menu_icon'             => 'dashicons-admin-users',
					'show_in_admin_bar'     => true,
					'show_in_nav_menus'     => true,
					'can_export'            => true,
					'has_archive'           => true,
					'exclude_from_search'   => false,
					'publicly_queryable'    => true,
					'capability_type'       => 'page',
				);
				register_post_type( 'team-members', $args );

			}
			add_action( 'init', 'teamMembers_post_type', 0 );

			/* END Team Members Custom Post Type */


			/* START Departments Custom Taxonomy */
			function departments_taxonomy() {

				$labels = array(
					'name'                       => _x( 'Departments', 'Taxonomy General Name', 'text_domain' ),
					'singular_name'              => _x( 'Department', 'Taxonomy Singular Name', 'text_domain' ),
					'menu_name'                  => __( 'Departments', 'text_domain' ),
					'all_items'                  => __( 'All Departments', 'text_domain' ),
					'parent_item'                => __( 'Parent Department', 'text_domain' ),
					'parent_item_colon'          => __( 'Parent Type:', 'text_domain' ),
					'new_item_name'              => __( 'New TypeName', 'text_domain' ),
					'add_new_item'               => __( 'Add New Type', 'text_domain' ),
					'edit_item'                  => __( 'Edit Type', 'text_domain' ),
					'update_item'                => __( 'Update Type', 'text_domain' ),
					'view_item'                  => __( 'View Item', 'text_domain' ),
					'separate_items_with_commas' => __( 'Separate types with commas', 'text_domain' ),
					'add_or_remove_items'        => __( 'Add or remove types', 'text_domain' ),
					'choose_from_most_used'      => __( 'Choose from the most used types', 'text_domain' ),
					'popular_items'              => __( 'Popular Items', 'text_domain' ),
					'search_items'               => __( 'Search regions', 'text_domain' ),
					'not_found'                  => __( 'Not Found', 'text_domain' ),
					'no_terms'                   => __( 'No items', 'text_domain' ),
					'items_list'                 => __( 'Items list', 'text_domain' ),
					'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
				);
				$args = array(
					'labels'                     => $labels,
					'hierarchical'               => true,
					'public'                     => true,
					'show_ui'                    => true,
					'show_admin_column'          => true,
					'show_in_nav_menus'          => true,
					'show_tagcloud'              => true,
				);
				register_taxonomy( 'departments', array( 'team-members' ), $args );

			}
			add_action( 'init', 'departments_taxonomy', 0 );

			/* END Departments Custom Taxonomy */

			// Create Custom Fields
			require_once('CustomFields/customFields.php');

			// add scripts and styles
			add_filter( 'template_include', 'load_members_styles', 1000 );
	        function load_members_styles( $template ){
	             if(is_page_template('PageTemplates/team-members-template.php')){

					wp_enqueue_script('TeamMembers', '/wp-content/plugins/TeamMembers/assets/scripts.js');
					wp_enqueue_style('TeamMembers', '/wp-content/plugins/TeamMembers/assets/styles.css');

	            }
		        return $template; 
		    }



		$this->templates = array();
		// Add a filter to the attributes metabox to inject template into the cache.
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {
			// 4.6 and older
			add_filter(
				'page_attributes_dropdown_pages_args',
				array( $this, 'register_project_templates' )
			);
		} else {
			// Add a filter to the wp 4.7 version attributes metabox
			add_filter(
				'theme_page_templates', array( $this, 'add_new_template' )
			);
		}
		// Add a filter to the save post to inject out template into the page cache
		add_filter(
			'wp_insert_post_data',
			array( $this, 'register_project_templates' )
		);
		// Add a filter to the template include to determine if the page has our
		// template assigned and return it's path
		add_filter(
			'template_include',
			array( $this, 'view_project_template')
		);
		// Add your templates to this array.
		$this->templates = array(
			'PageTemplates/team-members-template.php' => 'Team Members',
		);
	}
	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}
	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 */
	public function register_project_templates( $atts ) {
		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
		// Retrieve the cache list.
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		}
		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');
		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );
		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );
		return $atts;
	}
	/**
	 * Checks if the template is assigned to the page
	 */
	public function view_project_template( $template ) {
		// Return the search template if we're searching (instead of the template for the first result)
		if ( is_search() ) {
			return $template;
		}
		// Get global post
		global $post;
		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}
		// Return default template if we don't have a custom one defined
		if ( ! isset( $this->templates[get_post_meta(
			$post->ID, '_wp_page_template', true
		)] ) ) {
			return $template;
		}
		// Allows filtering of file path
		$filepath = apply_filters( 'page_templater_plugin_dir_path', plugin_dir_path( __FILE__ ) );
		$file =  $filepath . get_post_meta(
			$post->ID, '_wp_page_template', true
		);
		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}
		// Return template
		return $template;
	}
}
add_action( 'plugins_loaded', array( 'TeamMembers', 'get_instance' ) );