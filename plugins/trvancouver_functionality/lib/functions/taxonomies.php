<?php
/**
 * TAXONOMIES
 *
 * @link  http://codex.wordpress.org/Function_Reference/register_taxonomy
 */


// Register Product Type Taxonomy
function best_tax_product_type() {

	$labels = array(
		'name'                       => 'Best Types',
		'singular_name'              => 'Best Type',
		'menu_name'                  => 'Best Type',
		'all_items'                  => 'All Best Types',
		'parent_item'                => 'Parent',
		'parent_item_colon'          => 'Parent Best Type:',
		'new_item_name'              => 'New Best Type',
		'add_new_item'               => 'Add New Best Type',
		'edit_item'                  => 'Edit Best Type',
		'update_item'                => 'Update Best Type',
		'view_item'                  => 'View Best Type',
		'separate_items_with_commas' => 'Separate items with commas',
		'add_or_remove_items'        => 'Add or remove items',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Best Types',
		'search_items'               => 'Search Best Types',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No items',
		'items_list'                 => 'Best Type list',
		'items_list_navigation'      => 'Best Type list navigation',
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
	register_taxonomy( 'best_type', array( 'best' ), $args );

}
add_action( 'init', 'best_tax_product_type', 0 );



// Register Custom Taxonomy
function foodie_tax_product_type() {

	$labels = array(
		'name'                       => 'Foodie Types',
		'singular_name'              => 'Foodie Type',
		'menu_name'                  => 'Foodie Type',
		'all_items'                  => 'All Foodie Types',
		'parent_item'                => 'Parent',
		'parent_item_colon'          => 'Parent Foodie Type:',
		'new_item_name'              => 'New Foodie Type',
		'add_new_item'               => 'Add New Foodie Type',
		'edit_item'                  => 'Edit Foodie Type',
		'update_item'                => 'Update Foodie Type',
		'view_item'                  => 'View Foodie Type',
		'separate_items_with_commas' => 'Separate items with commas',
		'add_or_remove_items'        => 'Add or remove items',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Foodie Types',
		'search_items'               => 'Search Items',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No items',
		'items_list'                 => 'Foodie Type list',
		'items_list_navigation'      => 'Foodie Type list navigation',
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
	register_taxonomy( 'foodie_type', array( 'post' ), $args );

}
add_action( 'init', 'foodie_tax_product_type(', 0 );