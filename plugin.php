<?php
/**
 	* Plugin Name: Add-Art
 	* Plugin URI: http://www.add-art.org
 	* Description Allows custom artshows to be made for the Add-Art browser plugin.
 	* Version: 1.2.2
 	* Author: Add-Art
 	* Author URI: http://www.add-art.org
 	* License: GPL2
 	*/

function addart_add_to_feed() {
	global $post;
	$artshow_value = get_post_meta( $post->ID, '_artshow_value_key', true );
	$thumbnail_value = get_post_meta( $post->ID, '_thumbnail_value_key', true );
	$summary_value = get_post_meta( $post->ID, '_summary_value_key', true );
	$showurl_value = get_post_meta( $post->ID, '_showurl_value_key', true );

	echo("<artshow>{$artshow_value}</artshow>\r\n");
	echo("<thumbnail>{$thumbnail_value}</thumbnail>\r\n");
	echo("<summary>{$summary_value}</summary>\r\n");
	echo("<showurl>{$showurl_value}</showurl>\r\n");
}

function load_js()
{
	wp_enqueue_script('addart_script', plugin_dir_url( __FILE__ ) . 'script.js');
}

function addart_add_meta_box() {

	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'addart_sectionid',
			__( 'Add-Art', 'addart_textdomain' ),
			'addart_meta_box_callback',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'addart_add_meta_box' );
add_action('rss2_item', 'addart_add_to_feed');
add_action('admin_enqueue_scripts', 'load_js');

function addart_meta_box_callback( $post ) {

	wp_nonce_field( 'addart_meta_box', 'addart_meta_box_nonce' );

	$artshow_value = get_post_meta( $post->ID, '_artshow_value_key', true );
	$thumbnail_value = get_post_meta( $post->ID, '_thumbnail_value_key', true );
	$summary_value = get_post_meta( $post->ID, '_summary_value_key', true );
	$showurl_value = get_post_meta( $post->ID, '_showurl_value_key', true );

	echo '<label for="addart_artshow">';
	_e( 'Artshow', 'addart_textdomain' );
	echo '</label>';
	echo '</br>';
	echo '<input type="text" id="addart_artshow_input" name="addart_artshow" value="' . esc_attr( $artshow_value ) . '" size="45" />';
	echo '</br>';
	echo '<i>';
	echo 'Every artshow on Add-Art consists of 8 different artworks.';
	echo '</br>';
	echo 'Each artwork needs to be available in the 19 different dimensions below and named `[number 1-8]artbanner[width]x[height].jpg`';
	echo '</br>';
	echo 'Compress these images into a folder named `images.zip` and paste a link to it here. ';
	echo '</br>';
	echo '<span style="text-decoration:underline; cursor:pointer;" id="addart_artshow_expand_btn">Show dimensions</span>';
	echo '</i>';
	echo '<div id="addart_artshow_expand" style="display:none">';
	echo '<ul style="list-style:none;padding:none;">';
	echo '<li>88&times;15</li>';
	echo '<li>88&times;31</li>';
	echo '<li>120&times;60</li>';
	echo '<li>120&times;90</li>';
	echo '<li>120&times;240</li>';
	echo '<li>120&times;600</li>';
	echo '<li>125&times;125</li>';
	echo '<li>160&times;600</li>';
	echo '<li>180&times;150</li>';
	echo '<li>234&times;60</li>';
	echo '<li>240&times;400</li>';
	echo '<li>250&times;250</li>';
	echo '<li>300&times;250</li>';
	echo '<li>300&times;600</li>';
	echo '<li>336&times;280</li>';
	echo '<li>468&times;60</li>';
	echo '<li>720&times;300</li>';
	echo '<li>728&times;90</li>';
	echo '</ul>';
	echo '</div>';
	echo '</br></br>';
	echo '<label for="addart_thumbnail">';
	_e( 'Thumbnail', 'addart_textdomain' );
	echo '</label>';
	echo '</br>';
	echo '<input type="text" id="addart_thumbnail_input" name="addart_thumbnail" value="' . esc_attr( $thumbnail_value ) . '" size="45" />';
	echo '</br>';
	echo '<i>The plugin uses a 100&times;100px image as a thumbnail image for each artshow.</i>';
	echo '</br></br>';
	echo '<label for="addart_summary">';
	_e( 'Summary', 'addart_textdomain' );
	echo '</label>';
	echo '</br>';
	echo '<textarea id="addart_summary_input" name="addart_summary" value="' . esc_attr( $summary_value ) . '" cols="45" rows="5"></textarea>';
	echo '</br></br>';
	echo '<label for="addart_showurl">';
	_e( 'Link to show info', 'addart_textdomain' );
	echo '</label>';
	echo '</br>';
	echo '<input type="text" id="addart_showurl_input" name="addart_showurl" value="' . esc_attr( $showurl_value ) . '" size="45" />';
	echo '</br>';
}

function addart_save_meta_box_data( $post_id ) {

	if ( ! isset( $_POST['addart_meta_box_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['addart_meta_box_nonce'], 'addart_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	if ( ! isset( $_POST['addart_artshow'] ) ) {
		return;
	}
	if ( ! isset( $_POST['addart_thumbnail'] ) ) {
		return;
	}
	if ( ! isset( $_POST['addart_summary'] ) ) {
		return;
	}
	if ( ! isset( $_POST['addart_showurl'] ) ) {
		return;
	}

	$artshow = sanitize_text_field( $_POST['addart_artshow'] );
	$thumbnail = sanitize_text_field( $_POST['addart_thumbnail'] );
	$summary = sanitize_text_field( $_POST['addart_summary'] );
	$showurl = sanitize_text_field( $_POST['addart_showurl'] );

	update_post_meta( $post_id, '_artshow_value_key', $artshow );
	update_post_meta( $post_id, '_thumbnail_value_key', $thumbnail );
	update_post_meta( $post_id, '_summary_value_key', $summary );
	update_post_meta( $post_id, '_showurl_value_key', $showurl );
}
add_action( 'save_post', 'addart_save_meta_box_data' );



