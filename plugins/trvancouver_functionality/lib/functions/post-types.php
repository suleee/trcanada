<?php
/**
 * POST TYPES
 *
 * @link  http://codex.wordpress.org/Function_Reference/register_post_type
 */


// Register Best Custom Post Type
function tr_cpt_best() {

	$labels = array(
		'name'                  => 'Best',
		'singular_name'         => 'Best',
		'menu_name'             => 'Best',
		'name_admin_bar'        => 'Best',
		'archives'              => 'Best Archives',
		'attributes'            => 'Best Attributes',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'All Best',
		'add_new_item'          => 'Add New Best',
		'add_new'               => 'Add New Best',
		'new_item'              => 'New Best',
		'edit_item'             => 'Edit Best',
		'update_item'           => 'Update Best',
		'view_item'             => 'View Best',
		'view_items'            => 'View Best',
		'search_items'          => 'Search Best',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into item',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Best list',
		'items_list_navigation' => 'Best list navigation',
		'filter_items_list'     => 'Filter Best list',
	);
	$args = array(
		'label'                 => 'Best',
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 4,
		'menu_icon'             => 'dashicons-thumbs-up',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'best', $args );

}
add_action( 'init', 'tr_cpt_best', 0 );









// food CPT
function tr_cpt_foodie() {

	$labels = array(
		'name'                  => 'Foodie',
		'singular_name'         => 'Foodie',
		'menu_name'             => 'Foodie',
		'name_admin_bar'        => 'Foodie',
		'archives'              => 'Foodie Archives',
		'attributes'            => 'Foodie Attributes',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'All Foodie',
		'add_new_item'          => 'Add New Foodie',
		'add_new'               => 'Add New Foodie',
		'new_item'              => 'New Foodie',
		'edit_item'             => 'Edit Foodie',
		'update_item'           => 'Update Foodie',
		'view_item'             => 'View Foodie',
		'view_items'            => 'View Foodie',
		'search_items'          => 'Search Foodie',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into item',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Foodie list',
		'items_list_navigation' => 'Foodie list navigation',
		'filter_items_list'     => 'Filter Foodie list',
	);
	$args = array(
		'label'                 => 'Foodie',
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 4,
		'menu_icon'             => 'dashicons-thumbs-up',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'foodie', $args );

}
add_action( 'init', 'tr_cpt_foodie', 0 );




// Register Custom Post Type
function news_post_type() {
	
		$labels = array(
			'name'                  => 'News',
			'singular_name'         => 'News',
			'menu_name'             => 'News',
			'name_admin_bar'        => 'News',
			'archives'              => 'News Archives',
			'attributes'            => 'News Attributes',
			'parent_item_colon'     => 'Parent Item:',
			'all_items'             => 'All News',
			'add_new_item'          => 'Add New Item',
			'add_new'               => 'Add New',
			'new_item'              => 'New News',
			'edit_item'             => 'Edit News',
			'update_item'           => 'Update News',
			'view_item'             => 'View News',
			'view_items'            => 'View News',
			'search_items'          => 'Search News',
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => 'Insert into item',
			'uploaded_to_this_item' => 'Uploaded to this item',
			'items_list'            => 'News list',
			'items_list_navigation' => 'News list navigation',
			'filter_items_list'     => 'Filter food list',
		);
		$args = array(
			'label'                 => 'News',
			'description'           => 'update news',
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', ),
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 6,
			'menu_icon'             => 'dashicons-megaphone',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
		);
		register_post_type( 'news', $args );
	
	}
	add_action( 'init', 'news_post_type', 0 );





// Register Custom Post Type
function life_post_type() {
	
		$labels = array(
			'name'                  => 'Life',
			'singular_name'         => 'Life',
			'menu_name'             => 'Life',
			'name_admin_bar'        => 'Life',
			'archives'              => 'Life Archives',
			'attributes'            => 'Life Attributes',
			'parent_item_colon'     => 'Parent Item:',
			'all_items'             => 'All Life',
			'add_new_item'          => 'Add New Item',
			'add_new'               => 'Add New',
			'new_item'              => 'New Life',
			'edit_item'             => 'Edit Life',
			'update_item'           => 'Update Life',
			'view_item'             => 'View Life',
			'view_items'            => 'View Life',
			'search_items'          => 'Search Life',
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => 'Insert into item',
			'uploaded_to_this_item' => 'Uploaded to this item',
			'items_list'            => 'Life list',
			'items_list_navigation' => 'Life list navigation',
			'filter_items_list'     => 'Filter news list',
		);
		$args = array(
			'label'                 => 'Life',
			'description'           => 'life',
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', ),
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 7,
			'menu_icon'             => 'dashicons-admin-home',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
		);
		register_post_type( 'life', $args );
	
	}
	add_action( 'init', 'life_post_type', 0 );


// Register Custom Post Type
function trtv_post_type() {
	
		$labels = array(
			'name'                  => 'TRtv',
			'singular_name'         => 'TRtv',
			'menu_name'             => 'TRtv',
			'name_admin_bar'        => 'TRtv',
			'archives'              => 'TRtv Archives',
			'attributes'            => 'TRtv Attributes',
			'parent_item_colon'     => 'Parent Item:',
			'all_items'             => 'All TRtv',
			'add_new_item'          => 'Add New Item',
			'add_new'               => 'Add New',
			'new_item'              => 'New TRtv',
			'edit_item'             => 'Edit TRtv',
			'update_item'           => 'Update TRtv',
			'view_item'             => 'View TRtv',
			'view_items'            => 'View TRtv',
			'search_items'          => 'Search TRtv',
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => 'Insert into item',
			'uploaded_to_this_item' => 'Uploaded to this item',
			'items_list'            => 'TRtv list',
			'items_list_navigation' => 'TRtv list navigation',
			'filter_items_list'     => 'Filter TRtv list',
		);
		$args = array(
			'label'                 => 'TRtv',
			'description'           => 'TRtv',
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', ),
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 8,
			'menu_icon'             => 'dashicons-video-alt3',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
		);
		register_post_type( 'trtv', $args );
	
	}
	add_action( 'init', 'trtv_post_type', 0 );



// Register Custom Post Type
function column_post_type() {
	
		$labels = array(
			'name'                  => 'Column',
			'singular_name'         => 'Column',
			'menu_name'             => 'Column',
			'name_admin_bar'        => 'Column',
			'archives'              => 'Column Archives',
			'attributes'            => 'Column Attributes',
			'parent_item_colon'     => 'Parent Item:',
			'all_items'             => 'All Column',
			'add_new_item'          => 'Add New Item',
			'add_new'               => 'Add New',
			'new_item'              => 'New Column',
			'edit_item'             => 'Edit Column',
			'update_item'           => 'Update Column',
			'view_item'             => 'View Column',
			'view_items'            => 'View Column',
			'search_items'          => 'Search Column',
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => 'Insert into item',
			'uploaded_to_this_item' => 'Uploaded to this item',
			'items_list'            => 'Column list',
			'items_list_navigation' => 'Column list navigation',
			'filter_items_list'     => 'Filter Column list',
		);
		$args = array(
			'label'                 => 'Column',
			'description'           => 'Column',
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', ),
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 9,
			'menu_icon'             => 'dashicons-format-aside',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
		);
		register_post_type( 'column', $args );
	
	}
	add_action( 'init', 'column_post_type', 0 );