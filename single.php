<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;


$header_image = $post->get_image('header_image');
if ($header_image->ID){
    $context['header_image'] = $header_image;
}

if ($header_text = $post->get_field('header_text')) {
    $context['header_text'] = $header_text;
}

if ( post_password_required( $post->ID ) ) {
    Timber::render( 'single-password.twig', $context );
} else {
    switch ($post->post_type) {
//        case 'post':
//            $f_view = 'SITE_' . $post->post_type . '_get_single';
//            $context[$post->post_type] = $f_view($post);
//            Timber::render($post->post_type . '/view.twig', $context);
//            break;

        default:
            Timber::render( array( 'single.twig' ), $context );
    }
}
