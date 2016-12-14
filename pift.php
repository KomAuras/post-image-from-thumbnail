<?php
/*
Plugin Name: Add Post image from thumbnail
Description: При добавлении новой записи, в тело записи в начало добавляется картинка из превьюшки поста.
Version: 1.0
Author: Evgeny Stefanenko
Author URI: www.millenniumfoto.com
*/

add_action( 'save_post' , 'pift_save_post' , '99', 3 );

function pift_save_post( $post_ID, $post , $update ) {
    if ( $post->post_type != 'post' || wp_is_post_revision( $post_id ) || $post->post_status != 'publish' )
        return;
    $attach_id = get_post_thumbnail_id($post_ID);
    if ($attach_id && !preg_match('/cthulhu/', $post->post_content)) {
        $my_post = array(
            'ID'            => $post_ID,
            'post_content'  => wp_get_attachment_image($attach_id, 'medium', false, array('class'=>'cthulhu attachment-medium alignright')).$post->post_content
        );
        remove_action( 'save_post', 'pift_save_post' );
        $post_id = wp_update_post( $my_post );
        add_action( 'save_post' , 'pift_save_post' , '99', 3 );
    }
}
