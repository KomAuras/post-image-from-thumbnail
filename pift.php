<?php
/*
Plugin Name: Add Post image from thumbnail
Description: При добавлении новой записи, в тело записи в начало добавляется картинка из превьюшки поста.
Version: 1.0
Author: Evgeny Stefanenko
Author URI: www.millenniumfoto.com
*/

add_filter( 'wp_insert_post_data' , 'filter_post_data' , '99', 2 );

function filter_post_data( $data , $postarr ) {
    if( ! isset($_POST['post_type']) || $_POST['post_type'] != 'post' ) return $data; // убедимся что мы редактируем нужный тип поста
    if( get_current_screen()->id != 'post' ) return $data; // убедимся что мы на нужной странице админки
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return $data; // пропустим если это автосохранение
    if ( ! current_user_can('edit_post', $postarr['ID'] ) ) return $data; // убедимся что пользователь может редактировать запись

    $attach_id = get_post_thumbnail_id($postarr->ID);
    if ($attach_id && !preg_match('/cthulhu/', $data['post_content'])) {
        $data['post_content'] = wp_get_attachment_image($attach_id, 'medium', false, array('class'=>'cthulhu attachment-medium alignright')).$data['post_content'];
    }
    return $data;
}
